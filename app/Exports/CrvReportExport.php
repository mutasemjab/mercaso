<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class CrvReportExport implements WithMultipleSheets
{
    protected $crvData;
    protected $summary;

    public function __construct($crvData, $summary)
    {
        $this->crvData = $crvData;
        $this->summary = $summary;
    }

    public function sheets(): array
    {
        return [
            'CRV Summary' => new CrvSummarySheet($this->summary),
            'CRV Transactions' => new CrvTransactionsSheet($this->crvData),
            'Product Breakdown' => new CrvProductBreakdownSheet($this->summary['product_breakdown']),
        ];
    }
}

class CrvSummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $summary;

    public function __construct($summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        $data = collect();
        
        // Add report info
        $data->push(['', '']);
        $data->push(['CRV (CALIFORNIA REDEMPTION VALUE) REPORT', '']);
        $data->push(['Report Generated:', date('Y-m-d H:i:s')]);
        $data->push(['Reporting Period:', $this->summary['reporting_period']['start_date'] . ' to ' . $this->summary['reporting_period']['end_date']]);
        $data->push(['', '']);
        
        // Summary totals
        $data->push(['CRV SUMMARY TOTALS', '']);
        $data->push(['Total CRV Collected:', '$' . number_format($this->summary['totals']['total_crv_collected'], 2)]);
        $data->push(['Total Units Sold:', number_format($this->summary['totals']['total_units_sold'])]);
        $data->push(['Total Orders with CRV:', number_format($this->summary['totals']['total_orders_with_crv'])]);
        $data->push(['Unique CRV Products:', number_format($this->summary['totals']['unique_crv_products'])]);
        $data->push(['', '']);
        
        // California CRV Information
        $data->push(['CALIFORNIA CRV INFORMATION', '']);
        $data->push(['CRV Rate (Containers < 24oz):', '$0.05']);
        $data->push(['CRV Rate (Containers >= 24oz):', '$0.10']);
        $data->push(['', '']);
        
        // Compliance note
        $data->push(['Compliance Note:', $this->summary['compliance_note']]);
        $data->push(['', '']);
        $data->push(['Legal Reference:', 'California Public Resources Code Section 14500-14599']);
        $data->push(['Reporting Requirement:', 'Monthly CRV payments due to CalRecycle']);
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'CRV Summary';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            12 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

class CrvTransactionsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $crvData;

    public function __construct($crvData)
    {
        $this->crvData = $crvData;
    }

    public function collection()
    {
        return collect($this->crvData)->map(function ($item) {
            return [
                $item['order_number'],
                $item['date'],
                $item['customer_name'],
                $item['product_name'],
                $item['product_barcode'],
                $item['quantity'],
                '$' . number_format($item['crv_per_unit'], 2),
                '$' . number_format($item['total_crv'], 2),
                $item['order_status']
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Date',
            'Customer',
            'Product Name',
            'Barcode',
            'Quantity',
            'CRV per Unit',
            'Total CRV',
            'Status'
        ];
    }

    public function title(): string
    {
        return 'CRV Transactions';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

class CrvProductBreakdownSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $productBreakdown;

    public function __construct($productBreakdown)
    {
        $this->productBreakdown = $productBreakdown;
    }

    public function collection()
    {
        return collect($this->productBreakdown)->map(function ($item) {
            return [
                $item['product_name'],
                $item['product_barcode'],
                '$' . number_format($item['crv_per_unit'], 2),
                number_format($item['total_units_sold']),
                '$' . number_format($item['total_crv_collected'], 2),
                '$' . number_format($item['total_units_sold'] > 0 ? $item['total_crv_collected'] / $item['total_units_sold'] : 0, 2)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Barcode',
            'CRV per Unit',
            'Total Units Sold',
            'Total CRV Collected',
            'Average CRV per Unit'
        ];
    }

    public function title(): string
    {
        return 'Product Breakdown';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}