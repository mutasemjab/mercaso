<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class MonthlyTaxSummaryExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $monthlyTotal;
    protected $summary;
    protected $startDate;

    public function __construct($monthlyTotal, $summary, $startDate)
    {
        $this->monthlyTotal = $monthlyTotal;
        $this->summary = $summary;
        $this->startDate = $startDate;
    }

    public function collection()
    {
        $data = collect();
        
        // Header
        $data->push(['', '']);
        $data->push(['MONTHLY TAX SUMMARY - GOVERNMENT FILING', '']);
        $data->push(['Report Generated:', date('Y-m-d H:i:s')]);
        $data->push(['Reporting Month:', $this->monthlyTotal['reporting_month']]);
        $data->push(['', '']);
        
        // Monthly totals
        $data->push(['MONTHLY TOTALS', '']);
        $data->push(['Gross Sales:', '$' . number_format($this->monthlyTotal['gross_sales'], 2)]);
        $data->push(['Total Tax Collected:', '$' . number_format($this->monthlyTotal['total_tax_collected'], 2)]);
        $data->push(['Net Sales (with tax):', '$' . number_format($this->monthlyTotal['net_sales'], 2)]);
        $data->push(['Total Orders:', number_format($this->monthlyTotal['order_count'])]);
        $data->push(['', '']);
        
        // Tax rate breakdown header
        $data->push(['TAX RATE BREAKDOWN', '']);
        $data->push(['Tax Rate (%)', 'Order Count', 'Units Sold', 'Gross Sales', 'Tax Collected', 'Total Sales']);
        
        // Tax breakdown data
        foreach ($this->summary as $row) {
            $data->push([
                $row->tax_rate . '%',
                number_format($row->order_count),
                number_format($row->total_units),
                '$' . number_format($row->gross_sales, 2),
                '$' . number_format($row->tax_collected, 2),
                '$' . number_format($row->total_sales, 2)
            ]);
        }
        
        $data->push(['', '']);
        
        // Filing information
        $data->push(['FILING INFORMATION', '']);
        $data->push(['Filing Due Date:', $this->startDate->addMonth()->format('Y-m-20')]);
        $data->push(['Payment Due Date:', $this->startDate->format('Y-m-20')]);
        $data->push(['Late Filing Penalty:', '10% of tax due']);
        $data->push(['Late Payment Penalty:', '10% of tax due + 1.5% per month']);
        $data->push(['', '']);
        
        // Instructions
        $data->push(['FILING INSTRUCTIONS', '']);
        $data->push(['1. File sales tax return by due date', '']);
        $data->push(['2. Remit payment with return', '']);
        $data->push(['3. Maintain records for 4 years', '']);
        $data->push(['4. Report any exempt sales separately', '']);
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Monthly Tax Summary';
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