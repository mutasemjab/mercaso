

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 font-weight-bold text-primary mb-2">
                        <i class="fas fa-chart-line mr-2"></i>Sales Report By Category
                    </h1>
                    <p class="lead text-muted mb-1">for All Sales Outlets</p>
                    <p class="text-muted">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('M d, Y')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Section -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filter Report</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('tax-crv-report')); ?>" class="form-inline justify-content-center">
                        <div class="form-group mr-3 mb-2">
                            <label for="start_date" class="mr-2 font-weight-bold">Start Date:</label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date"
                                   class="form-control" 
                                   value="<?php echo e(\Carbon\Carbon::parse($startDate)->format('Y-m-d')); ?>"
                                   required>
                        </div>
                        
                        <div class="form-group mr-3 mb-2">
                            <label for="end_date" class="mr-2 font-weight-bold">End Date:</label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date"
                                   class="form-control" 
                                   value="<?php echo e(\Carbon\Carbon::parse($endDate)->format('Y-m-d')); ?>"
                                   required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg mb-2">
                            <i class="fas fa-sync-alt mr-2"></i>Generate Report
                        </button>
                        
                        <a href="<?php echo e(route('tax-crv-report')); ?>" class="btn btn-outline-secondary btn-lg ml-2 mb-2">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 no-print">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo e(number_format($totals['total'], 2)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Tax</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo e(number_format($totals['tax'], 2)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total CRV</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo e(number_format($totals['crv'], 2)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-recycle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Quantity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($totals['quantity'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-dark text-white no-print">
                    <h5 class="mb-0"><i class="fas fa-table mr-2"></i>Detailed Report</h5>
                </div>
                <div class="card-body p-0">
                    <!-- Print Header (Only visible when printing) -->
                    <div class="print-only" style="display: none;">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <h2 style="margin: 0; font-size: 24px; font-weight: bold;">Sales Report By Category</h2>
                            <p style="margin: 5px 0; font-size: 14px;"><strong>for All Sales Outlets</strong></p>
                            <p style="margin: 5px 0; font-size: 12px;">
                                <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('M d, Y')); ?>

                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0" id="reportTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 3%;">#</th>
                                    <th style="width: 15%;">Category</th>
                                    <th class="text-right" style="width: 7%;">Quantity</th>
                                    <th class="text-right" style="width: 8%;">Sales ($)</th>
                                    <th class="text-right" style="width: 8%;">Discount ($)</th>
                                    <th class="text-right" style="width: 8%;">Total ($)</th>
                                    <th class="text-right" style="width: 7%;">Tax ($)</th>
                                    <th class="text-right" style="width: 7%;">CRV ($)</th>
                                    <th class="text-right" style="width: 7%;">Cost ($)</th>
                                    <th class="text-right" style="width: 8%;">Profit ($)</th>
                                    <th class="text-right" style="width: 7%;">Margin</th>
                                    <th class="text-right" style="width: 8%;">% Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $salesReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($row->row_number); ?></td>
                                    <td class="font-weight-bold"><?php echo e($row->category_name); ?></td>
                                    <td class="text-right"><?php echo e(number_format($row->quantity)); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($row->sales, 2)); ?></td>
                                    <td class="text-right">
                                        <?php if($row->discount > 0): ?>
                                            <span class="text-danger">$<?php echo e(number_format($row->discount, 2)); ?></span>
                                        <?php else: ?>
                                            $0.00
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right font-weight-bold text-primary">
                                        $<?php echo e(number_format($row->total, 2)); ?>

                                    </td>
                                    <td class="text-right text-success">$<?php echo e(number_format($row->tax, 2)); ?></td>
                                    <td class="text-right text-info">$<?php echo e(number_format($row->crv, 2)); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($row->cost, 2)); ?></td>
                                    <td class="text-right font-weight-bold text-success">
                                        $<?php echo e(number_format($row->profit, 2)); ?>

                                    </td>
                                    <td class="text-right"><?php echo e(number_format($row->margin, 1)); ?>%</td>
                                    <td class="text-right"><?php echo e(number_format($row->percentage_of_sales, 1)); ?>%</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No data available for the selected period</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                
                                <?php if($salesReport->isNotEmpty()): ?>
                                <tr class="bg-dark text-white font-weight-bold total-row">
                                    <td colspan="2" class="text-center">TOTAL</td>
                                    <td class="text-right"><?php echo e(number_format($totals['quantity'])); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($totals['sales'], 2)); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($totals['discount'], 2)); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($totals['total'], 2)); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($totals['tax'], 2)); ?></td>
                                    <td class="text-right">$<?php echo e(number_format($totals['crv'], 2)); ?></td>
                                    <td class="text-right">$0.00</td>
                                    <td class="text-right">$<?php echo e(number_format($totals['total'], 2)); ?></td>
                                    <td class="text-right">100%</td>
                                    <td class="text-right">100%</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-muted text-center no-print">
                    <small>
                        <i class="far fa-clock mr-1"></i>
                        Report generated on <?php echo e(now()->format('M d, Y H:i A')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="row mt-4 no-print">
        <div class="col-12 text-center">
            <button class="btn btn-success btn-lg" onclick="window.print()">
                <i class="fas fa-print mr-2"></i>Print Report
            </button>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
    .bg-gradient-primary {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
    }
    .bg-gradient-dark {
        background: linear-gradient(90deg, #5a5c69 0%, #373840 100%);
    }
    .table thead th {
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .card {
        border-radius: 10px;
    }
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075) !important;
    }

    /* Print Styles for A4 */
    @media print {
        /* Hide non-printable elements */
        .no-print {
            display: none !important;
        }

        /* Show print-only elements */
        .print-only {
            display: block !important;
        }

        /* A4 Page Setup - Landscape for better table fit */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 10pt;
        }

        /* Remove all unnecessary spacing */
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .row {
            margin: 0 !important;
        }

        .col-12 {
            padding: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }

        .card-body {
            padding: 0 !important;
        }

        /* Table styling for print */
        .table-responsive {
            overflow: visible !important;
        }

        #reportTable {
            width: 100% !important;
            margin: 0 !important;
            font-size: 9pt !important;
            border-collapse: collapse !important;
        }

        #reportTable thead th {
            background-color: #343a40 !important;
            color: white !important;
            padding: 8px 4px !important;
            border: 1px solid #000 !important;
            font-size: 9pt !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        #reportTable tbody td {
            padding: 6px 4px !important;
            border: 1px solid #ddd !important;
            font-size: 9pt !important;
        }

        /* FIXED: Total row styling for print */
        #reportTable tbody tr.total-row td {
            background-color: #343a40 !important;
            color: white !important;
            border: 1px solid #000 !important;
            font-weight: bold !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Keep colors in print */
        .text-danger {
            color: #dc3545 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .text-primary {
            color: #007bff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .text-success {
            color: #28a745 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .text-info {
            color: #17a2b8 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Remove badges in print - just show text */
        .badge {
            background: none !important;
            color: #000 !important;
            padding: 0 !important;
            border: none !important;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/reports/tax-crv.blade.php ENDPATH**/ ?>