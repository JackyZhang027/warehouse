<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    protected $data;
    protected $view;
    protected $warehouse;
    protected $period;

    public function __construct($data, $warehouse, $period, $view)
    {
        $this->data = $data;
        $this->view = $view;
        $this->warehouse = $warehouse;
        $this->period = $period;
    }

    public function view(): View
    {
        return view($this->view, [
            'datas' => $this->data,
            'warehouse' => $this->warehouse,
            'period' => $this->period,
        ]);
    }
}
