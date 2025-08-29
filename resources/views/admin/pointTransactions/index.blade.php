@extends('layouts.admin')

@section('title', __('messages.Point_Transactions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Point_Transactions') }}</h3>
                    <a href="{{ route('point-transactions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.Add_New') }}
                    </a>
                </div>
                 @can('point-transaction-table')
                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="{{ route('point-transactions.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('messages.User') }}</label>
                                    <select name="user_id" class="form-control">
                                        <option value="">{{ __('messages.All_Users') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('messages.Transaction_Type') }}</label>
                                    <select name="type_of_transaction" class="form-control">
                                        <option value="">{{ __('messages.All_Types') }}</option>
                                        <option value="1" {{ request('type_of_transaction') == '1' ? 'selected' : '' }}>
                                            {{ __('messages.Add_Points') }}
                                        </option>
                                        <option value="2" {{ request('type_of_transaction') == '2' ? 'selected' : '' }}>
                                            {{ __('messages.Withdraw_Points') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ __('messages.Date_From') }}</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ __('messages.Date_To') }}</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                        </button>
                                        <a href="{{ route('point-transactions.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('messages.Clear') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Results Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.User') }}</th>
                                    <th>{{ __('messages.Admin') }}</th>
                                    <th>{{ __('messages.Points') }}</th>
                                    <th>{{ __('messages.Type') }}</th>
                                    <th>{{ __('messages.Note') }}</th>
                                    <th>{{ __('messages.Date') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>
                                            @if($transaction->user)
                                                <span class="badge badge-info">{{ $transaction->user->name }}</span>
                                            @else
                                                <span class="text-muted">{{ __('messages.Unknown_User') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->admin)
                                                {{ $transaction->admin->name }}
                                            @else
                                                <span class="text-muted">{{ __('messages.System') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->type_of_transaction == 1 ? 'success' : 'danger' }}">
                                                {{ $transaction->type_of_transaction == 1 ? '+' : '-' }}{{ $transaction->points }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->type_of_transaction == 1)
                                                <span class="badge badge-success">{{ __('messages.Add_Points') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('messages.Withdraw_Points') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->note)
                                                <span title="{{ $transaction->note }}">
                                                    {{ Str::limit($transaction->note, 50) }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('messages.No_Note') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @can('point-transaction-edit')
                                                <a href="{{ route('point-transactions.edit', $transaction) }}" 
                                                   class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('point-transaction-delete')
                                                <form method="POST" action="{{ route('point-transactions.destroy', $transaction) }}" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('{{ __('messages.Are_you_sure') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.Delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            {{ __('messages.No_point_transactions_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $pointTransactions->appends(request()->query())->links() }}
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

