<?php $__env->startSection('content'); ?>

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Beacon/Form Edit</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <?php echo e(Html::ul($errors->all())); ?>

                        <?php echo Form::open(array('url' => url('beacons/update/'.$beacon->id),'method'=>'PUT','id'=>'add_beacon','name'=>'add_beacon','class'=>'form-horizontal')); ?>

                            <?php echo $__env->make('Beacons.formhtml', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/jquery.form.js?v=1')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/jquery.validate.min.js?v=1')); ?>"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        options = {
                rules: {
                    "name": {required:true},
                    "duration": {required:true,digits:true},
                    "resume":{required:true}
                },
                messages: {
                    "name": "Please enter Name",
                    "Duration": {required:"Please enter duration",digits:"Please enter an integer"},
                    "resume":"Please select"
                }
            };
            
            $('#add_beacon').validate( options );
    });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/bmmlatest/resources/views/Beacons/edit.blade.php ENDPATH**/ ?>