<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\TaxRuleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BillingCycleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductFeatureController;
use App\Http\Controllers\CustomizeFeatureController;
use App\Http\Controllers\AddOnController;
use App\Http\Controllers\BasePriceController;
use App\Http\Controllers\FeaturePriceController;
use App\Http\Controllers\AddOnPriceController;
use App\Http\Controllers\PresetController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\Admin\DomainTldController;
use App\Http\Controllers\Admin\DomainOrderController;
use App\Http\Controllers\Api\WhmcsWebhookController;
use App\Models\Category;
use App\Models\Announcement;

Route::post('/pricing/quote', [QuoteController::class, 'quote'])->name('pricing.quote');

/* WHMCS Webhook (no CSRF – called by WHMCS server) */
Route::post('/api/whmcs/webhook', [WhmcsWebhookController::class, 'handle'])
    ->name('whmcs.webhook');

/* Frontend (public) */
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('/checkout/payment-details', [IndexController::class, 'initPaymentDetails'])->name('checkout.paymentDetails.store');
Route::get('/checkout/payment-details/{paymentDetail}', [IndexController::class, 'showPaymentDetails'])->name('checkout.paymentDetails.show');
Route::post('/checkout/payment-details/{paymentDetail}', [IndexController::class, 'submitPaymentDetails'])->name('checkout.paymentDetails.update');
Route::get('/checkout/{product}', [IndexController::class, 'checkout'])->name('checkout'); // <-- মনে রাখো: এখন প্যারাম দরকার
Route::get('/customize', [IndexController::class, 'customize'])->name('customize');
Route::get('/purchase', [IndexController::class, 'purchase'])->name('purchase');
Route::get('/profile', [IndexController::class, 'profile'])->name('profile');
Route::get('/about', [IndexController::class, 'about'])->name('about');
Route::get('/services', [IndexController::class, 'services'])->name('services');
Route::post('/coupons/claim', [CouponController::class, 'claim'])->middleware('auth')->name('coupons.claim');

/* Domain Registration */
Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
Route::post('/domains/search', [DomainController::class, 'search'])->name('domains.search');
Route::get('/domains/checkout', [DomainController::class, 'checkout'])->name('domains.checkout');
Route::post('/domains/purchase', [DomainController::class, 'purchase'])->name('domains.purchase');
Route::any('/domain-payment/success', [DomainController::class, 'paymentSuccess'])->name('domain.payment.success');
Route::any('/domain-payment/fail', [DomainController::class, 'paymentFail'])->name('domain.payment.fail');
Route::any('/domain-payment/cancel', [DomainController::class, 'paymentCancel'])->name('domain.payment.cancel');

/* Auth */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
// routes/web.php
Route::get('/forgot-password', [AuthController::class, 'forgotPage'])->name('forgot.page')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password')->middleware('guest');

/* Registration (guest only) */
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');

/* 🔐 Google OAuth — অবশ্যই admin group-এর বাইরে এবং guest এ */
Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});


/* User area (requires auth) */
Route::prefix('user')->middleware('auth', 'role:user')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $categories = Category::all();
        $announcements = Announcement::latest('published_at')
            ->latest('id')
            ->take(5)
            ->get();
        $payments = $user?->payments()
            ->with(['product.category', 'location', 'billingCycle'])
            ->latest('created_at')
            ->take(10)
            ->get() ?? collect();

        return view('customer.dashboard', compact('payments', 'categories', 'announcements'));
    })->name('user.dashboard');

    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])
        ->name('user.payments.invoice');

    Route::get('/profile', function () {
        $user = auth()->user();
        $user?->loadMissing(['account.country', 'account.primaryContact']);
        $categories = Category::all();

        return view('customer.profile', compact('user', 'categories'));
    })->name('user.profile');

    Route::post('/profile', [CustomerProfileController::class, 'update'])
        ->name('user.profile.update');

    Route::post('/change-password', [AuthController::class, 'changePassword'])
        ->name('user.password.update');
});

// SSLCommerz payment
Route::post('/ssl-pay', [PaymentController::class, 'initiate'])->name('ssl.pay');
Route::post('/ssl-pay-ajax', [PaymentController::class, 'initiateAjax'])->name('ssl.pay.ajax');
Route::any('/ssl-success', [PaymentController::class, 'success'])->name('ssl.success');
Route::any('/ssl-fail', [PaymentController::class, 'fail'])->name('ssl.fail');
Route::any('/ssl-cancel', [PaymentController::class, 'cancel'])->name('ssl.cancel');


