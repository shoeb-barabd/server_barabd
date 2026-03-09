<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingCycle;
use App\Models\Category;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;


class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->input('q', ''));
        $status = $request->input('status', '');

        $payments = Payment::with(['user', 'product', 'location', 'billingCycle'])
            ->when($q !== '', function ($query) use ($q) {
                $like = "%{$q}%";
                $query->where(function ($inner) use ($like) {
                    $inner->where('tran_id', 'like', $like)
                          ->orWhere('product_name', 'like', $like)
                          ->orWhereHas('product', fn ($p) => $p->where('name', 'like', $like))
                          ->orWhereHas('user', function ($u) use ($like) {
                              $u->where('first_name', 'like', $like)
                                ->orWhere('last_name', 'like', $like)
                                ->orWhere('email', 'like', $like);
                          });
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $statuses = $this->statusOptions();

        return view('back.payment.index', compact('payments', 'q', 'status', 'statuses'));
    }

    public function create()
    {
        $payment = new Payment([
            'currency' => 'BDT',
            'status'   => 'INIT',
        ]);

        $lookups = $this->lookups();
        $statuses = $this->statusOptions();

        return view('back.payment.create', compact('payment', 'lookups', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        Payment::create($data);

        return redirect()->route('admin.payments.index')->with('success', 'Payment created successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'product', 'location', 'billingCycle', 'category']);
        return view('back.payment.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $lookups = $this->lookups();
        $statuses = $this->statusOptions();

        return view('back.payment.edit', compact('payment', 'lookups', 'statuses'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $this->validatedData($request, $payment);
        $payment->update($data);

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Payment deleted successfully!');
    }

    public function pdf(Payment $payment)
    {
        $payment->load(['user', 'product', 'location', 'billingCycle', 'category']);
        $pdf = Pdf::loadView('back.payment.pdf', compact('payment'));
        $fileName = 'payment-' . ($payment->tran_id ?: $payment->id) . '.pdf';

        return $pdf->download($fileName);
    }

    private function validatedData(Request $request, ?Payment $payment = null): array
    {
        $input = $request->all();
        foreach (['config', 'line_items', 'meta'] as $jsonField) {
            if (isset($input[$jsonField]) && trim((string) $input[$jsonField]) === '') {
                $input[$jsonField] = null;
            }
        }

        $validated = Validator::make($input, [
            'tran_id'          => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('payments', 'tran_id')->ignore($payment?->id),
            ],
            'amount'           => ['required', 'numeric', 'min:0'],
            'currency'         => ['required', 'string', 'max:3'],
            'status'           => ['required', 'string', Rule::in(array_keys($this->statusOptions()))],
            'val_id'           => ['nullable', 'string', 'max:255'],
            'gateway_response' => ['nullable', 'string'],
            'user_id'          => ['nullable', 'exists:users,id'],
            'category_id'      => ['nullable', 'exists:categories,id'],
            'product_id'       => ['nullable', 'exists:products,id'],
            'location_id'      => ['nullable', 'exists:locations,id'],
            'billing_cycle_id' => ['nullable', 'exists:billing_cycles,id'],
            'product_name'     => ['nullable', 'string', 'max:255'],
            'config'           => ['nullable', 'json'],
            'line_items'       => ['nullable', 'json'],
            'meta'             => ['nullable', 'json'],
            'paid_at'          => ['nullable', 'date'],
        ])->validate();

        $validated['tran_id'] = $validated['tran_id'] ?: 'BARABD_' . uniqid();
        $validated['config'] = $this->decodeJson($validated['config'] ?? null);
        $validated['line_items'] = $this->decodeJson($validated['line_items'] ?? null);
        $validated['meta'] = $this->decodeJson($validated['meta'] ?? null);

        return $validated;
    }

    private function decodeJson(?string $value): ?array
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function lookups(): array
    {
        return [
            'users'          => User::orderBy('first_name')->orderBy('last_name')->get(),
            'categories'     => Category::orderBy('name')->get(),
            'products'       => Product::orderBy('name')->get(),
            'locations'      => Location::orderBy('name')->get(),
            'billingCycles' => BillingCycle::orderBy('name')->get(),
        ];
    }

    private function statusOptions(): array
    {
        return [
            'INIT'      => 'Init',
            'PENDING'   => 'Pending',
            'SUCCESS'   => 'Success',
            'FAILED'    => 'Failed',
            'CANCELLED' => 'Cancelled',
        ];
    }
}
