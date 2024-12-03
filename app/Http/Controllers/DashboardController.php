<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\MaterialRequest;
use App\Models\DeliveryOrder;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('Super Admin')) {
            $warehouses = Warehouse::all()->mapWithKeys(function ($item) {
                return [$item->id => $item->spk_number . ' - ' . $item->project];
            })->all();
        } else {
            $warehouses = $user->warehouses->mapWithKeys(function ($item) {
                return [$item->id => $item->spk_number . ' - ' . $item->project];
            })->all();
        }

        $totalWarehouses = count($warehouses);
        if ($user && $user->hasRole('Super Admin')) {
            $totalMaterialRequest = MaterialRequest::count();
            $totalDeliveryNote = DeliveryOrder::count();
        } else {
            $totalMaterialRequest = MaterialRequest::whereIn('warehouse_id', $user->warehouses()->pluck('warehouses.id'))->count();
            $totalDeliveryNote = DeliveryOrder::whereIn('warehouse_id', $user->warehouses()->pluck('warehouses.id'))->count();
        }

        $announcements = Announcement::where('published', true)
                            ->where('expire_date', '>', now())
                            ->get();

        return view('home', compact('warehouses', 'totalWarehouses', 'totalMaterialRequest', 'totalDeliveryNote', 'announcements'));
    }
}