/* Admin area (requires auth + role superadmin/admin) */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin,admin'])->group(function () {

    // Users (only superadmin can manage users)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/', [IndexController::class, 'dashboard'])->name('dashboard');

    // Countries
    Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
    Route::get('countries/create', [CountryController::class, 'create'])->name('countries.create');
    Route::post('countries', [CountryController::class, 'store'])->name('countries.store');
    Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
    Route::put('countries/{country}', [CountryController::class, 'update'])->name('countries.update');
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');

    // Coupons
    Route::resource('coupons', AdminCouponController::class)->except(['show']);

    // Offers (front special offer URL)
    Route::get('offers', [OfferController::class, 'index'])->name('offers.index');
    Route::post('offers', [OfferController::class, 'store'])->name('offers.store');
    Route::put('offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    Route::get('offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::delete('offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');

    // Currencies
    Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
    Route::post('currencies', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::get('currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::put('currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
    Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');

    // Exchange Rates
    Route::get('exchange-rates', [ExchangeRateController::class, 'index'])->name('exchange-rates.index');
    Route::get('exchange-rates/create', [ExchangeRateController::class, 'create'])->name('exchange-rates.create');
    Route::post('exchange-rates', [ExchangeRateController::class, 'store'])->name('exchange-rates.store');
    Route::get('exchange-rates/{exchangeRate}/edit', [ExchangeRateController::class, 'edit'])->name('exchange-rates.edit');
    Route::put('exchange-rates/{exchangeRate}', [ExchangeRateController::class, 'update'])->name('exchange-rates.update');
    Route::delete('exchange-rates/{exchangeRate}', [ExchangeRateController::class, 'destroy'])->name('exchange-rates.destroy');

    // Tax Rules
    Route::get('tax-rules', [TaxRuleController::class, 'index'])->name('tax-rules.index');
    Route::get('tax-rules/create', [TaxRuleController::class, 'create'])->name('tax-rules.create');
    Route::post('tax-rules', [TaxRuleController::class, 'store'])->name('tax-rules.store');
    Route::get('tax-rules/{taxRule}/edit', [TaxRuleController::class, 'edit'])->name('tax-rules.edit');
    Route::put('tax-rules/{taxRule}', [TaxRuleController::class, 'update'])->name('tax-rules.update');
    Route::delete('tax-rules/{taxRule}', [TaxRuleController::class, 'destroy'])->name('tax-rules.destroy');

    // Accounts
    Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('accounts/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // Contacts
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    /* -------- Locations -------- */
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

    /* -------- Billing Cycles -------- */
    Route::get('/billing-cycles', [BillingCycleController::class, 'index'])->name('billing-cycles.index');
    Route::get('/billing-cycles/create', [BillingCycleController::class, 'create'])->name('billing-cycles.create');
    Route::post('/billing-cycles', [BillingCycleController::class, 'store'])->name('billing-cycles.store');
    Route::get('/billing-cycles/{billing_cycle}/edit', [BillingCycleController::class, 'edit'])->name('billing-cycles.edit');      // <-- fixed
    Route::put('/billing-cycles/{billing_cycle}', [BillingCycleController::class, 'update'])->name('billing-cycles.update');  // <-- fixed
    Route::delete('/billing-cycles/{billing_cycle}', [BillingCycleController::class, 'destroy'])->name('billing-cycles.destroy');// <-- fixed

    /* -------- Categories -------- */
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    /* -------- Products -------- */
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    /* -------- Product-Feature -------- */
    Route::get('/product-features', [ProductFeatureController::class, 'index'])->name('product-features.index');
    Route::get('/product-features/create', [ProductFeatureController::class, 'create'])->name('product-features.create');
    Route::post('/product-features', [ProductFeatureController::class, 'store'])->name('product-features.store');
    Route::get('/product-features/{product_feature}/edit', [ProductFeatureController::class, 'edit'])->name('product-features.edit');
    Route::put('/product-features/{product_feature}', [ProductFeatureController::class, 'update'])->name('product-features.update');
    Route::delete('/product-features/{product_feature}', [ProductFeatureController::class, 'destroy'])->name('product-features.destroy');

    /* -------- Customize Features (used in custom builder) -------- */
    Route::get('/customize-features', [CustomizeFeatureController::class, 'index'])->name('customize-features.index');
    Route::get('/customize-features/create', [CustomizeFeatureController::class, 'create'])->name('customize-features.create');
    Route::post('/customize-features', [CustomizeFeatureController::class, 'store'])->name('customize-features.store');
    Route::get('/customize-features/{customize_feature}/edit', [CustomizeFeatureController::class, 'edit'])->name('customize-features.edit');
    Route::put('/customize-features/{customize_feature}', [CustomizeFeatureController::class, 'update'])->name('customize-features.update');
    Route::delete('/customize-features/{customize_feature}', [CustomizeFeatureController::class, 'destroy'])->name('customize-features.destroy');

    /* -------- Add-ons -------- */
    Route::get('/add-ons', [AddOnController::class, 'index'])->name('add-ons.index');
    Route::get('/add-ons/create', [AddOnController::class, 'create'])->name('add-ons.create');
    Route::post('/add-ons', [AddOnController::class, 'store'])->name('add-ons.store');
    Route::get('/add-ons/{add_on}/edit', [AddOnController::class, 'edit'])->name('add-ons.edit');
    Route::put('/add-ons/{add_on}', [AddOnController::class, 'update'])->name('add-ons.update');
    Route::delete('/add-ons/{add_on}', [AddOnController::class, 'destroy'])->name('add-ons.destroy');

    /* -------- Base Prices -------- */
    Route::get('/base-prices', [BasePriceController::class, 'index'])->name('base-prices.index');
    Route::get('/base-prices/create', [BasePriceController::class, 'create'])->name('base-prices.create');
    Route::post('/base-prices', [BasePriceController::class, 'store'])->name('base-prices.store');
    Route::get('/base-prices/{base_price}/edit', [BasePriceController::class, 'edit'])->name('base-prices.edit');
    Route::put('/base-prices/{base_price}', [BasePriceController::class, 'update'])->name('base-prices.update');
    Route::delete('/base-prices/{base_price}', [BasePriceController::class, 'destroy'])->name('base-prices.destroy');

    /* -------- Feature Prices -------- */
    Route::get('/feature-prices', [FeaturePriceController::class, 'index'])->name('feature-prices.index');
    Route::get('/feature-prices/create', [FeaturePriceController::class, 'create'])->name('feature-prices.create');
    Route::post('/feature-prices', [FeaturePriceController::class, 'store'])->name('feature-prices.store');
    Route::get('/feature-prices/{feature_price}/edit', [FeaturePriceController::class, 'edit'])->name('feature-prices.edit');
    Route::put('/feature-prices/{feature_price}', [FeaturePriceController::class, 'update'])->name('feature-prices.update');
    Route::delete('/feature-prices/{feature_price}', [FeaturePriceController::class, 'destroy'])->name('feature-prices.destroy');

    /* -------- Add-on Prices -------- */
    Route::get('/add-on-prices', [AddOnPriceController::class, 'index'])->name('add-on-prices.index');
    Route::get('/add-on-prices/create', [AddOnPriceController::class, 'create'])->name('add-on-prices.create');
    Route::post('/add-on-prices', [AddOnPriceController::class, 'store'])->name('add-on-prices.store');
    Route::get('/add-on-prices/{add_on_price}/edit', [AddOnPriceController::class, 'edit'])->name('add-on-prices.edit');
    Route::put('/add-on-prices/{add_on_price}', [AddOnPriceController::class, 'update'])->name('add-on-prices.update');
    Route::delete('/add-on-prices/{add_on_price}', [AddOnPriceController::class, 'destroy'])->name('add-on-prices.destroy');

    /* -------- Presets -------- */
    Route::get('/presets', [PresetController::class, 'index'])->name('presets.index');
    Route::get('/presets/create', [PresetController::class, 'create'])->name('presets.create');
    Route::post('/presets', [PresetController::class, 'store'])->name('presets.store');
    Route::get('/presets/{preset}/edit', [PresetController::class, 'edit'])->name('presets.edit');
    Route::put('/presets/{preset}', [PresetController::class, 'update'])->name('presets.update');
    Route::delete('/presets/{preset}', [PresetController::class, 'destroy'])->name('presets.destroy');

    /* -------- Payments -------- */
    Route::get('/payments/{payment}/pdf', [AdminPaymentController::class, 'pdf'])->name('payments.pdf');
    Route::resource('/payments', AdminPaymentController::class);
    Route::resource('/announcements', AnnouncementController::class)
        ->names('announcements')
        ->except(['show']);

    /* -------- Domain TLDs -------- */
    Route::resource('domain-tlds', DomainTldController::class)->except(['show']);

    /* -------- Domain Orders -------- */
    Route::get('domain-orders', [DomainOrderController::class, 'index'])->name('domain-orders.index');
    Route::get('domain-orders/{domain_order}', [DomainOrderController::class, 'show'])->name('domain-orders.show');
    Route::delete('domain-orders/{domain_order}', [DomainOrderController::class, 'destroy'])->name('domain-orders.destroy');

    Route::post('/notifications/payments/seen', function () {
        $user = auth()->user();
        if ($user) {
            cache()->forever('payments_seen_ids_' . $user->id, []);
        }
        return response()->json(['ok' => true]);
    })->name('notifications.payments.seen');

    Route::post('/notifications/payments/seen/{payment}', function (\App\Models\Payment $payment) {
        $user = auth()->user();
        if ($user) {
            $seen = collect(cache()->get('payments_seen_ids_' . $user->id, []))
                ->filter(fn($id) => is_numeric($id))
                ->map(fn($id) => (int) $id)
                ->push((int) $payment->id)
                ->unique()
                ->slice(-200) // keep it small
                ->values()
                ->all();

            cache()->forever('payments_seen_ids_' . $user->id, $seen);
        }

        return response()->json(['ok' => true]);
    })->name('notifications.payments.seen.item');

});
