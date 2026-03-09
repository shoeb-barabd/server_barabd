<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\AddOn;
use App\Models\Location;
use App\Models\BillingCycle;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    //categories
    public function index(Request $request)
    {
        $selectedCategoryId = $request->integer('category') ?: null;

        // Add-ons
        $addOns = AddOn::where('is_active', true)
            ->with([
                'prices' => function ($q) {
                    // যদি location ফিল্টার না লাগে, একদম ফাঁকা রাখো
                    // $q->whereNotNull('location_id');
                }
            ])
            ->get();

        // Categories + products + সব features + basePrices
        $categories = Category::query()
            ->where('is_active', 1)
            ->orderBy('id')
            ->with([
                'products' => function ($q) {
                    $q->where('is_active', 1)
                        ->orderBy('id')
                        ->with([
                            // সব feature আনো, কোনো fixed key না
                            'features',

                            // base prices (monthly)
                            'basePrices' => function ($bp) {
                                $bp->whereHas('billingCycle', function ($bc) {
                                    $bc->where('key', 'monthly');
                                })
                                    ->with([
                                        'location:id,name,currency_code',
                                        'billingCycle:id,key',
                                    ]);
                            },
                        ]);
                },
            ])
            ->get();

        if ($selectedCategoryId && !$categories->firstWhere('id', $selectedCategoryId)) {
            $selectedCategoryId = null;
        }

        $selectedCategoryId = $selectedCategoryId ?: ($categories->first()?->id);

        $offer = Offer::active()->latest('id')->first();
        $offerUrl = $offer?->url;

        return view('front.index', compact('addOns', 'categories', 'offerUrl', 'offer', 'selectedCategoryId'));
    }


    public function checkout(Request $request, Product $product)
    {
        $locationId = $request->integer('location');

        $product->loadMissing([
            'category:id,name',
            'features' => function ($query) {
                $query->orderBy('id');
            },
        ]);

        $location = null;
        if ($locationId) {
            $location = Location::active()->find($locationId);
        }

        if (!$location) {
            $location = $product->basePrices()
                ->whereHas('location', fn($q) => $q->where('is_active', true))
                ->with('location:id,name,currency_code,tax_rate_percent')
                ->orderBy('location_id')
                ->first()
                    ?->location;
        }

        if (!$location) {
            abort(404, 'Location not available for this product.');
        }

        $prices = $product->basePrices()
            ->where('location_id', $location->id)
            ->with([
                'location:id,name,currency_code,tax_rate_percent',
                'billingCycle:id,key,name,months',
            ])
            ->get();

        if ($prices->isEmpty()) {
            abort(404, 'Pricing not configured for this product.');
        }

        $formatAmount = function ($value) {
            $number = number_format((float) $value, 2, '.', ',');
            return rtrim(rtrim($number, '0'), '.');
        };

        $priceOptions = $prices->mapWithKeys(function ($price) use ($formatAmount) {
            $billingCycle = $price->billingCycle;
            $key = $billingCycle->key ?? 'cycle_' . $billingCycle->id;
            $months = max(1, (int) ($billingCycle->months ?? 1));
            $amount = (float) $price->amount;
            return [
                $key => [
                    'key' => $key,
                    'billing_cycle_id' => $price->billing_cycle_id,
                    'label' => $billingCycle->name ?? ucfirst(str_replace('_', ' ', $key)),
                    'months' => $months,
                    'symbol' => $price->location->currency_symbol ?? '',
                    'currency_code' => $price->location->currency_code ?? 'BDT',
                    'amount' => $amount,
                    'amount_formatted' => $formatAmount($amount),
                    'per_month' => $amount / $months,
                    'per_month_formatted' => $formatAmount($amount / $months),
                    'description' => $months > 1
                        ? "Billed every {$months} months"
                        : 'Billed monthly',
                ],
            ];
        })->toArray();

        // ---------- এখান থেকে yearly কে default বানালাম ----------

        $requestedCycle = $request->input('cycle');

        // ১) যদি request এ cycle না থাকে বা invalid হয়
        if (!$requestedCycle || !array_key_exists($requestedCycle, $priceOptions)) {

            // আগে ধরে নিচ্ছি yearly key আছে
            $defaultCycleKey = 'yearly';

            // ২) যদি 'yearly' না থাকে, তাহলে যে plan এর 'months' সবচেয়ে বেশি, সেটাকে নেই
            if (!array_key_exists($defaultCycleKey, $priceOptions)) {
                $defaultCycleKey = collect($priceOptions)
                    ->sortByDesc('months')   // বেশি মাস আগে
                    ->keys()
                    ->first();               // প্রথমটাই নিলাম
            }

            $requestedCycle = $defaultCycleKey;
        }

        // এখন selected সব জিনিস yearly (বা ফfallback) ধরে নিবে
        $selectedPrice = $priceOptions[$requestedCycle];

        return view('front.checkout', [
            'product' => $product,
            'category' => $product->category,
            'location' => $location,
            'prices' => $prices,
            'priceOptions' => $priceOptions,
            'selectedCycle' => $requestedCycle,
            'selectedPrice' => $selectedPrice,
        ]);
    }



    public function initPaymentDetails(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'billing_cycle_id' => ['required', 'exists:billing_cycles,id'],
            'plan_title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $paymentDetail = PaymentDetail::create([
            'user_id' => auth()->id(),
            'product_id' => $data['product_id'],
            'location_id' => $data['location_id'],
            'billing_cycle_id' => $data['billing_cycle_id'],
            'plan_title' => $data['plan_title'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => 'draft',
        ]);

        return redirect()->route('checkout.paymentDetails.show', $paymentDetail);
    }

    public function showPaymentDetails(PaymentDetail $paymentDetail)
    {
        if (!auth()->check()) {
            session(['url.intended' => route('checkout.paymentDetails.show', $paymentDetail)]);
            return redirect()->route('login');
        }

        if ($paymentDetail->user_id && auth()->id() !== $paymentDetail->user_id) {
            abort(403);
        }

        $paymentDetail->loadMissing([
            'product:id,name',
            'location:id,name,currency_code',
            'billingCycle:id,name,key,months',
        ]);

        $user = auth()->user();
        $prefillFirst = $paymentDetail->first_name;
        $prefillLast = $paymentDetail->last_name;
        $prefillEmail = $paymentDetail->email;

        if ($user) {
            $prefillEmail = $prefillEmail ?: $user->email;
            if (!$prefillFirst) {
                $nameParts = preg_split('/\s+/', (string) $user->name, 2);
                $prefillFirst = $nameParts[0] ?? null;
                $prefillLast = $prefillLast ?: ($nameParts[1] ?? null);
            }
        }

        return view('front.paymentDetails', compact('paymentDetail', 'prefillFirst', 'prefillLast', 'prefillEmail'));
    }

    public function submitPaymentDetails(Request $request, PaymentDetail $paymentDetail)
    {
        if (!auth()->check()) {
            session(['url.intended' => route('checkout.paymentDetails.show', $paymentDetail)]);
            return redirect()->route('login');
        }

        if ($paymentDetail->user_id && auth()->id() !== $paymentDetail->user_id) {
            abort(403);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'phone_country_code' => ['required', 'string', 'max:10'],
            'phone_full' => ['required', 'string', 'max:50'],
            'street_address' => ['required', 'string', 'max:255'],
            'street_address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'domain_name' => ['nullable', 'string', 'max:255'],
            'tld' => ['nullable', 'string', 'max:20'],
        ]);

        $paymentDetail->fill($data);
        if (!$paymentDetail->user_id && auth()->check()) {
            $paymentDetail->user_id = auth()->id();
        }
        $paymentDetail->status = 'ready';
        $paymentDetail->save();

        // Create a PENDING payment record (no SSLCommerz redirect)
        $product = \App\Models\Product::with('category')->find($paymentDetail->product_id);
        $location = \App\Models\Location::find($paymentDetail->location_id);
        $cycle = \App\Models\BillingCycle::find($paymentDetail->billing_cycle_id);

        $tranId = 'BARABD_' . uniqid();

        $payment = Payment::create([
            'tran_id' => $tranId,
            'user_id' => auth()->id(),
            'category_id' => $product->category_id ?? null,
            'product_id' => $paymentDetail->product_id,
            'location_id' => $paymentDetail->location_id,
            'billing_cycle_id' => $paymentDetail->billing_cycle_id,
            'product_name' => $paymentDetail->plan_title ?: ($product->name ?? 'Product'),
            'amount' => $paymentDetail->amount,
            'currency' => $paymentDetail->currency,
            'status' => 'PENDING',
            'config' => [],
            'line_items' => [],
            'meta' => [
                // Payment Detail Reference
                'payment_detail_id' => $paymentDetail->id,
                // Customer Information
                'first_name' => $paymentDetail->first_name,
                'last_name' => $paymentDetail->last_name,
                'email' => $paymentDetail->email,
                'phone' => $paymentDetail->phone_full ?? $paymentDetail->phone,
                'phone_country_code' => $paymentDetail->phone_country_code,
                // Billing Address
                'street_address' => $paymentDetail->street_address,
                'street_address_2' => $paymentDetail->street_address_2,
                'city' => $paymentDetail->city,
                'state' => $paymentDetail->state,
                'postal_code' => $paymentDetail->postal_code,
                // Domain
                'domain_name' => $paymentDetail->domain_name,
                'tld' => $paymentDetail->tld,
                'full_domain' => $paymentDetail->domain_name ? ($paymentDetail->domain_name . ($paymentDetail->tld ?? '')) : null,
                // Product & Location Info
                'category_name' => $product->category->name ?? null,
                'location_name' => $location->name ?? null,
                'billing_cycle_name' => $cycle->name ?? $cycle->key ?? null,
            ],
        ]);

        // Redirect to purchase page where user can submit payment proof
        return redirect()->route('purchase')->with([
            'success' => 'Your order has been placed! Please complete payment using one of the methods below.',
            'invoice' => [
                'order_id' => $payment->id,
                'tran_id' => $tranId,
                'date' => now()->format('d M, Y'),
                // IDs needed for SSLCommerz form
                'product_id' => $paymentDetail->product_id,
                'location_id' => $paymentDetail->location_id,
                'billing_cycle_id' => $paymentDetail->billing_cycle_id,
                'payment_detail_id' => $paymentDetail->id,
                // Customer Info
                'first_name' => $paymentDetail->first_name,
                'last_name' => $paymentDetail->last_name,
                'email' => $paymentDetail->email,
                'phone' => $paymentDetail->phone_full ?? $paymentDetail->phone,
                // Billing Address
                'street_address' => $paymentDetail->street_address,
                'street_address_2' => $paymentDetail->street_address_2,
                'city' => $paymentDetail->city,
                'state' => $paymentDetail->state,
                'postal_code' => $paymentDetail->postal_code,
                // Domain
                'domain' => $paymentDetail->domain_name ? ($paymentDetail->domain_name . ($paymentDetail->tld ?? '')) : null,
                // Product Info
                'product_name' => $paymentDetail->plan_title ?: ($product->name ?? 'Product'),
                'category' => $product->category->name ?? null,
                'location' => $location->name ?? null,
                'billing_cycle' => $cycle->name ?? $cycle->key ?? null,
                // Amount
                'amount' => $paymentDetail->amount,
                'currency' => $paymentDetail->currency,
            ],
        ]);
    }

    public function customize()
    {
        // Active categories with products and customize-only features + pricing
        $categories = \App\Models\Category::active()
            ->with([
                'products' => function ($q) {
                    $q->where('is_active', true)
                        ->orderBy('id');
                },
                'customizeFeatures' => function ($fq) {
                    $fq->orderBy('id')
                        ->with([
                            'prices' => function ($pq) {
                                $pq->with([
                                    'location:id,name,currency_code',
                                    'billingCycle:id,key,months',
                                ]);
                            }
                        ]);
                }
            ])
            ->orderBy('id')
            ->get();

        $categoriesPayload = $categories->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'products' => $cat->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                    ];
                })->values(),
                'customizeFeatures' => $cat->customizeFeatures->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'key' => $feature->key,
                        'label' => $feature->label,
                        'input_type' => $feature->input_type,
                        'unit' => $feature->unit,
                        'min' => $feature->min,
                        'max' => $feature->max,
                        'step' => $feature->step,
                        'options_json' => $feature->options_json,
                        'is_required' => (bool) $feature->is_required,
                        'prices' => $feature->prices->map(function ($price) {
                            return [
                                'location_id' => $price->location_id,
                                'billing_cycle_id' => $price->billing_cycle_id,
                                'included_value' => $price->included_value,
                                'step' => $price->step,
                                'price_per_step' => $price->price_per_step,
                                'location' => $price->location ? [
                                    'id' => $price->location->id,
                                    'name' => $price->location->name,
                                    'currency_code' => $price->location->currency_code,
                                ] : null,
                                'billingCycle' => $price->billingCycle ? [
                                    'id' => $price->billingCycle->id,
                                    'key' => $price->billingCycle->key,
                                    'months' => $price->billingCycle->months,
                                ] : null,
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->values();

        $addOns = \App\Models\AddOn::where('is_active', true)
            ->with([
                'prices.location:id,name,currency_code',
                'prices.billingCycle:id,key,months'
            ])
            ->orderBy('id')
            ->get();

        $locations = \App\Models\Location::where('is_active', true)->get();
        $billingCycles = \App\Models\BillingCycle::all();
        $defaultLocationId = session('auto_location_id') ?: ($locations->first()?->id);

        return view('front.customize', compact('categories', 'categoriesPayload', 'addOns', 'locations', 'billingCycles', 'defaultLocationId'));
    }

    public function purchase()
    {
        return view('front.purchase');
    }

    public function profile()
    {
        return view('front.profile');
    }

    public function about()
    {
        return view('front.about');
    }

    public function services()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->with([
                'products' => function ($q) {
                    $q->where('is_active', true)
                        ->orderBy('id')
                        ->with([
                            'features' => function ($fq) {
                                $fq->orderBy('id');
                            },
                            'basePrices' => function ($bp) {
                                $bp->whereHas('location', function ($loc) {
                                    $loc->where('is_active', true);
                                })
                                    ->with([
                                        'location:id,name,currency_code',
                                        'billingCycle:id,key,months',
                                    ]);
                            },
                        ]);
                },
            ])
            ->get();

        $locations = $categories->flatMap(function ($cat) {
            return $cat->products;
        })
            ->flatMap(function ($product) {
                return $product->basePrices->pluck('location');
            })
            ->filter()
            ->unique('id')
            ->values();

        $defaultLocationId = $locations->first()->id ?? null;

        return view('front.services', compact('categories', 'locations', 'defaultLocationId'));
    }

    public function login()
    {
        return view('auth', ['mode' => 'login']);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $newUsersLast30 = User::where('created_at', '>=', now()->subDays(30))->count();
        $totalPayments = Payment::count();
        $successfulPayments = Payment::where('status', 'SUCCESS')->count();
        $pendingPayments = Payment::where('status', 'PENDING')->count();

        $totalRevenue = Payment::where('status', 'SUCCESS')
            ->where('currency', '!=', 'BDT')
            ->sum('amount');
        $bdtRevenue = Payment::where('status', 'SUCCESS')->where('currency', 'BDT')->sum('amount');
        $usdRevenue = Payment::where('status', 'SUCCESS')
            ->where('currency', '!=', 'BDT')
            ->sum('amount');
        $todayRevenue = Payment::where('status', 'SUCCESS')
            ->whereDate(DB::raw('COALESCE(paid_at, created_at)'), Carbon::today())
            ->sum('amount');
        $avgTicket = Payment::where('status', 'SUCCESS')->avg('amount') ?: 0;

        $revenueStart = Carbon::now()->subMonths(11)->startOfMonth();
        $rawMonthly = Payment::where('status', 'SUCCESS')
            ->whereRaw('COALESCE(paid_at, created_at) >= ?', [$revenueStart])
            ->selectRaw('DATE_FORMAT(COALESCE(paid_at, created_at), "%Y-%m") as month_key')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->pluck('total', 'month_key');

        $monthlyRevenue = [];
        $monthlyLabels = [];
        $cursor = $revenueStart->copy();
        for ($i = 0; $i < 12; $i++) {
            $key = $cursor->format('Y-m');
            $monthlyLabels[] = $cursor->format('M Y');
            $monthlyRevenue[] = (float) ($rawMonthly[$key] ?? 0);
            $cursor->addMonth();
        }

        $statusBreakdown = Payment::select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(amount) as amount'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $recentPayments = Payment::with(['user', 'product'])
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('back.admin.dashboard', compact(
            'totalUsers',
            'newUsersLast30',
            'totalPayments',
            'successfulPayments',
            'pendingPayments',
            'totalRevenue',
            'bdtRevenue',
            'usdRevenue',
            'todayRevenue',
            'avgTicket',
            'monthlyRevenue',
            'monthlyLabels',
            'statusBreakdown',
            'recentPayments'
        ));
    }
}
