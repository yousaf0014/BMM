@extends('layouts.admin.app')
@section('content')

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>User / Form Add</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => 'users/storeBuilding/'.$user->id,'id'=>'add_user','name'=>'add_user','class'=>'form-horizontal','enctype'=>'multipart/form-data')) !!}
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">User Name</label>
                                <label><?php echo $user->first_name.' '.$user->last_name;?></label>
                            </div>
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Buildings</label>
                                <select name="buildings[]" class="form-control input-sm" id="buildings" multiple="multiple">
                                    <?php foreach($buildings as $build){ 

                                        ?>
                                            <?php $buser = !empty($build->user->first_name) ? ' ('.$build->user->first_name.' '.$build->user->last_name.' )':'' ?>
                                            <option <?php echo in_array($build->id,$currentBuildings) ? 'selected="selected"':'';?> value="{{$build->id}}">{{$build->name.$buser}} <?php echo $build->id == $userBuilding ? ' | Current viewing':'';?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            <a href="{{ url('users') }}" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                    
                                </span>
                                <span class="text">Go Back</span>
                            </a>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
@endsection
@section('scripts')
<link href="{!! asset('plugins/bootstrap-multiselect/jquery.multiselect.css')!!}" rel="stylesheet">
<script src="{!! asset('js/libs/jquery-3.1.1.min.js')!!}"></script>
<script src="{!! asset('plugins/bootstrap-multiselect/jquery.multiselect.js')!!}"></script>
<script>
    jq = jQuery.noConflict( true );
    jq('document').ready(function(){
        jq('select#buildings').multiselect({
            columns: 2,
            placeholder: 'Select Building',
            search: true,
            searchOptions: {
                'default': 'Search Building'
            },
            selectAll: true
        });
    });
</script>
@endsection