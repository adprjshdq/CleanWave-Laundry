<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Customer;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Order;
use App\Models\Coupon;
use Database\Seeders\DatabaseSeeder;

class CleanWaveLaundryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /** Test 1: Halaman publik dapat diakses dengan baik */
    public function test_public_pages_load_successfully(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('CleanWave');

        $responseServices = $this->get('/layanan');
        $responseServices->assertStatus(200);
        $responseServices->assertSee('Laundry Kiloan');

        $responseTracking = $this->get('/lacak-pesanan');
        $responseTracking->assertStatus(200);
    }

    /** Test 2: Live Tracking berhasil menemukan pesanan berdasarkan kode */
    public function test_tracking_returns_order_details(): void
    {
        $response = $this->get('/lacak-pesanan/CW-20261010-0042');
        $response->assertStatus(200);
        $response->assertSee('CW-20261010-0042');
        $response->assertSee('Sedang Diproses');
    }

    /** Test 3: Pengguna terautentikasi dapat membuat pesanan baru */
    public function test_customer_can_create_laundry_order(): void
    {
        /** @var User $customerUser */
        $customerUser = User::where('role', 'customer')->first();
        $service = Service::first();

        $response = $this->actingAs($customerUser)->post(route('customer.orders.store'), [
            'items' => [
                ['service_id' => $service->id, 'quantity' => 4],
            ],
            'pickup_method' => 'dropoff',
            'payment_method' => 'qris',
            'notes' => 'Tolong pisahkan pakaian halus.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customerUser->customer->id,
            'pickup_method' => 'dropoff',
            'status' => 'pending',
        ]);
    }

    /** Test 4: Customer biasa tidak dapat mengakses panel admin (RBAC protection) */
    public function test_customer_cannot_access_admin_panel(): void
    {
        /** @var User $customerUser */
        $customerUser = User::where('role', 'customer')->first();

        $response = $this->actingAs($customerUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /** Test 5: Admin dapat mengakses panel dashboard dan manajemen order */
    public function test_admin_can_access_dashboard(): void
    {
        /** @var User $adminUser */
        $adminUser = User::where('role', 'admin')->first();

        $response = $this->actingAs($adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Ringkasan Operasional');
    }
}
