<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    public function index(Request $request)
    {
        $shops = Shop::all();
        $representatives = Representative::all();
        $shopId = $request->input('shop_id');
        $representativeId = $request->input('representative_id');
        $toDate = $request->input('to_date', date('Y-m-d')); // Default to today's date if not provided

        $reportData = [];

        if ($shopId && $representativeId) {
            $query = User::where('representative_id', $representativeId)
                ->where('created_at', '<=', $toDate)
                ->with(['addresses', 'wholeSales', 'representative']);

            $users = $query->get();

            foreach ($users as $user) {
                $reportData[] = [
                    'id' => $user->id ?? 'N/A',
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ];
            }
        }

        return view('reports.user_report', compact('shops','representatives', 'reportData', 'shopId', 'toDate', 'representativeId',));
    }

}
