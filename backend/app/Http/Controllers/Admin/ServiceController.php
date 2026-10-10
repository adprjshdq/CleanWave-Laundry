<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with('services')->get();
        $services = Service::with('category')->latest()->paginate(15);
        return view('admin.services.index', compact('categories', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'price_per_unit' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Service::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'unit' => $request->unit,
            'price_per_unit' => $request->price_per_unit,
            'estimated_days' => $request->estimated_days,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'price_per_unit' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }
}
