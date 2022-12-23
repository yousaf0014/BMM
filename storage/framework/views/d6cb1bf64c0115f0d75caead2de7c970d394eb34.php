<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            User 
        </div>
        <div class="card-body">
            <?php if(session('status')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo e(session('status')); ?> You are logged in!
                    </div>
                <?php endif; ?>

                

                <div class="box-typical box-typical-padding">
                        
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" style="color: #000000;font-weight: bold;">Name:</label>
                        <div class="col-sm-10" style="color: #000000;">
                            <?php echo e($user->first_name.' '.$user->last_name); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" style="color: #000000;font-weight: bold;">Email:</label>
                        <div class="col-sm-10" style="color: #000000;">
                            <?php echo e($user->email); ?>

                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/bmmlatest/resources/views/Home/shopdashboard.blade.php ENDPATH**/ ?>