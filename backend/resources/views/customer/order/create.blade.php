@extends('layouts.cleanwave')

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <a href="{{ route('customer.orders.index') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Pesanan Saya
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                Buat Pesanan Laundry Baru
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Pilih layanan, tentukan metode antar-jemput, dan pantau total biaya secara transparan.
            </p>
        </div>

        <form action="{{ route('customer.orders.store') }}" method="POST" id="orderForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Left Column: Form Steps (8 Cols) -->
                <div class="lg:col-span-8 space-y-8">

                    <!-- STEP 1: Pilih Layanan -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <span class="w-8 h-8 rounded-xl bg-brand-600 text-white font-bold flex items-center justify-center text-sm">1</span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Pilih Layanan & Perkiraan Jumlah</h2>
                                <p class="text-xs text-slate-500">Centang dan masukkan perkiraan berat/jumlah untuk dihitung.</p>
                            </div>
                        </div>

                        <!-- Services List by Category -->
                        @php $serviceIndex = 0; @endphp
                        @foreach($categories as $cat)
                        <div class="space-y-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg flex items-center gap-2">
                                <i class="fa-solid {{ $cat->icon ?? 'fa-basket-shopping' }} text-brand-600"></i> {{ $cat->name }}
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($cat->activeServices as $svc)
                                <div class="p-4 rounded-2xl border border-slate-200 hover:border-brand-400 bg-white hover:bg-blue-50/20 transition service-card flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between">
                                            <label class="font-bold text-sm text-slate-900 cursor-pointer flex items-center gap-2">
                                                <input type="checkbox" class="service-checkbox rounded text-brand-600 focus:ring-brand-500 w-4 h-4" data-id="{{ $svc->id }}" data-price="{{ $svc->price_per_unit }}" data-name="{{ $svc->name }}" data-unit="{{ $svc->unit }}">
                                                <span>{{ $svc->name }}</span>
                                            </label>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 pl-6">{{ $svc->description }}</p>
                                    </div>

                                    <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between pl-6">
                                        <span class="text-xs font-bold text-brand-600">
                                            Rp {{ number_format($svc->price_per_unit, 0, ',', '.') }} / {{ $svc->unit }}
                                        </span>

                                        <div class="qty-wrapper hidden items-center gap-1.5">
                                            <input type="number" step="0.5" min="0.5" value="1" class="qty-input w-20 px-2 py-1 rounded-lg border border-slate-300 text-xs text-center font-bold" data-id="{{ $svc->id }}">
                                            <span class="text-xs text-slate-500">{{ $svc->unit }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <!-- Hidden Inputs Container for items array -->
                        <div id="hiddenItemsContainer"></div>
                    </div>

                    <!-- STEP 2: Metode Antar-Jemput & Alamat -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <span class="w-8 h-8 rounded-xl bg-brand-600 text-white font-bold flex items-center justify-center text-sm">2</span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Metode Penyerahan Cucian</h2>
                                <p class="text-xs text-slate-500">Pilih kurir antar-jemput ke rumah atau antar sendiri ke outlet.</p>
                            </div>
                        </div>

                        <!-- Pickup Radios -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="p-4 rounded-2xl border-2 border-brand-500 bg-blue-50/30 cursor-pointer flex items-center gap-3 method-card" id="cardPickup">
                                <input type="radio" name="pickup_method" value="pickup" checked class="text-brand-600 focus:ring-brand-500 w-4 h-4">
                                <div>
                                    <span class="block font-bold text-sm text-slate-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-truck-pickup text-brand-600"></i> Antar-Jemput Kurir
                                    </span>
                                    <span class="text-xs text-slate-500">Biaya jemput + antar: Rp 10.000</span>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border border-slate-200 bg-white cursor-pointer flex items-center gap-3 method-card" id="cardDropoff">
                                <input type="radio" name="pickup_method" value="dropoff" class="text-brand-600 focus:ring-brand-500 w-4 h-4">
                                <div>
                                    <span class="block font-bold text-sm text-slate-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-store text-slate-600"></i> Drop-Off ke Outlet
                                    </span>
                                    <span class="text-xs text-slate-500">Gratis (Anda bawa sendiri ke outlet)</span>
                                </div>
                            </label>
                        </div>

                        <!-- Address Section (Shown when pickup is selected) -->
                        <div id="addressSection" class="space-y-4 pt-4 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900">Alamat Penjemputan</h3>

                            @if(count($addresses) > 0)
                            <div class="space-y-2">
                                @foreach($addresses as $addr)
                                <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-brand-400 cursor-pointer bg-white">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} class="mt-1 text-brand-600">
                                    <div>
                                        <span class="font-bold text-xs text-slate-900">{{ $addr->label }}</span>
                                        <p class="text-xs text-slate-600">{{ $addr->full_address }}, {{ $addr->city }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @endif

                            <!-- Quick Input New Address -->
                            <div class="pt-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    {{ count($addresses) > 0 ? 'Atau Tulis Alamat Baru / Catatan Patokan:' : 'Alamat Lengkap Anda:' }}
                                </label>
                                <textarea name="new_address" rows="2" placeholder="Jl. Contoh No. 123, RT/RW, Patokan dekat minimarket..." class="w-full rounded-xl border border-slate-300 text-xs p-3 focus:ring-2 focus:ring-brand-500"></textarea>
                            </div>
                        </div>

                        <!-- Catatan Khusus -->
                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan untuk Petugas / Kurir (Opsional):</label>
                            <input type="text" name="notes" placeholder="Contoh: Pisahkan kemeja putih, jangan pakai pewangi menyengat..." class="w-full rounded-xl border border-slate-300 text-xs p-3 focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <!-- STEP 3: Metode Pembayaran -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <span class="w-8 h-8 rounded-xl bg-brand-600 text-white font-bold flex items-center justify-center text-sm">3</span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Metode Pembayaran</h2>
                                <p class="text-xs text-slate-500">Pilih kemudahan pembayaran digital atau tunai.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="p-4 rounded-2xl border-2 border-brand-500 bg-blue-50/20 cursor-pointer flex flex-col justify-between pay-card">
                                <div class="flex justify-between items-center mb-3">
                                    <input type="radio" name="payment_method" value="qris" checked class="text-brand-600">
                                    <i class="fa-solid fa-qrcode text-xl text-brand-600"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-900">QRIS Instant</span>
                                <span class="text-[10px] text-slate-500">Scan via GoPay, OVO, BCA, dll.</span>
                            </label>

                            <label class="p-4 rounded-2xl border border-slate-200 bg-white cursor-pointer flex flex-col justify-between pay-card">
                                <div class="flex justify-between items-center mb-3">
                                    <input type="radio" name="payment_method" value="transfer" class="text-brand-600">
                                    <i class="fa-solid fa-building-columns text-xl text-slate-600"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-900">Transfer Bank</span>
                                <span class="text-[10px] text-slate-500">BCA / Mandiri / BNI Virtual</span>
                            </label>

                            <label class="p-4 rounded-2xl border border-slate-200 bg-white cursor-pointer flex flex-col justify-between pay-card">
                                <div class="flex justify-between items-center mb-3">
                                    <input type="radio" name="payment_method" value="cash" class="text-brand-600">
                                    <i class="fa-solid fa-hand-holding-dollar text-xl text-slate-600"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-900">Bayar di Tempat (COD)</span>
                                <span class="text-[10px] text-slate-500">Tunai saat kurir antar pakaian.</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Live Order Summary (4 Cols) -->
                <div class="lg:col-span-4">
                    <div class="sticky top-28 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-xl space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-brand-600"></i> Ringkasan Pesanan
                        </h3>

                        <!-- Selected Items List -->
                        <div id="summaryItemsList" class="space-y-2.5 max-h-56 overflow-y-auto pr-1 text-xs">
                            <p class="text-slate-400 italic text-center py-4">Belum ada layanan yang dipilih.</p>
                        </div>

                        <!-- Coupon Input Box -->
                        <div class="pt-3 border-t border-slate-100">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Punya Kode Promo / Kupon?</label>
                            <div class="flex gap-2">
                                <input type="text" id="couponInput" name="coupon_code" placeholder="Misal: CLEANWAVE10" class="w-full rounded-xl border border-slate-300 text-xs px-3 py-2 uppercase font-mono">
                                <button type="button" id="applyCouponBtn" class="px-3 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs">Terapkan</button>
                            </div>
                            <p id="couponMsg" class="text-[11px] mt-1 hidden"></p>
                        </div>

                        <!-- Loyalty Points Redemption -->
                        <div class="pt-3 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-semibold text-slate-700">Tukar Poin Loyalitas</label>
                                <span class="text-[11px] font-bold text-amber-600">
                                    <i class="fa-solid fa-coins mr-1"></i>{{ number_format($customer->loyalty_points, 0, ',', '.') }} poin
                                </span>
                            </div>

                            @if($customer->loyalty_points > 0)
                            <div class="flex gap-2">
                                <input type="number" id="pointsInput" name="points_redeemed" value="0" min="0" max="{{ (int) $customer->loyalty_points }}" step="1" class="w-full rounded-xl border border-slate-300 text-xs px-3 py-2 font-bold">
                                <button type="button" id="applyPointsBtn" class="px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs whitespace-nowrap">Pakai Semua</button>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">1 poin = Rp 1. Poin otomatis dibatasi sesuai sisa tagihan.</p>
                            @else
                            <p class="text-[11px] text-slate-400">Belum ada poin yang bisa ditukar. Selesaikan pesanan untuk mengumpulkan poin.</p>
                            @endif
                        </div>

                        <!-- Calculation Breakdown -->
                        <div class="space-y-2 pt-3 border-t border-slate-100 text-xs text-slate-600">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span id="summarySubtotal" class="font-bold text-slate-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-emerald-600">
                                <span>Diskon:</span>
                                <span id="summaryDiscount" class="font-bold">- Rp 0</span>
                            </div>
                            <div class="flex justify-between text-emerald-600 hidden" id="pointsRow">
                                <span>Tukar Poin (<span id="pointsUsedLabel">0</span>):</span>
                                <span id="summaryPoints" class="font-bold">- Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ongkir Antar-Jemput:</span>
                                <span id="summaryDelivery" class="font-bold text-slate-900">Rp 10.000</span>
                            </div>
                            <div class="flex justify-between text-base font-extrabold text-slate-900 pt-3 border-t border-slate-200">
                                <span>Total Tagihan:</span>
                                <span id="summaryTotal" class="text-brand-600 text-lg">Rp 10.000</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitOrderBtn" class="w-full py-4 rounded-2xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-extrabold text-sm shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-[1.02] transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-lock mr-1.5"></i> Konfirmasi & Buat Pesanan
                        </button>
                        <p class="text-[11px] text-slate-400 text-center">
                            Garansi perlindungan pakaian & kepuasan pelanggan 100%.
                        </p>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    document(() => {
        // Interactivity scripts
    });
</script>
<script>
    const checkboxes = document.querySelectorAll('.service-checkbox');
    const summaryItemsList = document.getElementById('summaryItemsList');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryDiscount = document.getElementById('summaryDiscount');
    const summaryDelivery = document.getElementById('summaryDelivery');
    const summaryTotal = document.getElementById('summaryTotal');
    const hiddenItemsContainer = document.getElementById('hiddenItemsContainer');
    const submitBtn = document.getElementById('submitOrderBtn');
    const pickupRadios = document.querySelectorAll('input[name="pickup_method"]');
    const addressSection = document.getElementById('addressSection');
    const couponInput = document.getElementById('couponInput');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponMsg = document.getElementById('couponMsg');
    const pointsInput = document.getElementById('pointsInput');
    const applyPointsBtn = document.getElementById('applyPointsBtn');
    const pointsRow = document.getElementById('pointsRow');
    const pointsUsedLabel = document.getElementById('pointsUsedLabel');
    const summaryPoints = document.getElementById('summaryPoints');
    const pointsBalance = {{ (int) $customer->loyalty_points }};

    let deliveryFee = 10000;
    let discount = 0;
    let appliedCoupon = null;

    // Checkbox toggling
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const card = this.closest('.service-card');
            const qtyWrapper = card.querySelector('.qty-wrapper');
            if (this.checked) {
                qtyWrapper.classList.remove('hidden');
                qtyWrapper.classList.add('flex');
            } else {
                qtyWrapper.classList.remove('flex');
                qtyWrapper.classList.add('hidden');
            }
            updateSummary();
        });
    });

    // Quantity inputs
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', updateSummary);
    });

    // Pickup method toggling
    pickupRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'pickup') {
                deliveryFee = 10000;
                addressSection.classList.remove('hidden');
                document.getElementById('cardPickup').classList.add('border-brand-500', 'bg-blue-50/30');
                document.getElementById('cardDropoff').classList.remove('border-brand-500', 'bg-blue-50/30');
            } else {
                deliveryFee = 0;
                addressSection.classList.add('hidden');
                document.getElementById('cardDropoff').classList.add('border-brand-500', 'bg-blue-50/30');
                document.getElementById('cardPickup').classList.remove('border-brand-500', 'bg-blue-50/30');
            }
            updateSummary();
        });
    });

    // Coupon button
    applyCouponBtn.addEventListener('click', () => {
        const code = couponInput.value.trim().toUpperCase();
        if (code === 'CLEANWAVE10') {
            appliedCoupon = {
                code: 'CLEANWAVE10',
                type: 'percentage',
                val: 10
            };
            couponMsg.className = 'text-[11px] mt-1 text-emerald-600 font-bold block';
            couponMsg.textContent = '✅ Kupon diskon 10% berhasil diterapkan!';
        } else if (code === 'HEMAT15K') {
            appliedCoupon = {
                code: 'HEMAT15K',
                type: 'fixed',
                val: 15000
            };
            couponMsg.className = 'text-[11px] mt-1 text-emerald-600 font-bold block';
            couponMsg.textContent = '✅ Kupon potongan Rp 15.000 berhasil diterapkan!';
        } else {
            appliedCoupon = null;
            couponMsg.className = 'text-[11px] mt-1 text-rose-600 font-bold block';
            couponMsg.textContent = '❌ Kupon tidak valid atau sudah kadaluarsa.';
        }
        updateSummary();
    });

    // Loyalty points redemption
    if (pointsInput) {
        pointsInput.addEventListener('input', updateSummary);
    }

    if (applyPointsBtn) {
        applyPointsBtn.addEventListener('click', () => {
            let subtotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const card = cb.closest('.service-card');
                    const qty = parseFloat(card.querySelector('.qty-input').value) || 1;
                    subtotal += qty * parseFloat(cb.dataset.price);
                }
            });

            const remaining = Math.max(0, subtotal - discount);
            pointsInput.value = Math.min(pointsBalance, Math.floor(remaining));
            updateSummary();
        });
    }

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function updateSummary() {
        let subtotal = 0;
        let html = '';
        let hiddenHtml = '';
        let itemIndex = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                const id = cb.dataset.id;
                const name = cb.dataset.name;
                const price = parseFloat(cb.dataset.price);
                const unit = cb.dataset.unit;
                const card = cb.closest('.service-card');
                const qtyInput = card.querySelector('.qty-input');
                const qty = parseFloat(qtyInput.value) || 1;

                const itemSub = qty * price;
                subtotal += itemSub;

                html += `
                    <div class="flex justify-between items-center py-1">
                        <div>
                            <span class="font-bold text-slate-900 block">${name}</span>
                            <span class="text-slate-500">${qty} ${unit} × ${formatRupiah(price)}</span>
                        </div>
                        <span class="font-semibold text-slate-800">${formatRupiah(itemSub)}</span>
                    </div>
                `;

                hiddenHtml += `
                    <input type="hidden" name="items[${itemIndex}][service_id]" value="${id}">
                    <input type="hidden" name="items[${itemIndex}][quantity]" value="${qty}">
                `;
                itemIndex++;
            }
        });

        if (itemIndex === 0) {
            summaryItemsList.innerHTML = '<p class="text-slate-400 italic text-center py-4">Belum ada layanan yang dipilih.</p>';
            submitBtn.disabled = true;
        } else {
            summaryItemsList.innerHTML = html;
            submitBtn.disabled = false;
        }

        hiddenItemsContainer.innerHTML = hiddenHtml;

        // Discount
        if (appliedCoupon) {
            if (appliedCoupon.type === 'percentage') {
                discount = (subtotal * appliedCoupon.val) / 100;
            } else {
                discount = Math.min(subtotal, appliedCoupon.val);
            }
        } else {
            discount = 0;
        }

        // Loyalty points
        let pointsRedeemed = 0;
        if (pointsInput) {
            const maxPoints = Math.max(0, Math.min(pointsBalance, Math.floor(subtotal - discount)));
            pointsRedeemed = Math.min(Math.max(0, parseInt(pointsInput.value) || 0), maxPoints);

            if (parseInt(pointsInput.value) > maxPoints) {
                pointsInput.value = maxPoints;
            }
        }

        const grandTotal = Math.max(0, subtotal - discount - pointsRedeemed + deliveryFee);

        summarySubtotal.textContent = formatRupiah(subtotal);
        summaryDiscount.textContent = '- ' + formatRupiah(discount);

        if (pointsRedeemed > 0) {
            pointsRow.classList.remove('hidden');
            pointsUsedLabel.textContent = pointsRedeemed.toLocaleString('id-ID');
            summaryPoints.textContent = '- ' + formatRupiah(pointsRedeemed);
        } else {
            pointsRow.classList.add('hidden');
        }

        summaryDelivery.textContent = formatRupiah(deliveryFee);
        summaryTotal.textContent = formatRupiah(grandTotal);
    }

    // Initial state check
    updateSummary();
</script>
@endpush
@endsection