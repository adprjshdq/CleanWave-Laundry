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

    /** Test 6: Customer dapat melihat dan menandai notifikasi sebagai dibaca */
    public function test_customer_can_view_and_read_notifications(): void
    {
        /** @var User $customerUser */
        $customerUser = User::where('role', 'customer')->first();
        $notification = $customerUser->notifications()->where('is_read', false)->firstOrFail();

        $this->actingAs($customerUser)
            ->get(route('notifications.index'))
            ->assertStatus(200)
            ->assertSee('Notifikasi')
            ->assertSee($notification->title);

        $this->actingAs($customerUser)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    /** Test 7: Customer dapat mengunduh invoice pesanan dalam format PDF */
    public function test_customer_can_download_invoice_pdf(): void
    {
        /** @var User $customerUser */
        $customerUser = User::where('role', 'customer')->first();
        $order = Order::where('customer_id', $customerUser->customer->id)->firstOrFail();

        $response = $this->actingAs($customerUser)
            ->get(route('customer.orders.invoice', $order->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    /** Test 8: Admin dapat membuka laporan dan mengekspor data ke Excel */
    public function test_admin_can_access_reports_and_export_excel(): void
    {
        /** @var User $adminUser */
        $adminUser = User::where('role', 'admin')->first();

        $this->actingAs($adminUser)
            ->get(route('admin.reports.index'))
            ->assertStatus(200)
            ->assertSee('Laporan Operasional');

        $this->actingAs($adminUser)
            ->get(route('admin.reports.orders.export'))
            ->assertStatus(200);

        $this->actingAs($adminUser)
            ->get(route('admin.reports.services.export'))
            ->assertStatus(200);
    }

    /** Test 9: Customer dapat menukar poin loyalitas saat membuat pesanan */
    public function test_customer_can_redeem_loyalty_points_on_order(): void
    {
        /** @var User $customerUser */
        $customerUser = User::where('role', 'customer')->first();
        $customer = $customerUser->customer;
        $service = Service::first();
        $initialPoints = $customer->loyalty_points;

        $response = $this->actingAs($customerUser)->post(route('customer.orders.store'), [
            'items' => [
                ['service_id' => $service->id, 'quantity' => 4],
            ],
            'pickup_method' => 'dropoff',
            'payment_method' => 'qris',
            'points_redeemed' => 200,
        ]);

        $response->assertRedirect();

        $order = Order::where('customer_id', $customer->id)->latest('id')->firstOrFail();
        $this->assertEquals(200, $order->points_redeemed);
        $this->assertEquals(($service->price_per_unit * 4) - 200, $order->total);
        $this->assertEquals($initialPoints - 200, $customer->fresh()->loyalty_points);
    }
}
