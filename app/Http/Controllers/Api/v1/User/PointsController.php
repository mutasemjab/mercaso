<?php
namespace App\Http\Controllers\Api\v1\User;
use App\Http\Controllers\Controller;
use App\Models\PointsTransaction;
use App\Models\PointTransaction;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\Responses;

class PointsController extends Controller
{
    use Responses;

    /**
     * Get points transactions history
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            $transactions = PointTransaction::with([
                'user:id,name,phone',
            ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

            // Filter by transaction type if provided
            if ($request->has('type_of_transaction') && $request->type_of_transaction != '') {
                $transactions->where('type_of_transaction', $request->type_of_transaction);
            }

            $transactions = $transactions->paginate(10);

            // Add transaction type labels
            $transactions->getCollection()->transform(function ($transaction) {
                $transaction->transaction_type_label = $transaction->type_of_transaction == 1 ? 'Added' : 'Withdrawn';
                return $transaction;
            });

            return $this->success_response(
                'Points transactions retrieved successfully',
                [
                    'current_points' => $user->points,
                    'transactions' => $transactions
                ]
            );

        } catch (\Exception $e) {
            return $this->error_response(
                'Failed to retrieve points transactions',
                ['error' => $e->getMessage()]
            );
        }
    }

  
}