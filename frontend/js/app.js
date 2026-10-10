/**
 * CleanWave Laundry — Frontend Interactive Logic & API Client
 */

const API_BASE = 'http://localhost:8000/api';

// Currency formatter
function formatRupiah(amount) {
  return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

// Global CleanWave State
const CleanWave = {
  services: [
    { id: 1, name: 'Cuci Kering Lipat (Reguler)', category: 'Laundry Kiloan', unit: 'kg', price: 7000, days: 2, desc: 'Cuci bersih harum, kering higienis, dilipat rapi siap simpan.' },
    { id: 2, name: 'Cuci Komplit + Setrika Uap', category: 'Laundry Kiloan', unit: 'kg', price: 10000, days: 2, desc: 'Cuci bersih ditambah setrika uap anti kusut segel plastik.' },
    { id: 3, name: 'Express Cuci Kilat (6 Jam)', category: 'Laundry Kiloan', unit: 'kg', price: 18000, days: 1, desc: 'Layanan darurat prioritas selesai dalam 6 jam.' },
    { id: 4, name: 'Cuci & Spa Sepatu (Sneakers)', category: 'Laundry Satuan', unit: 'pasang', price: 35000, days: 3, desc: 'Deep cleaning midsole, insole, upper de-yellowing.' },
    { id: 5, name: 'Jas / Blazer Set (Dry Clean)', category: 'Laundry Satuan', unit: 'set', price: 45000, days: 3, desc: 'Perawatan dry cleaning menjaga serat kain tetap awet.' },
    { id: 6, name: 'Bed Cover Jumbo King Size', category: 'Perlengkapan Rumah', unit: 'pcs', price: 30000, days: 2, desc: 'Cuci tuntas tungau dengan mesin drum kapasitas 20kg.' }
  ],

  coupons: {
    'CLEANWAVE10': { type: 'percent', val: 10, desc: 'Diskon 10%' },
    'HEMAT15K': { type: 'fixed', val: 15000, desc: 'Potongan Rp 15.000' }
  },

  // Calculate order total
  calculateOrder(selectedItems, pickupMethod, couponCode) {
    let subtotal = 0;
    selectedItems.forEach(item => {
      subtotal += (item.price * item.qty);
    });

    let deliveryFee = (pickupMethod === 'pickup') ? 10000 : 0;
    let discount = 0;

    if (couponCode && this.coupons[couponCode.toUpperCase()]) {
      const c = this.coupons[couponCode.toUpperCase()];
      if (c.type === 'percent') {
        discount = (subtotal * c.val) / 100;
      } else {
        discount = Math.min(subtotal, c.val);
      }
    }

    const total = Math.max(0, subtotal - discount + deliveryFee);

    return {
      subtotal,
      deliveryFee,
      discount,
      total
    };
  }
};

// Auto export to window for browser access
window.CleanWave = CleanWave;
window.formatRupiah = formatRupiah;
