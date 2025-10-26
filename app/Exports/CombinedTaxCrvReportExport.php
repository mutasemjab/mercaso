<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CombinedTaxCrvReportExport implements WithMultipleSheets
{
    protected $taxData;
    protected $crvData;

    public function __construct($taxData, $crvData)
    {
        $this->taxData = $taxData;
        $this->crvData = $crvData;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // Add Combined Overview first
        $sheets['Combined Overview'] = new CombinedOverviewSheetInternal($this->taxData, $this->crvData);
        
        // Add Tax sheets
        if (!empty($this->taxData['summary'])) {
            $sheets['Tax Summary'] = new TaxSummarySheetInternal($this->taxData['summary']);
        }
        
        if (!empty($this->taxData['transactions'])) {
            $sheets['Tax Details'] = new TaxDetailsSheetInternal($this->taxData['transactions']);
        }
        
        if (!empty($this->taxData['summary']['tax_breakdown'])) {
            $sheets['Tax Breakdown'] = new TaxBreakdownSheetInternal($this->taxData['summary']['tax_breakdown']);
        }
        
        // Add CRV sheets
        if (!empty($this->crvData['summary'])) {
            $sheets['CRV Summary'] = new CrvSummarySheetInternal($this->crvData['summary']);
        }
        
        if (!empty($this->crvData['transactions'])) {
            $sheets['CRV Transactions'] = new CrvTransactionsSheetInternal($this->crvData['transactions']);
        }
        
        if (!empty($this->crvData['summary']['product_breakdown'])) {
            $sheets['CRV Products'] = new CrvProductBreakdownSheetInternal($this->crvData['summary']['product_breakdown']);
        }
        
        return $sheets;
    }
}

