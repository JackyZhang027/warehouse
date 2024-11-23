<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\MaterialRequest;
use App\Models\DeliveryOrder;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('Super Admin')) {
            $totalWarehouses = Warehouse::count(); 
            $totalMaterialRequest = MaterialRequest::count();
            $totalDeliveryNote = DeliveryOrder::count();
        } else {
            $totalWarehouses = $user->warehouses()->count();
            $totalMaterialRequest = MaterialRequest::whereIn('warehouse_id', $user->warehouses()->pluck('id'))->count();
            $totalDeliveryNote = DeliveryOrder::whereIn('warehouse_id', $user->warehouses()->pluck('id'))->count();
        }

        return view('home', compact('totalWarehouses', 'totalMaterialRequest', 'totalDeliveryNote'));
    }
}
