@extends('layouts.admin')
@section('title')
SMS Notifications
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center">Send SMS Notification</h3>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Success Message --}}
                        @if(session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                @if(session('success_count') || session('failed_count'))
                                    <br>
                                    <small>
                                        <strong>Success:</strong> {{ session('success_count', 0) }} |
                                        <strong>Failed:</strong> {{ session('failed_count', 0) }}
                                    </small>
                                @endif
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Validation Errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('sms.send') }}" method="post" id="sms-form">
                            @csrf

                            {{-- Send Type Selection --}}
                            <div class="form-group">
                                <label for="send_type"><strong>Send To</strong></label>
                                <select name="send_type" id="send_type" class="form-control @if($errors->has('send_type')) is-invalid @endif" required>
                                    <option value="">-- Select Option --</option>
                                    <option value="all" {{ old('send_type') == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="by_type" {{ old('send_type') == 'by_type' ? 'selected' : '' }}>Filter by User Type</option>
                                    <option value="selected" {{ old('send_type') == 'selected' ? 'selected' : '' }}>Select Specific Users</option>
                                </select>
                                @if($errors->has('send_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('send_type') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- User Type Filter (Conditional) --}}
                            <div class="form-group" id="user_type_group" style="display: none;">
                                <label for="user_type"><strong>User Type</strong></label>
                                <select name="user_type" id="user_type" class="form-control @if($errors->has('user_type')) is-invalid @endif">
                                    <option value="">-- Select User Type --</option>
                                    <option value="1" {{ old('user_type') == '1' ? 'selected' : '' }}>Regular Users</option>
                                    <option value="2" {{ old('user_type') == '2' ? 'selected' : '' }}>Wholesale Users</option>
                                </select>
                                @if($errors->has('user_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_type') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- Selected Users Multi-select (Conditional) --}}
                            <div class="form-group" id="selected_users_group" style="display: none;">
                                <label for="selected_users"><strong>Select Users</strong></label>
                                <select name="selected_users[]" id="selected_users" class="form-control select2-multiple @if($errors->has('selected_users')) is-invalid @endif" multiple="multiple">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, old('selected_users', [])) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->phone }}) - {{ $user->user_type == 1 ? 'Regular' : 'Wholesale' }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('selected_users'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('selected_users') }}</strong>
                                    </span>
                                @endif
                                <small class="form-text text-muted">
                                    Hold <kbd>Ctrl</kbd> (or <kbd>Cmd</kbd> on Mac) + Click to select multiple users
                                </small>
                            </div>

                            {{-- SMS Message --}}
                            <div class="form-group">
                                <label for="message"><strong>Message</strong></label>
                                <textarea name="message" id="message" rows="5" class="form-control @if($errors->has('message')) is-invalid @endif" placeholder="Enter SMS message (minimum 10, maximum 1600 characters)" maxlength="1600" required>{{ old('message') }}</textarea>
                                <small class="form-text text-muted">
                                    Character count: <span id="char_count">{{ strlen(old('message', '')) }}</span>/1600
                                </small>
                                @if($errors->has('message'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- Submit & Cancel Buttons --}}
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="fas fa-paper-plane"></i> Send SMS
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary waves-effect waves-light">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Initialize Select2 for multi-select
    $('#selected_users').select2({
        placeholder: 'Select users...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return 'No users found';
            }
        }
    });

    // Show/hide conditional fields based on send_type
    $('#send_type').on('change', function() {
        var sendType = $(this).val();

        // Hide all conditional groups
        $('#user_type_group').hide();
        $('#selected_users_group').hide();

        // Remove required attribute from all
        $('#user_type').prop('required', false);
        $('#selected_users').prop('required', false);

        // Show relevant group and make required
        if (sendType === 'by_type') {
            $('#user_type_group').show();
            $('#user_type').prop('required', true);
        } else if (sendType === 'selected') {
            $('#selected_users_group').show();
            $('#selected_users').prop('required', true);
        }
    });

    // Trigger change on page load to show/hide based on old() values
    $('#send_type').trigger('change');

    // Character counter for message
    $('#message').on('input', function() {
        var charCount = $(this).val().length;
        $('#char_count').text(charCount);

        // Warning color when approaching limit
        if (charCount > 1500) {
            $('#char_count').addClass('text-danger font-weight-bold');
        } else if (charCount > 1400) {
            $('#char_count').addClass('text-warning font-weight-bold');
            $('#char_count').removeClass('text-danger');
        } else {
            $('#char_count').removeClass('text-danger text-warning font-weight-bold');
        }
    });

    // Initialize character count on page load
    $('#message').trigger('input');

    // Form submission handling
    $('#sms-form').on('submit', function(e) {
        // Validate before submit
        var sendType = $('#send_type').val();
        var message = $('#message').val().trim();

        if (!sendType) {
            e.preventDefault();
            alert('Please select "Send To" option');
            return false;
        }

        if (message.length < 10) {
            e.preventDefault();
            alert('Message must be at least 10 characters');
            return false;
        }

        if (sendType === 'by_type' && !$('#user_type').val()) {
            e.preventDefault();
            alert('Please select user type');
            return false;
        }

        if (sendType === 'selected' && $('#selected_users').val().length === 0) {
            e.preventDefault();
            alert('Please select at least one user');
            return false;
        }

        // Show confirmation before sending
        var userCount = 0;
        if (sendType === 'all') {
            userCount = {{ count($users) }};
        } else if (sendType === 'by_type') {
            var userType = $('#user_type').val();
            var usersByType = {!! json_encode($users->groupBy('user_type')->map->count()) !!};
            userCount = usersByType[userType] || 0;
        } else if (sendType === 'selected') {
            userCount = $('#selected_users').val().length;
        }

        var confirmMsg = 'Are you sure you want to send SMS to ' + userCount + ' user(s)?\n\nMessage preview:\n"' + message.substring(0, 50) + (message.length > 50 ? '...' : '') + '"';

        if (!confirm(confirmMsg)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
