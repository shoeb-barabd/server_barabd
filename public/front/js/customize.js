
(function () {
  const $  = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
  const data = window.CUSTOMIZE || {};

  const locSel      = $('#locationSelect');
  const billMonthly = $('#billingMonthly');
  const billAnnual  = $('#billingAnnual');
  const sumLines    = $('#summary-lines');
  const sumTotal    = $('#summary-total');
  const sumSubtotal = $('#summary-subtotal');
  const sumDiscountRow = $('#summary-discount-row');
  const sumDiscountLabel = $('#summary-discount-label');
  const sumDiscountValue = $('#summary-discount-value');
  const sumLoc      = $('#summary-location');
  const builderTitle = $('#builderTitle');
  const cartPlanTitle = $('#cartPlanTitle');
  const summaryPlanTitle = $('#summaryPlanTitle');
  const summaryCycleTag = $('#summaryCycleTag');
  const chipCategory = $('#chipCategory');
  const chipLocation = $('#chipLocation');
  const chipCycle = $('#chipCycle');
  const couponInput = $('#couponCodeInput');
  const couponBtn   = $('#couponApplyBtn');
  const couponMsg   = $('#couponMessage');
  const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  let initialized  = false;
  let appliedCoupon = null;

  const fm      = n => Number(n || 0).toFixed(2);
  const symbol  = code => (data.symbols?.[code] || code || '');
  const money   = (sym, n) => `${sym}${fm(n)}`;
  const currentLocId    = () => parseInt(locSel?.value || data.defaultLocationId || 0);
  const currentCycleKey = () => (billAnnual && billAnnual.checked) ? 'annual' : 'monthly';
  const toAnnual        = amount => amount * 12;
  const VPS_SLUG_HINTS  = ['vps', 'vps-host'];
  const CORE_KEYS       = ['cpu', 'cpu_core', 'cpu-core', 'cpu_cores', 'cpu-cores', 'cores'];
  const THREAD_KEYS     = ['cpu_threads', 'cpu-thread', 'cpu-threads', 'cpu_thread', 'threads', 'thread'];
  const syncLocks       = { cpu: false };

  function isVpsActive(group = activeGroup()) {
    if (!group) return false;
    const slug = (group.dataset.group || '').toLowerCase();
    const name = (group.dataset.categoryName || '').toLowerCase();
    return VPS_SLUG_HINTS.some(h => slug.includes(h) || name.includes(h));
  }

  function matchesBox(box, keys, labelHints) {
    const key   = (box?.dataset.key || '').toLowerCase();
    const label = (box?.dataset.label || box?.previousElementSibling?.textContent || '').toLowerCase();
    return keys.includes(key) || labelHints.some(h => label.includes(h));
  }

  function findBox(group, keys, labelHints) {
    return $$('.qty-box', group).find(b => matchesBox(b, keys, labelHints));
  }

  function readBoxValue(box) {
    if (!box) return 0;
    const slider = box.querySelector('input[type="range"]');
    const badge  = box.previousElementSibling?.querySelector('.qty-val');
    const fallback = parseFloat(box.getAttribute('data-min') || 0);
    return parseFloat(slider?.value ?? badge?.textContent ?? fallback) || fallback;
  }

  function syncCpuThreads(coreBox, coreVal) {
    if (syncLocks.cpu) return;
    const group = activeGroup();
    if (!group || !isVpsActive(group)) return;

    const threadsBox = findBox(group, THREAD_KEYS, ['thread']);
    if (!threadsBox) return;

    const slider  = threadsBox.querySelector('input[type="range"]');
    const badge   = threadsBox.previousElementSibling?.querySelector('.qty-val');
    const min     = parseFloat(threadsBox.getAttribute('data-min') || slider?.min || 0);
    const max     = parseFloat(threadsBox.getAttribute('data-max') || slider?.max || coreVal * 2 || 0);
    const target  = Math.min(max, Math.max(min, (Number(coreVal) || 0) * 2));

    syncLocks.cpu = true;
    if (typeof threadsBox._applyValue === 'function') {
      threadsBox._applyValue(target, { skipSync: true, skipRecalc: true });
    } else {
      if (slider) slider.value = String(target);
      if (badge) badge.textContent = String(target);
    }
    syncLocks.cpu = false;
  }

  function getPriceRow(prices, locId, cycleKey) {
    if (!Array.isArray(prices)) return null;
    return prices.find(p =>
      String(p.location_id || p.location?.id) === String(locId) &&
      (p.billing_cycle?.key || p.billingCycle?.key) === cycleKey
    ) || null;
  }

  function activeGroup() {
    return $('.group-card.active');
  }

  function switchCategory(slug, titleText, displayName) {
    $$('.custom-cat-pill').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.group === slug);
    });
    $$('.group-card').forEach(card => {
      const on = card.dataset.group === slug;
      card.classList.toggle('active', on);
      card.classList.toggle('d-none', !on);
    });
    if (titleText && builderTitle) builderTitle.textContent = titleText;
    if (cartPlanTitle) cartPlanTitle.textContent = displayName || titleText || 'Custom Plan';
    if (summaryPlanTitle) summaryPlanTitle.textContent = displayName || 'Custom Plan';
    if (chipCategory) chipCategory.textContent = displayName || 'Category';

    initOrRefreshFeaturePricing();
    refreshAddOnPrices();
    recalcSummary();
  }

  function bindCategoryTabs() {
    $$('.custom-cat-pill').forEach(btn => {
      if (btn._bound) return;
      btn._bound = true;
      btn.addEventListener('click', () => {
        switchCategory(btn.dataset.group, btn.dataset.title, btn.dataset.name);
      });
    });

    const first = $('.custom-cat-pill');
    if (first) switchCategory(first.dataset.group, first.dataset.title, first.dataset.name);
  }

  function initOrRefreshFeaturePricing() {
    const locId = currentLocId();
    const group = activeGroup();
    if (!group) return;
    const groupSlug = group.getAttribute('data-group');

    function findFeatureDefByKey(key) {
      for (const cat of (data.categories || [])) {
        if (cat.slug !== groupSlug) continue;
        const featureList = cat.customizeFeatures || cat.customize_features || [];
        const f = featureList.find(x => x.key === key);
        if (f) return f;
      }
      return null;
    }

    $$('.qty-box', group).forEach(box => {
      const fKey = box.getAttribute('data-key');
      const fDef = findFeatureDefByKey(fKey);
      if (!fDef) return;

      const rMonthly = getPriceRow(fDef.prices || [], locId, 'monthly');
      const included = parseFloat(rMonthly?.included_value || 0);
      const pricePerStepMonthly = parseFloat(rMonthly?.price_per_step || 0);
      const step = parseFloat(box.getAttribute('data-step') || 1);

      box.dataset.included         = String(included);
      box.dataset.priceStepMonthly = String(pricePerStepMonthly);
      box.dataset.step             = String(step);

      if (!box._bound) {
        box._bound = true;
        const decBtn  = $('[data-act="dec"]', box);
        const incBtn  = $('[data-act="inc"]', box);
        const valBadge = box.previousElementSibling?.querySelector('.qty-val');
        const slider  = $('input[type="range"]', box);
        const minOriginal = parseFloat(box.getAttribute('data-min') || 0);
        const max     = parseFloat(box.getAttribute('data-max') || 0);
        const readValue = () => parseFloat(slider?.value || valBadge?.textContent || minOriginal);

        // Allow showing 0 by default, but enforce original minimum after first interaction.
        if (slider && Number(slider.min || 0) > 0) slider.min = '0';

        const applyValue = (val, opts = {}) => {
          const wantsZero = Number.isFinite(val) && val === 0;
          let next = Number.isFinite(val) ? val : minOriginal;
          if (wantsZero) {
            next = 0;
          } else {
            next = Math.min(max, Math.max(minOriginal, next));
          }
          if (slider) slider.value = String(next);
          if (valBadge) valBadge.textContent = String(next);

          if (!opts.skipSync && matchesBox(box, CORE_KEYS, ['cpu core', 'core'])) {
            syncCpuThreads(box, next);
          }
          if (!opts.skipRecalc) recalcSummary();
        };

        const apply = delta => {
          applyValue(readValue() + delta);
        };

        box._applyValue = applyValue;
        applyValue(0, { skipSync: true, skipRecalc: true });

        slider?.addEventListener('input', () => {
          const raw = parseFloat(slider.value || minOriginal);
          applyValue(raw);
        });

        decBtn?.addEventListener('click', () => apply(-step));
        incBtn?.addEventListener('click', () => apply(+step));
      }
    });

    if (isVpsActive(group)) {
      const coreBox    = findBox(group, CORE_KEYS, ['cpu core', 'core']);
      const threadsBox = findBox(group, THREAD_KEYS, ['thread']);
      if (coreBox && threadsBox && !coreBox._vpsPaired) {
        coreBox._vpsPaired = true;
        const enforce = () => syncCpuThreads(coreBox, readBoxValue(coreBox));
        // Run once on load to align threads with current core value.
        enforce();
        const coreSlider = coreBox.querySelector('input[type="range"]');
        coreSlider?.addEventListener('input', enforce);
        // Also guard increment/decrement buttons.
        $('[data-act="inc"]', coreBox)?.addEventListener('click', () => setTimeout(enforce, 0));
        $('[data-act="dec"]', coreBox)?.addEventListener('click', () => setTimeout(enforce, 0));
      }
    }

    $$('.feature-check', group).forEach(chk => {
      const fKey = chk.getAttribute('data-feature');
      const fDef = findFeatureDefByKey(fKey);
      if (!fDef) return;

      const rMonthly = getPriceRow(fDef.prices || [], locId, 'monthly');
      const priceMonthly = parseFloat(rMonthly?.price_per_step || 0);
      chk.dataset.priceMonthly = String(priceMonthly);

      if (!chk._bound) {
        chk._bound = true;
        chk.addEventListener('change', recalcSummary);
      }
    });

    $$('.feature-select', group).forEach(sel => {
      if (!sel._bound) {
        sel._bound = true;
        sel.addEventListener('change', recalcSummary);
      }
    });
  }

  function refreshAddOnPrices() {
    const locId = currentLocId();
    const cycle = currentCycleKey();
    const group = activeGroup();
    if (!group) return;

    $$('.addon-pill', group).forEach(label => {
      const input = label.querySelector('input[type="checkbox"]');
      const key   = input?.dataset.key;
      if (!key) return;

      const addon = (data.addOns || []).find(a => a.key === key);
      if (!addon) return;

      const rowMonthly = getPriceRow(addon.prices || [], locId, 'monthly');
      const rowAnnual  = getPriceRow(addon.prices || [], locId, 'annual');

      const curr = (rowMonthly?.location?.currency_code) ||
                   (rowAnnual?.location?.currency_code) ||
                   (data.locations || []).find(l => String(l.id) === String(locId))?.currency_code ||
                   'USD';
      const sym  = symbol(curr);

      let unit = 0;
      if (cycle === 'monthly') {
        unit = parseFloat(rowMonthly?.unit_price || 0);
      } else {
        unit = rowAnnual
          ? parseFloat(rowAnnual.unit_price || 0)
          : toAnnual(parseFloat(rowMonthly?.unit_price || 0));
      }

      const priceSpan = label.querySelector('[data-price-text]');
      if (priceSpan) priceSpan.textContent = unit ? `${sym}${fm(unit)}/${cycle === 'monthly' ? 'mo' : 'yr'}` : 'Free';

      input.dataset.unit     = String(unit);
      input.dataset.currency = curr;

      if (!input._bound) {
        input._bound = true;
        input.addEventListener('change', recalcSummary);
      }
    });
  }

  function featureSubtotal(sym) {
    const group = activeGroup();
    if (!group) return { items: [], total: 0 };

    const items = [];
    const cycle = currentCycleKey();

    $$('.qty-box', group).forEach(box => {
      const label    = box.dataset.label;
      const unit     = box.getAttribute('data-unit') || '';
      const min      = parseFloat(box.dataset.min || 0);
      const slider   = $('input[type="range"]', box);
      const badgeVal = box.previousElementSibling?.querySelector('.qty-val');
      const currentValSource = slider?.value ?? badgeVal?.textContent ?? '';
      const val      = parseFloat(currentValSource) || min;
      const included = parseFloat(box.dataset.included || 0);
      const step     = parseFloat(box.dataset.step || 1);
      const priceMon = parseFloat(box.dataset.priceStepMonthly || 0);

      const added = Math.max(0, val - included);
      const steps = Math.ceil(added / (step || 1));
      let cost    = steps * priceMon;
      if (cycle === 'annual') cost = toAnnual(cost);

      if (cost > 0 || val !== included) {
        items.push({
          label: `${label} – ${val}${unit} (${included}${unit} incl.)`,
          cost
        });
      }
    });

    $$('.feature-check:checked', group).forEach(chk => {
      const label = chk.dataset.label;
      let cost = parseFloat(chk.dataset.priceMonthly || 0);
      if (currentCycleKey() === 'annual') cost = toAnnual(cost);
      if (cost > 0) items.push({ label, cost });
    });

    $$('.feature-select', group).forEach(sel => {
      const label = sel.dataset.label;
      const opt   = sel.options[sel.selectedIndex];
      const optLbl = (opt?.text || '').trim();
      let price   = parseFloat(opt?.dataset?.price || 0);
      if (currentCycleKey() === 'annual') price = toAnnual(price);
      if (price > 0) items.push({ label: `${label} – ${optLbl}`, cost: price });
    });

    let total = 0;
    items.forEach(i => total += i.cost);

    return { items, total };
  }

  function addonSubtotal(sym) {
    const group = activeGroup();
    if (!group) return { items: [], total: 0 };

    let items = [];
    let total = 0;
    $$('.addon-svc:checked', group).forEach(chk => {
      const label = chk.dataset.label || 'Add-on';
      const unit  = parseFloat(chk.dataset.unit || 0);
      total += unit;
      if (unit > 0) items.push({ label, cost: unit });
    });
    return { items, total };
  }

  function recalcSummary() {
    const locId = currentLocId();
    const loc   = (data.locations || []).find(l => String(l.id) === String(locId));
    const curr  = loc?.currency_code || 'USD';
    const sym   = symbol(curr);
    if (sumLoc && loc) sumLoc.textContent = loc.name;
    if (chipLocation) chipLocation.textContent = loc?.name || 'Location';

    const cycle = currentCycleKey();
    const cycleLabel = cycle === 'annual' ? 'Annual Billing' : 'Monthly Billing';
    if (chipCycle) chipCycle.textContent = cycleLabel;
    if (summaryCycleTag) summaryCycleTag.textContent = cycleLabel;

    const f = featureSubtotal(sym);
    const a = addonSubtotal(sym);
    const subtotal = f.total + a.total;

    const parts = [];

    if (!activeGroup()) {
      parts.push('<div class="text-secondary text-center">Select category & change values.</div>');
    } else {
      if (f.items.length) {
        parts.push('<div class="fw-semibold text-teal mb-1">Resources</div>');
        f.items.forEach(it => {
          parts.push(`
            <div class="d-flex justify-content-between ps-2">
              <span class="small">${it.label}</span>
              <span class="small">${money(sym, it.cost)}</span>
            </div>
          `);
        });
        parts.push(`
          <div class="d-flex justify-content-between fw-semibold ps-2 mt-1 mb-2">
            <span class="small">Resources Subtotal</span>
            <span class="small">${money(sym, f.total)}</span>
          </div>
        `);
      }

      if (a.items.length) {
        parts.push('<div class="fw-semibold text-teal mt-2 mb-1">Add-ons</div>');
        a.items.forEach(it => {
          parts.push(`
            <div class="d-flex justify-content-between ps-2">
              <span class="small">${it.label}</span>
              <span class="small">${money(sym, it.cost)}</span>
            </div>
          `);
        });
        parts.push(`
          <div class="d-flex justify-content-between fw-semibold ps-2 mt-1">
            <span class="small">Add-ons Subtotal</span>
            <span class="small">${money(sym, a.total)}</span>
          </div>
        `);
      }

      if (!f.items.length && !a.items.length) {
        parts.push('<div class="text-secondary text-center">Change any slider or select an add-on.</div>');
      }
    }

    sumLines.innerHTML = parts.join('') || '<div class="text-secondary text-center">Select category & change values.</div>';
    sumSubtotal.textContent = money(sym, subtotal);

    let discountValue = 0;
    if (appliedCoupon && appliedCoupon.discount_amount) {
      discountValue = Math.min(subtotal, appliedCoupon.discount_amount);
      sumDiscountLabel.textContent = `Discount (${appliedCoupon.code})`;
      sumDiscountValue.textContent = `-${money(sym, discountValue)}`;
      sumDiscountRow?.classList.remove('d-none');
    } else {
      sumDiscountRow?.classList.add('d-none');
    }

    const finalTotal = Math.max(subtotal - discountValue, 0);
    sumTotal.textContent = money(sym, finalTotal);

    // 🔹 Hidden input গুলা আপডেট করবো
    // New hidden payload for SSLCommerz flow
    const productField  = document.getElementById('orderProductId');
    const categoryField = document.getElementById('orderCategoryId');
    const locationField = document.getElementById('orderLocationId');
    const billingField  = document.getElementById('orderBillingCycleId');
    const featuresField = document.getElementById('orderFeaturesJson');
    const addonsField   = document.getElementById('orderAddonsJson');
    const amountField   = document.getElementById('orderAmount');
    const currencyField = document.getElementById('orderCurrency');
    const planField     = document.getElementById('orderPlanTitle');

    const group = activeGroup();
    const catSlug = group?.getAttribute('data-group');
    const cat = (data.categories || []).find(c => String(c.slug) === String(catSlug)) || (data.categories || [])[0];
    const fallbackProduct = ((data.categories || [])[0]?.products || [])[0];
    const product = (cat?.products || [])[0] || fallbackProduct;

    if (categoryField) categoryField.value = cat?.id || '';
    if (productField)  productField.value  = product?.id || '';
    if (locationField) locationField.value = locId || '';

    const billingCycle = (data.billingCycles || []).find(bc =>
      bc.key === cycle ||
      (cycle === 'annual' && ['annual','yearly','yearly_cycle'].includes(String(bc.key))) ||
      (cycle === 'monthly' && String(bc.key) === 'monthly') ||
      (cycle === 'annual' && (parseInt(bc.months || 0) >= 12))
    ) || (cycle === 'annual'
      ? (data.billingCycles || []).find(bc => parseInt(bc.months || 0) >= 12)
      : (data.billingCycles || []).find(bc => String(bc.key) === 'monthly')
    ) || (data.billingCycles || [])[0];
    if (billingField) billingField.value = billingCycle?.id || '';

    const featuresPayload = {};
    if (group) {
      $$('.qty-box', group).forEach(box => {
        const key = box.getAttribute('data-key');
        if (!key) return;
        const slider   = box.querySelector('input[type=\"range\"]');
        const badgeVal = box.previousElementSibling?.querySelector('.qty-val');
        const min      = parseFloat(box.getAttribute('data-min') || 0);
        const val      = parseFloat(slider?.value ?? badgeVal?.textContent ?? min) || min;
        featuresPayload[key] = val;
      });

      $$('.feature-check', group).forEach(chk => {
        const key = chk.getAttribute('data-feature');
        if (!key) return;
        featuresPayload[key] = chk.checked ? 1 : 0;
      });

      $$('.feature-select', group).forEach(sel => {
        const key = sel.getAttribute('data-key');
        if (!key) return;
        featuresPayload[key] = sel.value;
      });
    }

    const addonsPayload = [];
    if (group) {
      $$('.addon-svc:checked', group).forEach(chk => {
        const key = chk.getAttribute('data-key');
        if (!key) return;
        addonsPayload.push({ key, qty: 1 });
      });
    }

    if (featuresField) featuresField.value = JSON.stringify(featuresPayload);
    if (addonsField)   addonsField.value   = JSON.stringify(addonsPayload);
    if (amountField)   amountField.value   = finalTotal.toFixed(2);
    if (currencyField) currencyField.value = curr || 'BDT';
    if (billingField && !billingField.value) {
      billingField.value = (data.billingCycles || [])[0]?.id || '';
    }
    if (planField && summaryPlanTitle) {
      planField.value = summaryPlanTitle.textContent.trim();
    }
  }

  function setCouponMessage(text, type = 'muted') {
    if (!couponMsg) return;
    couponMsg.textContent = text || '';
    couponMsg.classList.remove('success', 'error');
    if (type === 'success') couponMsg.classList.add('success');
    if (type === 'error') couponMsg.classList.add('error');
  }

  function setCouponState(coupon) {
    appliedCoupon = coupon;
    if (coupon) {
      if (couponInput) {
        couponInput.value = coupon.code || '';
        couponInput.disabled = true;
      }
      if (couponBtn) couponBtn.textContent = 'Remove';
    } else {
      if (couponInput) {
        couponInput.disabled = false;
        couponInput.value = '';
      }
      if (couponBtn) couponBtn.textContent = 'Apply';
      setCouponMessage('');
    }
    recalcSummary();
  }

  async function requestCoupon(code) {
    if (!data.routes?.claimCoupon) return;
    if (!data.isAuthenticated) {
      window.location.href = data.routes.login || '/login';
      return;
    }
    if (!csrfToken) {
      setCouponMessage('Unable to validate coupon right now.', 'error');
      return;
    }

    try {
      const res = await fetch(data.routes.claimCoupon, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ code }),
        credentials: 'same-origin',
      });

      if (res.status === 401) {
        window.location.href = data.routes.login || '/login';
        return;
      }

      const json = await res.json();
      if (!res.ok) {
        throw new Error(json?.message || 'Invalid coupon.');
      }

      setCouponState({
        code: json.coupon?.code,
        discount_amount: parseFloat(json.coupon?.discount_amount || 0),
      });
      setCouponMessage(json.message || 'Coupon applied.', 'success');
    } catch (err) {
      setCouponState(null);
      if (couponInput) couponInput.value = code || '';
      setCouponMessage(err.message || 'Coupon not valid.', 'error');
    }
  }

  function bindCoupon() {
    couponBtn?.addEventListener('click', () => {
      if (appliedCoupon) {
        setCouponState(null);
        return;
      }
      const code = couponInput?.value?.trim();
      if (!code) {
        setCouponMessage('Enter a coupon code first.', 'error');
        return;
      }
      setCouponMessage('Validating coupon...');
      requestCoupon(code);
    });
  }

  function init() {
    if (initialized) return;
    initialized = true;
    bindCategoryTabs();
    bindCoupon();

    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
      orderForm.addEventListener('submit', () => {
        recalcSummary();
      });
    }

    locSel?.addEventListener('change', () => {
      if (window.CUSTOMIZE) window.CUSTOMIZE.defaultLocationId = currentLocId();
      initOrRefreshFeaturePricing();
      refreshAddOnPrices();
      recalcSummary();
    });

    billMonthly?.addEventListener('change', () => {
      refreshAddOnPrices();
      recalcSummary();
    });
    billAnnual?.addEventListener('change', () => {
      refreshAddOnPrices();
      recalcSummary();
    });

    initOrRefreshFeaturePricing();
    refreshAddOnPrices();
    recalcSummary();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
