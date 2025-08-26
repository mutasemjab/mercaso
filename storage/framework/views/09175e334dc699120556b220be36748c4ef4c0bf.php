

<?php $__env->startSection('title', __('messages.Add_Point_Transaction')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.Add_Point_Transaction')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('point-transactions.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back')); ?>

                        </a>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('point-transactions.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id"><?php echo e(__('messages.User')); ?> <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value=""><?php echo e(__('messages.Select_User')); ?></option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                                <?php echo e($user->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="current_points"><?php echo e(__('messages.Current_Points')); ?></label>
                                    <input type="text" id="current_points" class="form-control" readonly 
                                           placeholder="<?php echo e(__('messages.Select_user_to_view_points')); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_of_transaction"><?php echo e(__('messages.Transaction_Type')); ?> <span class="text-danger">*</span></label>
                                    <select name="type_of_transaction" id="type_of_transaction" 
                                            class="form-control <?php $__errorArgs = ['type_of_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value=""><?php echo e(__('messages.Select_Type')); ?></option>
                                        <option value="1" <?php echo e(old('type_of_transaction') == '1' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.Add_Points')); ?>

                                        </option>
                                        <option value="2" <?php echo e(old('type_of_transaction') == '2' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.Withdraw_Points')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['type_of_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points"><?php echo e(__('messages.Points')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" name="points" id="points" 
                                           class="form-control <?php $__errorArgs = ['points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('points')); ?>" min="1" required>
                                    <?php $__errorArgs = ['points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted" id="points-warning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        <?php echo e(__('messages.Insufficient_points_warning')); ?>

                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note"><?php echo e(__('messages.Note')); ?></label>
                                    <textarea name="note" id="note" rows="3" 
                                              class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              placeholder="<?php echo e(__('messages.Enter_note')); ?>"><?php echo e(old('note')); ?></textarea>
                                    <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong><?php echo e(__('messages.Note')); ?>:</strong>
                                    <?php echo e(__('messages.Point_transaction_note')); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.Save')); ?>

                        </button>
                        <a href="<?php echo e(route('point-transactions.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> <?php echo e(__('messages.Cancel')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    // Get user points when user is selected
    $('#user_id').on('change', function() {
        var userId = $(this).val();
        if (userId) {
            $.ajax({
                url: '<?php echo e(route("point-transactions.get-user-points")); ?>',
                method: 'GET',
                data: { user_id: userId },
                success: function(response) {
                    $('#current_points').val(response.points + ' <?php echo e(__("messages.Points")); ?>');
                    checkPointsAvailability();
                },
                error: function() {
                    $('#current_points').val('<?php echo e(__("messages.Error_loading_points")); ?>');
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
            placeholder: '<?php echo e(__("messages.Select_User")); ?>',
            allowClear: true
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/pointTransactions/create.blade.php ENDPATH**/ ?>