@extends('layouts.admin')

@section('title', __('messages.Edit_Point_Transaction'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.Edit_Point_Transaction') }} #{{ $pointTransaction->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('point-transactions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('point-transactions.update', $pointTransaction) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Current Transaction Details Card -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle"></i> {{ __('messages.Current_Transaction_Details') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>{{ __('messages.Original_User') }}:</strong><br>
                                                <span class="badge badge-info">{{ $pointTransaction->user->name ?? __('messages.Unknown_User') }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('messages.Original_Points') }}:</strong><br>
                                                <span class="badge badge-{{ $pointTransaction->type_of_transaction == 1 ? 'success' : 'danger' }}">
                                                    {{ $pointTransaction->type_of_transaction == 1 ? '+' : '-' }}{{ $pointTransaction->points }}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('messages.Created_By') }}:</strong><br>
                                                <span class="badge badge-secondary">{{ $pointTransaction->admin->name ?? __('messages.System') }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('messages.Created_At') }}:</strong><br>
                                                <small>{{ $pointTransaction->created_at->format('Y-m-d H:i:s') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Form Fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">{{ __('messages.User') }} <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">{{ __('messages.Select_User') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ (old('user_id', $pointTransaction->user_id) == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="current_points">{{ __('messages.Current_Points') }}</label>
                                    <input type="text" id="current_points" class="form-control" readonly 
                                           placeholder="{{ __('messages.Select_user_to_view_points') }}">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> {{ __('messages.Current_balance_info') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_of_transaction">{{ __('messages.Transaction_Type') }} <span class="text-danger">*</span></label>
                                    <select name="type_of_transaction" id="type_of_transaction" 
                                            class="form-control @error('type_of_transaction') is-invalid @enderror" required>
                                        <option value="">{{ __('messages.Select_Type') }}</option>
                                        <option value="1" {{ old('type_of_transaction', $pointTransaction->type_of_transaction) == '1' ? 'selected' : '' }}>
                                            <i class="fas fa-plus-circle"></i> {{ __('messages.Add_Points') }}
                                        </option>
                                        <option value="2" {{ old('type_of_transaction', $pointTransaction->type_of_transaction) == '2' ? 'selected' : '' }}>
                                            <i class="fas fa-minus-circle"></i> {{ __('messages.Withdraw_Points') }}
                                        </option>
                                    </select>
                                    @error('type_of_transaction')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points">{{ __('messages.Points') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="points" id="points" 
                                           class="form-control @error('points') is-invalid @enderror" 
                                           value="{{ old('points', $pointTransaction->points) }}" min="1" required>
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="points-warning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        {{ __('messages.Insufficient_points_warning') }}
                                    </small>
                                    <small class="form-text text-success" id="points-available" style="display: none;">
                                        <i class="fas fa-check-circle text-success"></i>
                                        {{ __('messages.Sufficient_points_available') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">{{ __('messages.Note') }}</label>
                                    <textarea name="note" id="note" rows="3" 
                                              class="form-control @error('note') is-invalid @enderror" 
                                              placeholder="{{ __('messages.Enter_note') }}">{{ old('note', $pointTransaction->note) }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Optional_note_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Alert -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i> {{ __('messages.Warning') }}</h5>
                                    {{ __('messages.Edit_point_transaction_warning') }}
                                    <hr>
                                    <ul class="mb-0">
                                        <li>{{ __('messages.Original_transaction_will_be_reversed') }}</li>
                                        <li>{{ __('messages.New_transaction_will_be_applied') }}</li>
                                        <li>{{ __('messages.User_balance_will_be_updated') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Impact Preview Card -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-calculator"></i> {{ __('messages.Transaction_Impact_Preview') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h6>{{ __('messages.Current_Balance') }}</h6>
                                                    <span class="badge badge-info badge-lg" id="preview-current">-</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h6>{{ __('messages.After_Reversal') }}</h6>
                                                    <span class="badge badge-warning badge-lg" id="preview-after-reversal">-</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h6>{{ __('messages.Final_Balance') }}</h6>
                                                    <span class="badge badge-success badge-lg" id="preview-final">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="text-center">
                                            <small class="text-muted" id="impact-description">
                                                {{ __('messages.Select_values_to_see_impact') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="save-btn">
                            <i class="fas fa-save"></i> {{ __('messages.Update') }}
                        </button>
                    
                        <a href="{{ route('point-transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                        </a>
                        
                        <div class="float-right">
                            <form method="POST" action="{{ route('point-transactions.destroy', $pointTransaction) }}" 
                                  style="display: inline-block;"
                                  onsubmit="return confirm('{{ __('messages.Are_you_sure_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> {{ __('messages.Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.badge-lg {
    font-size: 1.2em;
    padding: 0.6rem 1rem;
}

.card.bg-light {
    border: 1px solid #dee2e6;
}

.alert ul {
    padding-left: 20px;
}

#impact-description {
    font-style: italic;
}

.form-group label {
    font-weight: 600;
}

.card-header.bg-info {
    color: white !important;
}

.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}

@media (max-width: 768px) {
    .card-tools {
        margin-top: 10px;
    }
    
    .float-right {
        float: none !important;
        margin-top: 10px;
    }
}
</style>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Original transaction data
    const originalTransaction = {
        userId: {{ $pointTransaction->user_id }},
        points: {{ $pointTransaction->points }},
        type: {{ $pointTransaction->type_of_transaction }}
    };

    let currentUserPoints = 0;

    // Load current user's points on page load
    var initialUserId = $('#user_id').val();
    if (initialUserId) {
        loadUserPoints(initialUserId);
    }

    // Get user points when user is selected
    $('#user_id').on('change', function() {
        var userId = $(this).val();
        if (userId) {
            loadUserPoints(userId);
        } else {
            $('#current_points').val('');
            $('#points-warning').hide();
            $('#points-available').hide();
            resetPreview();
        }
    });

    function loadUserPoints(userId) {
        $.ajax({
            url: '{{ route("point-transactions.get-user-points") }}',
            method: 'GET',
            data: { user_id: userId },
            success: function(response) {
                currentUserPoints = parseInt(response.points) || 0;
                $('#current_points').val(currentUserPoints + ' {{ __("messages.Points") }}');
                checkPointsAvailability();
                updateImpactPreview();
            },
            error: function() {
                $('#current_points').val('{{ __("messages.Error_loading_points") }}');
                currentUserPoints = 0;
                resetPreview();
            }
        });
    }

    // Check points availability and update preview when values change
    $('#type_of_transaction, #points').on('change keyup', function() {
        checkPointsAvailability();
        updateImpactPreview();
    });

    function checkPointsAvailability() {
        var userId = parseInt($('#user_id').val()) || 0;
        var transactionType = parseInt($('#type_of_transaction').val()) || 0;
        var points = parseInt($('#points').val()) || 0;
        
        if (userId && transactionType == 2 && points > 0 && currentUserPoints >= 0) {
            // Calculate available points after reversing the original transaction
            var availablePoints = currentUserPoints;
            
            if (userId == originalTransaction.userId) {
                if (originalTransaction.type == 1) {
                    availablePoints += originalTransaction.points; // Add back originally added points
                } else {
                    availablePoints -= originalTransaction.points; // Remove back originally subtracted points
                }
            }
            
            if (points > availablePoints) {
                $('#points-warning').show();
                $('#points-available').hide();
                $('#points').addClass('is-invalid');
                $('#save-btn').prop('disabled', true);
            } else {
                $('#points-warning').hide();
                $('#points-available').show();
                $('#points').removeClass('is-invalid');
                $('#save-btn').prop('disabled', false);
            }
        } else {
            $('#points-warning').hide();
            $('#points-available').hide();
            $('#points').removeClass('is-invalid');
            $('#save-btn').prop('disabled', false);
        }
    }

    function updateImpactPreview() {
        var userId = parseInt($('#user_id').val()) || 0;
        var transactionType = parseInt($('#type_of_transaction').val()) || 0;
        var points = parseInt($('#points').val()) || 0;
        
        if (!userId || !transactionType || !points || currentUserPoints < 0) {
            resetPreview();
            return;
        }

        var currentBalance = currentUserPoints;
        var afterReversal = currentBalance;
        var finalBalance = currentBalance;
        
        // Calculate after reversing original transaction
        if (userId == originalTransaction.userId) {
            if (originalTransaction.type == 1) {
                afterReversal = currentBalance + originalTransaction.points; // Add back
            } else {
                afterReversal = currentBalance - originalTransaction.points; // Remove back
            }
        }
        
        // Calculate final balance after applying new transaction
        finalBalance = afterReversal;
        if (transactionType == 1) {
            finalBalance += points; // Add new points
        } else {
            finalBalance -= points; // Subtract new points
        }
        
        // Update preview
        $('#preview-current').text(currentBalance + ' {{ __("messages.Points") }}');
        $('#preview-after-reversal').text(afterReversal + ' {{ __("messages.Points") }}');
        $('#preview-final').text(finalBalance + ' {{ __("messages.Points") }}');
        
        // Update badges colors
        $('#preview-final').removeClass('badge-success badge-danger badge-warning');
        if (finalBalance >= currentBalance) {
            $('#preview-final').addClass('badge-success');
        } else if (finalBalance >= 0) {
            $('#preview-final').addClass('badge-warning');
        } else {
            $('#preview-final').addClass('badge-danger');
        }
        
        // Update description
        var changeAmount = finalBalance - currentBalance;
        var changeText = '';
        if (changeAmount > 0) {
            changeText = '{{ __("messages.Net_increase") }}: +' + changeAmount + ' {{ __("messages.Points") }}';
        } else if (changeAmount < 0) {
            changeText = '{{ __("messages.Net_decrease") }}: ' + changeAmount + ' {{ __("messages.Points") }}';
        } else {
            changeText = '{{ __("messages.No_net_change") }}';
        }
        
        $('#impact-description').html('<strong>' + changeText + '</strong>');
    }

    function resetPreview() {
        $('#preview-current').text('-');
        $('#preview-after-reversal').text('-');
        $('#preview-final').text('-');
        $('#impact-description').text('{{ __("messages.Select_values_to_see_impact") }}');
    }

    // Initialize Select2 if available
    if ($.fn.select2) {
        $('#user_id').select2({
            placeholder: '{{ __("messages.Select_User") }}',
            allowClear: true
        });
    }

    // Form validation
    $('form').on('submit', function(e) {
        var userId = $('#user_id').val();
        var transactionType = $('#type_of_transaction').val();
        var points = $('#points').val();
        
        if (!userId || !transactionType || !points) {
            e.preventDefault();
            alert('{{ __("messages.Please_fill_required_fields") }}');
            return false;
        }
        
        if ($('#points').hasClass('is-invalid')) {
            e.preventDefault();
            alert('{{ __("messages.Cannot_proceed_insufficient_points") }}');
            return false;
        }
        
        return confirm('{{ __("messages.Confirm_update_transaction") }}');
    });
});
</script>
@endsection