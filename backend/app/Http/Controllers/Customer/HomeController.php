<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with('activeServices')
            ->where('is_active', true)
            ->get();

        $featuredServices = Service::where('is_active', true)
            ->with('category')
            ->take(6)
            ->get();

        $reviews = Review::with(['customer.user'])
            ->latest()
            ->take(6)
            ->get();

        $avgRating = Review::avg('rating');

        return view('customer.home', compact(
            'categories', 'featuredServices', 'reviews', 'avgRating'
        ));
    }

    public function services()
    {
        $categories = ServiceCategory::with('activeServices')
            ->where('is_active', true)
            ->get();

        return view('customer.services', compact('categories'));
    }

    public function tracking()
    {
        return view('customer.tracking');
    }

    public function trackingResult(string $code)
    {
        $order = \App\Models\Order::with(['items.service', 'payment', 'employee.user', 'address'])
            ->where('order_code', $code)
            ->firstOrFail();

        return view('customer.tracking', compact('order'));
    }
}
