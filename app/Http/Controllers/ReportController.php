<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\ItemArrival;
use App\Models\ItemCategory;
use App\Models\ItemOut;
use App\Models\ItemOutDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
        $categories = ItemCategory::all();
        return View('report.index', compact('warehouses', 'categories'));
    }

    public function generate(Request $request)
    {
        $report_type = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $startDate . ' S/D '. $endDate;
        $warehouse_id = $request->input('warehouse_id');
        $category_id = $request->input('category_id');
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $view = '';
        $file_name = '';
        if($report_type == 'incoming'){
            $datas = ItemArrival::with(['deliveryOrderItem', 'deliveryOrderItem.deliveryOrder', 'deliveryOrderItem.materialRequestItem.item'])
                    ->whereBetween('arrival_date', [$startDate, $endDate])
                    ->whereHas('deliveryOrderItem.deliveryOrder.warehouse', function ($query) use ($warehouse_id) {
                        $query->where('warehouses.id', $warehouse_id);
                    })
                    ->whereHas('deliveryOrderItem.materialRequestItem.item', function ($query) use ($category_id) {
                        $query->where('items.category_id', $category_id);
                    })
                    ->orderBy('arrival_date')
                    ->get();
            
            $view = 'report.incoming';
            $file_name = 'Laporan Barang Masuk.xlsx';
        }
        elseif($report_type == 'outgoing'){
            $datas = ItemOutDetail::with(['itemOut', 'item'])
                    ->whereHas('itemOut.warehouse', function ($query) use ($warehouse_id, $startDate, $endDate) {
                        $query->where('warehouses.id', $warehouse_id)
                            ->whereBetween('date', [$startDate, $endDate]);
                    })
                    ->whereHas('item', function ($query) use ($category_id) {
                        $query->where('items.category_id', $category_id);
                    })
                    ->join('item_outs', 'item_out_details.item_out_id', '=', 'item_outs.id')
                    ->orderBy('item_outs.date')
                    ->select('item_out_details.*') // Ensure only item_out_details columns are selected
                    ->get();
            $view = 'report.outgoing';
            $file_name = 'Laporan Barang Keluar.xlsx';
        }
        elseif($report_type == 'stock'){
            $datas = DB::select("
                SELECT 
                    i.id AS item_id, 
                    i.code, 
                    i.name, 
                    u.name as uom,
                    SUM(CASE WHEN sc.date < ? THEN qty ELSE 0 END) AS last_week_stock,
                    SUM(CASE WHEN sc.date BETWEEN ? AND ? AND sc.type = 'in' THEN qty ELSE 0 END) AS in_this_week,
                    SUM(CASE WHEN sc.date BETWEEN ? AND ? AND sc.type = 'out' THEN qty * -1 ELSE 0 END) AS out_this_week,
                    SUM(CASE WHEN sc.type = 'in' THEN qty ELSE 0 END) AS total_in_stock
                FROM stock_cards sc
                INNER JOIN items i ON sc.item_id = i.id
                INNER JOIN uom u ON i.uom_id = u.id
                WHERE sc.date <= ? 
                AND i.category_id = ?
                AND sc.warehouse_id = ?
                GROUP BY i.id, i.code, i.name, u.name
            ", [$startDate, $startDate, $endDate, $startDate, $endDate, $endDate, $category_id, $warehouse_id]);
            $view = 'report.stock';
            $file_name = 'Laporan Stock Barang.xlsx';
        }
        elseif($report_type == 'spm'){
            $datas = DB::select("
                SELECT mr.mr_number, 
                    DATE_FORMAT(mr.date, '%d/%m/%y') as date,
                    i.code, i.name, u.name as uom,
                    mri.qty as spm_qty, 
                    SUM(COALESCE(ia.arrived_qty, 0)) as arrived_qty, 
                    TRIM(BOTH ', ' FROM GROUP_CONCAT(DISTINCT COALESCE(doi.po_number, '') ORDER BY doi.po_number ASC SEPARATOR ', ')) AS po_numbers,
                    TRIM(BOTH ', ' FROM GROUP_CONCAT(DISTINCT COALESCE(doi.po_date, '') ORDER BY doi.po_date ASC SEPARATOR ', ')) AS po_dates,
                    TRIM(BOTH ', ' FROM GROUP_CONCAT(DISTINCT COALESCE(doi.vendor, '') ORDER BY doi.vendor ASC SEPARATOR ', ')) AS vendors,
                    TRIM(BOTH ', ' FROM GROUP_CONCAT(DISTINCT COALESCE(DATE_FORMAT(ia.arrival_date, '%d/%m/%y'), '') ORDER BY ia.arrival_date ASC SEPARATOR ', ')) AS arrival_dates,
                    s.status
                FROM material_requests mr
                LEFT JOIN material_request_items mri ON mr.id = mri.mr_id
                LEFT JOIN delivery_order_items doi ON mri.id = doi.material_request_item_id
                LEFT JOIN delivery_orders d ON doi.delivery_order_id = d.id
                LEFT JOIN item_arrivals ia ON ia.delivery_order_item_id = doi.id
                LEFT JOIN items i ON mri.item_id = i.id
                LEFT JOIN uom u ON i.uom_id = u.id
                LEFT JOIN status s ON mr.status_id = s.id
                WHERE mr.warehouse_id = ?
                GROUP BY mr.mr_number, mr.date, mri.qty, i.code, i.name, u.name, s.status
                ORDER BY date
            ", [$warehouse_id]);
            $view = 'report.spm_list';
            $file_name = 'Laporan Seluruh SPM.xlsx';
        }
        

        return Excel::download(new ReportExport($datas, $warehouse, $period, $view), $file_name);

    }
}