// Combined Overview Sheet
class CombinedOverviewSheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $taxData;
    protected $crvData;

    public function __construct($taxData, $crvData)
    {
        $this->taxData = $taxData;
        $this->crvData = $crvData;
    }

    public function collection()
    {
        $data = collect();
        
        $data->push(['COMBINED TAX & CRV COMPLIANCE REPORT', '']);
        $data->push(['Report Generated:', date('Y-m-d H:i:s')]);
        
        $startDate = $this->taxData['summary']['reporting_period']['start_date'] ?? 'N/A';
        $endDate = $this->taxData['summary']['reporting_period']['end_date'] ?? 'N/A';
        $data->push(['Reporting Period:', $startDate . ' to ' . $endDate]);
        $data->push(['', '']);
        
        // Tax Summary
        $data->push(['SALES TAX SUMMARY', '']);
        $taxTotals = $this->taxData['summary']['totals'] ?? [];
        $data->push(['Total Gross Sales:', '$' . number_format($taxTotals['total_sales'] ?? 0, 2)]);
        $data->push(['Total Sales Tax Collected:', '$' . number_format($taxTotals['total_taxes'] ?? 0, 2)]);
        $data->push(['Total Tax Orders:', number_format($taxTotals['total_orders'] ?? 0)]);
        $data->push(['Total Line Items:', number_format($taxTotals['total_line_items'] ?? 0)]);
        $data->push(['', '']);
        
        // CRV Summary
        $data->push(['CRV SUMMARY', '']);
        $crvTotals = $this->crvData['summary']['totals'] ?? [];
        $data->push(['Total CRV Collected:', '$' . number_format($crvTotals['total_crv_collected'] ?? 0, 2)]);
        $data->push(['Total CRV Units:', number_format($crvTotals['total_units_sold'] ?? 0)]);
        $data->push(['Total CRV Orders:', number_format($crvTotals['total_orders_with_crv'] ?? 0)]);
        $data->push(['Unique CRV Products:', number_format($crvTotals['unique_crv_products'] ?? 0)]);
        $data->push(['', '']);
        
        // Combined Totals
        $totalTax = $taxTotals['total_taxes'] ?? 0;
        $totalCrv = $crvTotals['total_crv_collected'] ?? 0;
        $totalTaxAndCrv = $totalTax + $totalCrv;
        
        $data->push(['COMBINED TOTALS', '']);
        $data->push(['Total Sales Tax:', '$' . number_format($totalTax, 2)]);
        $data->push(['Total CRV:', '$' . number_format($totalCrv, 2)]);
        $data->push(['Total Sales Tax + CRV:', '$' . number_format($totalTaxAndCrv, 2)]);
        $data->push(['Government Remittance Due:', '$' . number_format($totalTaxAndCrv, 2)]);
        $data->push(['', '']);
        
        // Compliance Information
        $data->push(['COMPLIANCE REQUIREMENTS', '']);
        $data->push(['Sales Tax Filing:', 'Monthly/Quarterly to State Tax Authority']);
        $data->push(['CRV Remittance:', 'Monthly to CalRecycle']);
        $data->push(['Record Retention:', 'Minimum 4 years for audit purposes']);
        $data->push(['', '']);
        
        // Key Dates
        $data->push(['IMPORTANT DEADLINES', '']);
        $data->push(['Sales Tax Due:', '20th of following month']);
        $data->push(['CRV Payment Due:', '15th of following month']);
        $data->push(['Quarterly Return Due:', 'Last day of month following quarter']);
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Combined Overview';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '366092']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            5 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            11 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

// Tax Summary Sheet
class TaxSummarySheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $summary;

    public function __construct($summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        $data = collect();
        
        $data->push(['SALES TAX REPORT - US GOVERNMENT COMPLIANCE', '']);
        $data->push(['Report Generated:', date('Y-m-d H:i:s')]);
        $data->push(['Reporting Period:', ($this->summary['reporting_period']['start_date'] ?? 'N/A') . ' to ' . ($this->summary['reporting_period']['end_date'] ?? 'N/A')]);
        $data->push(['', '']);
        
        // Summary totals
        $data->push(['SUMMARY TOTALS', '']);
        $totals = $this->summary['totals'] ?? [];
        $data->push(['Total Gross Sales:', '$' . number_format($totals['total_sales'] ?? 0, 2)]);
        $data->push(['Total Tax Collected:', '$' . number_format($totals['total_taxes'] ?? 0, 2)]);
        $data->push(['Total Orders:', number_format($totals['total_orders'] ?? 0)]);
        $data->push(['Total Line Items:', number_format($totals['total_line_items'] ?? 0)]);
        $data->push(['', '']);
        
        // Tax breakdown
        if (!empty($this->summary['tax_breakdown'])) {
            $data->push(['TAX RATE BREAKDOWN', '']);
            $data->push(['Tax Rate (%)', 'Total Sales', 'Tax Collected', 'Transactions']);
            
            foreach ($this->summary['tax_breakdown'] as $breakdown) {
                $data->push([
                    $breakdown['tax_rate'] . '%',
                    '$' . number_format($breakdown['total_sales'], 2),
                    '$' . number_format($breakdown['total_tax'], 2),
                    number_format($breakdown['transaction_count'])
                ]);
            }
        }
        
        $data->push(['', '']);
        $data->push(['Compliance Note:', $this->summary['compliance_note'] ?? '']);
        
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
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '366092']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            5 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

// Tax Details Sheet
class TaxDetailsSheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                $item['order_number'] ?? '',
                $item['date'] ?? '',
                $item['customer_name'] ?? '',
                $item['product_name'] ?? '',
                $item['quantity'] ?? 0,
                '$' . number_format($item['unit_price'] ?? 0, 2),
                '$' . number_format($item['sale_amount'] ?? 0, 2),
                ($item['tax_rate'] ?? 0) . '%',
                '$' . number_format($item['tax_amount'] ?? 0, 2),
                '$' . number_format($item['total_amount'] ?? 0, 2),
                $item['order_status'] ?? ''
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
        return 'Tax Details';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

// Tax Breakdown Sheet
class TaxBreakdownSheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                ($item['tax_rate'] ?? 0) . '%',
                '$' . number_format($item['total_sales'] ?? 0, 2),
                '$' . number_format($item['total_tax'] ?? 0, 2),
                number_format($item['transaction_count'] ?? 0),
                (($item['total_sales'] ?? 0) > 0 ? number_format((($item['total_tax'] ?? 0) / ($item['total_sales'] ?? 1)) * 100, 2) : 0) . '%'
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
        return 'Tax Breakdown';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

// CRV Summary Sheet
class CrvSummarySheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $summary;

    public function __construct($summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        $data = collect();
        
        $data->push(['CRV (CALIFORNIA REDEMPTION VALUE) REPORT', '']);
        $data->push(['Report Generated:', date('Y-m-d H:i:s')]);
        $data->push(['Reporting Period:', ($this->summary['reporting_period']['start_date'] ?? 'N/A') . ' to ' . ($this->summary['reporting_period']['end_date'] ?? 'N/A')]);
        $data->push(['', '']);
        
        // Summary totals
        $data->push(['CRV SUMMARY TOTALS', '']);
        $totals = $this->summary['totals'] ?? [];
        $data->push(['Total CRV Collected:', '$' . number_format($totals['total_crv_collected'] ?? 0, 2)]);
        $data->push(['Total Units Sold:', number_format($totals['total_units_sold'] ?? 0)]);
        $data->push(['Total Orders with CRV:', number_format($totals['total_orders_with_crv'] ?? 0)]);
        $data->push(['Unique CRV Products:', number_format($totals['unique_crv_products'] ?? 0)]);
        $data->push(['', '']);
        
        // California CRV Information
        $data->push(['CALIFORNIA CRV INFORMATION', '']);
        $data->push(['CRV Rate (Containers < 24oz):', '$0.05']);
        $data->push(['CRV Rate (Containers >= 24oz):', '$0.10']);
        $data->push(['', '']);
        
        // Compliance note
        $data->push(['Compliance Note:', $this->summary['compliance_note'] ?? '']);
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
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            5 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

// CRV Transactions Sheet
class CrvTransactionsSheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                $item['order_number'] ?? '',
                $item['date'] ?? '',
                $item['customer_name'] ?? '',
                $item['product_name'] ?? '',
                $item['product_barcode'] ?? '',
                $item['quantity'] ?? 0,
                '$' . number_format($item['crv_per_unit'] ?? 0, 2),
                '$' . number_format($item['total_crv'] ?? 0, 2),
                $item['order_status'] ?? ''
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
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}

// CRV Product Breakdown Sheet
class CrvProductBreakdownSheetInternal implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                $item['product_name'] ?? '',
                $item['product_barcode'] ?? '',
                '$' . number_format($item['crv_per_unit'] ?? 0, 2),
                number_format($item['total_units_sold'] ?? 0),
                '$' . number_format($item['total_crv_collected'] ?? 0, 2),
                '$' . number_format(($item['total_units_sold'] ?? 0) > 0 ? ($item['total_crv_collected'] ?? 0) / ($item['total_units_sold'] ?? 1) : 0, 2)
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
        return 'CRV Products';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E8B57']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}