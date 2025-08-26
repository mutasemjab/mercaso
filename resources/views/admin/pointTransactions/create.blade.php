@extends('layouts.admin')

@section('title', __('messages.Add_Point_Transaction'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.Add_Point_Transaction') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('point-transactions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('point-transactions.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">{{ __('messages.User') }} <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">{{ __('messages.Select_User') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                        <option value="1" {{ old('type_of_transaction') == '1' ? 'selected' : '' }}>
                                            {{ __('messages.Add_Points') }}
                                        </option>
                                        <option value="2" {{ old('type_of_transaction') == '2' ? 'selected' : '' }}>
                                            {{ __('messages.Withdraw_Points') }}
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
                                           value="{{ old('points') }}" min="1" required>
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="points-warning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        {{ __('messages.Insufficient_points_warning') }}
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
                                              placeholder="{{ __('messages.Enter_note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('messages.Note') }}:</strong>
                                    {{ __('messages.Point_transaction_note') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.Save') }}
                        </button>
                        <a href="{{ route('point-transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Get user points when user is selected
    $('#user_id').on('change', function() {
        var userId = $(this).val();
        if (userId) {
            $.ajax({
                url: '{{ route("point-transactions.get-user-points") }}',
                method: 'GET',
                data: { user_id: userId },
                success: function(response) {
                    $('#current_points').val(response.points + ' {{ __("messages.Points") }}');
                    checkPointsAvailability();
                },
                error: function() {
                    $('#current_points').val('{{ __("messages.Error_loading_points") }}');
                }
            });
        } else {
            $('#current_points').val('');
            $('#points-warning').hide();
        }
    });

    // Check points availability when transaction type or points change
    $('#type_of_transaction, #points').on('change keyup', function() {
        checkPointsAvailability();
    });

    function checkPointsAvailability() {
        var userId = $('#user_id').val();
        var transactionType = $('#type_of_transaction').val();
        var points = parseInt($('#points').val()) || 0;
        var currentPointsText = $('#current_points').val();
        
        if (userId && transactionType == '2' && points > 0 && currentPointsText) {
            var currentPoints = parseInt(currentPointsText.match(/\d+/)) || 0;
            
            if (points > currentPoints) {
                $('#points-warning').show();
                $('#points').addClass('is-invalid');
            } else {
                $('#points-warning').hide();
                $('#points').removeClass('is-invalid');
            }
        } else {
            $('#points-warning').hide();
            $('#points').removeClass('is-invalid');
        }
    }

    // Initialize Select2 if available
    if ($.fn.select2) {
        $('#user_id').select2({
            placeholder: '{{ __("messages.Select_User") }}',
            allowClear: true
        });
    }
});
</script>
@endsection