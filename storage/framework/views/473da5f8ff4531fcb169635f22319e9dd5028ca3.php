<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('noteVouchers.import')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label for="file">Upload Excel File</label>
        <input type="file" name="file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
</form>
<a href="<?php echo e(route('noteVouchers.import.sample')); ?>" class="btn btn-secondary">Download Sample File</a>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/noteVouchers/import.blade.php ENDPATH**/ ?>