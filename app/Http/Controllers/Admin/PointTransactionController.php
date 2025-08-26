<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PointTransaction::with(['user', 'admin']);

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by transaction type if provided
        if ($request->filled('type_of_transaction')) {
            $query->where('type_of_transaction', $request->type_of_transaction);
        }

        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pointTransactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::select('id', 'name')->get();

        return view('admin.pointTransactions.index', compact('pointTransactions', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', 'name')->get();
        return view('admin.pointTransactions.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'type_of_transaction' => 'required|in:1,2',
            'note' => 'nullable|string|max:1000',
        ]);

        // Check if user has enough points for withdrawal
        if ($request->type_of_transaction == 2) {
            $user = User::findOrFail($request->user_id);
            if ($user->points < $request->points) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['points' => __('messages.insufficient_points')]);
            }
        }

        $pointTransaction = PointTransaction::create([
            'user_id' => $request->user_id,
            'admin_id' => Auth::id(),
            'points' => $request->points,
            'type_of_transaction' => $request->type_of_transaction,
            'note' => $request->note,
        ]);

        // Update user points
        $user = User::findOrFail($request->user_id);
        if ($request->type_of_transaction == 1) {
            // Add points
            $user->increment('points', $request->points);
        } else {
            // Subtract points
            $user->decrement('points', $request->points);
        }

        return redirect()->route('point-transactions.index')
            ->with('success', __('messages.point_transaction_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PointTransaction $pointTransaction)
    {
        $pointTransaction->load(['user', 'admin']);
        return view('admin.pointTransactions.show', compact('pointTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointTransaction $pointTransaction)
    {
        $users = User::select('id', 'name')->get();
        return view('admin.pointTransactions.edit', compact('pointTransaction', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PointTransaction $pointTransaction)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'type_of_transaction' => 'required|in:1,2',
            'note' => 'nullable|string|max:1000',
        ]);

        // Reverse the old transaction effect on user points
        $user = User::findOrFail($pointTransaction->user_id);
        if ($pointTransaction->type_of_transaction == 1) {
            // Remove previously added points
            $user->decrement('points', $pointTransaction->points);
        } else {
            // Add back previously subtracted points
            $user->increment('points', $pointTransaction->points);
        }

        // Check if user has enough points for withdrawal (new transaction)
        if ($request->type_of_transaction == 2) {
            $targetUser = User::findOrFail($request->user_id);
            if ($targetUser->points < $request->points) {
                // Restore the original transaction effect
                if ($pointTransaction->type_of_transaction == 1) {
                    $user->increment('points', $pointTransaction->points);
                } else {
                    $user->decrement('points', $pointTransaction->points);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['points' => __('messages.insufficient_points')]);
            }
        }

        // Update the transaction
        $pointTransaction->update([
            'user_id' => $request->user_id,
            'admin_id' => Auth::id(),
            'points' => $request->points,
            'type_of_transaction' => $request->type_of_transaction,
            'note' => $request->note,
        ]);

        // Apply the new transaction effect on user points
        $newUser = User::findOrFail($request->user_id);
        if ($request->type_of_transaction == 1) {
            // Add points
            $newUser->increment('points', $request->points);
        } else {
            // Subtract points
            $newUser->decrement('points', $request->points);
        }

        return redirect()->route('point-transactions.index')
            ->with('success', __('messages.point_transaction_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointTransaction $pointTransaction)
    {
        // Reverse the transaction effect on user points
        $user = User::findOrFail($pointTransaction->user_id);
        if ($pointTransaction->type_of_transaction == 1) {
            // Remove previously added points
            $user->decrement('points', $pointTransaction->points);
        } else {
            // Add back previously subtracted points
            $user->increment('points', $pointTransaction->points);
        }

        $pointTransaction->delete();

        return redirect()->route('point-transactions.index')
            ->with('success', __('messages.point_transaction_deleted_successfully'));
    }

    /**
     * Get user's current points (AJAX)
     */
    public function getUserPoints(Request $request)
    {
        if ($request->ajax() && $request->filled('user_id')) {
            $user = User::findOrFail($request->user_id);
            return response()->json(['points' => $user->points]);
        }
        
        return response()->json(['points' => 0]);
    }
}