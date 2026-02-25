<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CategoryReportExport implements FromView
{
    protected $salesData;
    protected $inventoryData;

    public function __construct($salesData, $inventoryData)
    {
        $this->salesData = $salesData;
        $this->inventoryData = $inventoryData;
    }

    public function view(): View
    {
        return view('exports.category_report_export', [
            'salesData' => $this->salesData,
            'inventoryData' => $this->inventoryData,
        ]);
    }
}