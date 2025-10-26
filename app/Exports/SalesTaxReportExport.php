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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SalesTaxReportExport implements WithMultipleSheets
{
    protected $taxData;
    protected $summary;

    public function __construct($taxData, $summary)
    {
        $this->taxData = $taxData;
        $this->summary = $summary;
    }

    public function sheets(): array
    {
        return [
            'Tax Summary' => new TaxSummarySheet($this->summary),
            'Transaction Details' => new TaxDetailsSheet($this->taxData),
            'Tax Rate Breakdown' => new TaxBreakdownSheet($this->summary['tax_breakdown']),
        ];
    }
}

class TaxSummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
        $data->push(['SALES TAX REPORT - US GOVERNMENT COMPLIANCE', '']);
        $data->push(['Report Generated:', date('Y-m-d H:i:s')]);
        $data->push(['Reporting Period:', $this->summary['reporting_period']['start_date'] . ' to ' . $this->summary['reporting_period']['end_date']]);
        $data->push(['', '']);
        
        // Summary totals
        $data->push(['SUMMARY TOTALS', '']);
        $data->push(['Total Gross Sales:', '$' . number_format($this->summary['totals']['total_sales'], 2)]);
        $data->push(['Total Tax Collected:', '$' . number_format($this->summary['totals']['total_taxes'], 2)]);
        $data->push(['Total Orders:', number_format($this->summary['totals']['total_orders'])]);
        $data->push(['Total Line Items:', number_format($this->summary['totals']['total_line_items'])]);
        $data->push(['', '']);
        
        // Tax breakdown header
        $data->push(['TAX RATE BREAKDOWN', '']);
        $data->push(['Tax Rate (%)', 'Total Sales', 'Tax Collected', 'Transactions']);
        
        // Tax breakdown data
        foreach ($this->summary['tax_breakdown'] as $breakdown) {
            $data->push([
                $breakdown['tax_rate'] . '%',
                '$' . number_format($breakdown['total_sales'], 2),
                '$' . number_format($breakdown['total_tax'], 2),
                number_format($breakdown['transaction_count'])
            ]);
        }
        
        $data->push(['', '']);
        $data->push(['Compliance Note:', $this->summary['compliance_note']]);
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Tax Summary';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '366092']],
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
            13 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D9E1F2']]
            ],
        ];
    }
}

class TaxDetailsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $taxData;

    public function __construct($taxData)
    {
        $this->taxData = $taxData;
    }

    public function collection()
    {
        return collect($this->taxData)->map(function ($item) {
            return [
                $item['order_number'],
                $item['date'],
                $item['customer_name'],
                $item['product_name'],
                $item['quantity'],
                '$' . number_format($item['unit_price'], 2),
                '$' . number_format($item['sale_amount'], 2),
                $item['tax_rate'] . '%',
                '$' . number_format($item['tax_amount'], 2),
                '$' . number_format($item['total_amount'], 2),
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
            'Product',
            'Quantity',
            'Unit Price',
            'Sale Amount',
            'Tax Rate',
            'Tax Amount',
            'Total Amount',
            'Status'
        ];
    }

    public function title(): string
    {
        return 'Transaction Details';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

class TaxBreakdownSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $taxBreakdown;

    public function __construct($taxBreakdown)
    {
        $this->taxBreakdown = $taxBreakdown;
    }

    public function collection()
    {
        return collect($this->taxBreakdown)->map(function ($item) {
            return [
                $item['tax_rate'] . '%',
                '$' . number_format($item['total_sales'], 2),
                '$' . number_format($item['total_tax'], 2),
                number_format($item['transaction_count']),
                ($item['total_sales'] > 0 ? number_format(($item['total_tax'] / $item['total_sales']) * 100, 2) : 0) . '%'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tax Rate (%)',
            'Total Sales',
            'Tax Collected',
            'Transaction Count',
            'Effective Tax Rate'
        ];
    }

    public function title(): string
    {
        return 'Tax Rate Breakdown';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}