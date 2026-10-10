<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Address;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::create([
            'name' => 'Administrator CleanWave',
            'email' => 'admin@cleanwave.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // 2. Employee User
        $empUser = User::create([
            'name' => 'Budi Santoso (Operator)',
            'email' => 'budi@cleanwave.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'user_id' => $empUser->id,
            'position' => 'Senior Washing Specialist',
            'shift' => 'morning',
            'is_available' => true,
        ]);

        // 3. Customer User
        $custUser = User::create([
            'name' => 'Sarah Wijaya',
            'email' => 'customer@cleanwave.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081298765432',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'user_id' => $custUser->id,
            'loyalty_points' => 350,
            'membership_level' => 'bronze',
        ]);

        $address = Address::create([
            'customer_id' => $customer->id,
            'label' => 'Rumah Utama',
            'full_address' => 'Jl. Boulevard Raya Blok A4 No. 12, Kelapa Gading',
            'city' => 'Jakarta Utara',
            'postal_code' => '14240',
            'is_default' => true,
        ]);

        // 4. Service Categories
        $catKiloan = ServiceCategory::create([
            'name' => 'Laundry Kiloan',
            'icon' => 'fa-weight-scale',
            'description' => 'Solusi hemat cuci pakaian harian dengan hitungan per kilogram.',
            'is_active' => true,
        ]);

        $catSatuan = ServiceCategory::create([
            'name' => 'Laundry Satuan & Khusus',
            'icon' => 'fa-shirt',
            'description' => 'Perawatan khusus untuk jas, gaun, sepatu, dan pakaian premium.',
            'is_active' => true,
        ]);

        $catRumah = ServiceCategory::create([
            'name' => 'Perlengkapan Rumah',
            'icon' => 'fa-bed',
            'description' => 'Pembersihan mendalam untuk bed cover, selimut, gorden, dan karpet.',
            'is_active' => true,
        ]);

        // 5. Services
        $svc1 = Service::create([
            'category_id' => $catKiloan->id,
            'name' => 'Cuci Kering Lipat (Reguler)',
            'unit' => 'kg',
            'price_per_unit' => 7000,
            'estimated_days' => 2,
            'description' => 'Cuci bersih, wangi, kering higienis, dan dilipat rapi siap simpan.',
            'is_active' => true,
        ]);

        $svc2 = Service::create([
            'category_id' => $catKiloan->id,
            'name' => 'Cuci Komplit + Setrika Uap',
            'unit' => 'kg',
            'price_per_unit' => 10000,
            'estimated_days' => 2,
            'description' => 'Cuci bersih harum ditambah setrika uap anti kusut dengan plastik segel.',
            'is_active' => true,
        ]);

        $svc3 = Service::create([
            'category_id' => $catKiloan->id,
            'name' => 'Express Cuci Kilat (6 Jam)',
            'unit' => 'kg',
            'price_per_unit' => 18000,
            'estimated_days' => 1,
            'description' => 'Layanan darurat prioritas: selesai dalam 6 jam dengan kualitas terbaik.',
            'is_active' => true,
        ]);

        $svc4 = Service::create([
            'category_id' => $catSatuan->id,
            'name' => 'Cuci & Spa Sepatu (Sneakers/Leather)',
            'unit' => 'pasang',
            'price_per_unit' => 35000,
            'estimated_days' => 3,
            'description' => 'Deep cleaning midsole, insole, upper, serta de-yellowing premium.',
            'is_active' => true,
        ]);

        $svc5 = Service::create([
            'category_id' => $catSatuan->id,
            'name' => 'Jas / Blazer Set (Dry Clean)',
            'unit' => 'set',
            'price_per_unit' => 45000,
            'estimated_days' => 3,
            'description' => 'Perawatan dry cleaning profesional menjaga serat kain jas tetap awet.',
            'is_active' => true,
        ]);

        $svc6 = Service::create([
            'category_id' => $catRumah->id,
            'name' => 'Bed Cover Jumbo (Ukuran King)',
            'unit' => 'pcs',
            'price_per_unit' => 30000,
            'estimated_days' => 2,
            'description' => 'Cuci tuntas tungau dan debu dengan mesin drum besar kapasitas 20kg.',
            'is_active' => true,
        ]);

        // 6. Coupons
        $c1 = Coupon::create([
            'code' => 'CLEANWAVE10',
            'description' => 'Diskon 10% untuk pesanan minimal Rp 30.000',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order' => 30000,
            'max_usage' => 500,
            'used_count' => 12,
            'expired_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        $c2 = Coupon::create([
            'code' => 'HEMAT15K',
            'description' => 'Potongan langsung Rp 15.000 minimal pesanan Rp 70.000',
            'discount_type' => 'fixed',
            'discount_value' => 15000,
            'min_order' => 70000,
            'max_usage' => 200,
            'used_count' => 5,
            'expired_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        // 7. Sample Orders for Portfolio Demonstration
        // Order 1: Completed
        $order1 = Order::create([
            'customer_id' => $customer->id,
            'employee_id' => $employee->id,
            'address_id' => $address->id,
            'coupon_id' => $c1->id,
            'order_code' => 'CW-20261001-0001',
            'status' => 'completed',
            'pickup_method' => 'pickup',
            'pickup_date' => now()->subDays(4),
            'delivery_date' => now()->subDays(2),
            'notes' => 'Tolong gunakan pewangi lavender.',
            'subtotal' => 50000,
            'discount' => 5000,
            'delivery_fee' => 10000,
            'total' => 55000,
            'created_at' => now()->subDays(4),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'service_id' => $svc2->id,
            'quantity' => 5,
            'price_per_unit' => 10000,
            'subtotal' => 50000,
        ]);

        Payment::create([
            'order_id' => $order1->id,
            'payment_code' => 'PAY-CW1001',
            'method' => 'qris',
            'status' => 'paid',
            'amount' => 55000,
            'paid_at' => now()->subDays(4)->addMinutes(15),
        ]);

        Review::create([
            'order_id' => $order1->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Pakaian wangi tahan berhari-hari, setrikanya sangat rapi dan kurir jemput tepat waktu! Sangat direkomendasikan.',
        ]);

        // Order 2: In Processing (Active order for tracking demo)
        $order2 = Order::create([
            'customer_id' => $customer->id,
            'employee_id' => $employee->id,
            'address_id' => $address->id,
            'order_code' => 'CW-20261010-0042',
            'status' => 'processing',
            'pickup_method' => 'pickup',
            'pickup_date' => now()->subHours(5),
            'delivery_date' => now()->addDay(),
            'notes' => 'Sepatu putih sneakers tolong hati-hati jangan sampai lunturnya kena kain.',
            'subtotal' => 65000,
            'discount' => 0,
            'delivery_fee' => 10000,
            'total' => 75000,
            'created_at' => now()->subHours(6),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'service_id' => $svc4->id,
            'quantity' => 1,
            'price_per_unit' => 35000,
            'subtotal' => 35000,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'service_id' => $svc6->id,
            'quantity' => 1,
            'price_per_unit' => 30000,
            'subtotal' => 30000,
        ]);

        Payment::create([
            'order_id' => $order2->id,
            'payment_code' => 'PAY-CW1010',
            'method' => 'transfer',
            'status' => 'paid',
            'amount' => 75000,
            'paid_at' => now()->subHours(5),
        ]);

        // Notifications
        Notification::create([
            'user_id' => $custUser->id,
            'title' => 'Pesanan Sedang Diproses',
            'message' => 'Pesanan CW-20261010-0042 sedang dalam tahap pencucian & perawatan oleh Budi Santoso.',
            'type' => 'order',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $custUser->id,
            'title' => 'Kupon Spesial untuk Anda!',
            'message' => 'Gunakan kode CLEANWAVE10 untuk potongan 10% pada cucian berikutnya.',
            'type' => 'promo',
            'is_read' => true,
        ]);
    }
}
