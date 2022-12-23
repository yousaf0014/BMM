<?php $__env->startSection('content'); ?>
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Messages</h6>
                            
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12  layout-spacing">
                <form id="searchCompanies" name="searchSs" action="<?php echo e(url('/messages')); ?>">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">                                
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Search Form</h4>
                                </div>     
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">

                            <div class="row">
                                <div class="col-xl-5">
                                    <div class="form-group">
                                        <select name="zone" name="keyword" class="form-control form-control-lg" placeholder="Zone">
                                            <option value="">--Select Zone--</option>
                                            <?php foreach($zones as $z){ ?>
                                                <option value="<?php echo e($z->id); ?>" <?php echo  $z->id == $zone ? 'selected="selected"':''?>><?php echo e($z->name); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <input type="text" value="<?php echo e(isset($keyword) ? $keyword : ''); ?>" name="keyword" class="form-control form-control-lg" placeholder="keyword" />
                                    </div>
                                </div>
                                <div class="col-xl-1">
                                    <a href="javascript:{}" class="btn btn-primary btn-sm" onclick="$('#searchCompanies').submit();">
                                        
                                            <div class="icon-container">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </div>
                                        
                                    </a>
                                </div>
                                <div class="col-xl-2">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-add')): ?>
                                    <a href="<?php echo e(url('/messages/create/')); ?>" class="btn btn-success btn-sm">
                                        <span class="icon text-white-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                        </span>
                                        <span class="text">Add Message</span>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="container">
                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                    <form id="general-info" class="section general-info">
                        <div class="info">
                            <h6 class="">List</h6>
                                <div class="row">
                                    <div class="widget-content widget-content-area">
                                            <table class="table table-bordered table-hover mb-4">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">#</th>
                                                        <th class="text-center">Title</th>
                                                        <th class="text-center">Zone</th>
                                                        <th class="text-center">Beacon</th>
                                                        <th class="text-center">Message</th>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-show')): ?>
                                                        <th class="text-center">Pre-View</th>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-edit')): ?>
                                                        <th class="text-center">Edit</th>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-delete')): ?>
                                                        <th class="text-center">Delete</th>
                                                        <?php endif; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $counter = ($ads->currentPage()-1) * $ads->perPage();
                                                    ?>
                                                    <?php $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php $counter++; ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo e($counter); ?></td>
                                                            <td><span><?php echo e($row->title); ?></span></td>
                                                            <td><span ><?php echo e($row->zone->name); ?></span></td>
                                                            <td><span ><?php echo e(!empty($row->beacon->unique_id) ? $row->beacon->unique_id:''); ?></span></td>
                                                            <td><span ><?php echo e($row->message); ?></span></td>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-show')): ?>
                                                            <td class="text-center">
                                                                <a href="<?php echo e(url('/messages/'.$row->id.'/')); ?>" title="View">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                    </div>
                                                                    
                                                                </a>
                                                            </td>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-edit')): ?>
                                                            <td>
                                                                <a href="<?php echo e(url('/messages/'.$row->id.'/edit')); ?>" title="Edit Content" >
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('message-delete')): ?>
                                                            <td>
                                                                <?php echo Form::open(array('url' => '/messages/' . $row->id ,'id'=>'delete_'.$row->id,'class' => 'pull-right')); ?>

                                                                    <?php echo Form::hidden('_method', 'DELETE'); ?>

                                                                <?php echo Form::close(); ?>

                                                                <a onclick="show_alert('<?php echo e($row->id); ?>')" href="javascript:;" title="Delete">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php echo $ads->render(); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#myModal").on("show.bs.modal", function(e) {
            url =  $(e.relatedTarget).data('target-url');
            $.get( url , function( data ) {
                $(".modal-body").html(data);
            });

        });
    });
    function show_alert(id) {
        if(confirm('Are you sure? you want to delete.')){
            $('#delete_'+id).submit();
        }else{
            return false;
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/bmmlatest/resources/views/Messages/index.blade.php ENDPATH**/ ?>