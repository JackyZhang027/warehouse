<?php

namespace App\Exports;

use App\Models\ItemOut;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ItemOutExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('report.out', [
            'out' => $this->data
        ]);
    }

}
