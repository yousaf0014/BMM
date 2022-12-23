@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Name</label>
    <input type="text" name="name" required value="" class="form-control input-sm" placeholder='Name' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Permission</label>
    <select name="permission[]" id="permissions" class="form-control input-sm" multiple="">
        <?php foreach($permission as $permission){ ?>
                <option value="{{$permission->id}}">{{$permission->name}}</option>
        <?php } ?>
    </select>
</div>
<button type="submit" class="btn btn-primary btn-sm">Save</button>
<a href="{{ url('roles') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a>
<link href="{!! asset('plugins/bootstrap-multiselect/jquery.multiselect.css')!!}" rel="stylesheet">
<script src="{!! asset('js/libs/jquery-3.1.1.min.js')!!}"></script>
<script src="{!! asset('plugins/bootstrap-multiselect/jquery.multiselect.js')!!}"></script>
<script>
    jq = jQuery.noConflict( true );
    jq('document').ready(function(){
        jq('select#permissions').multiselect({
            columns: 2,
            placeholder: 'Select Permissions',
            search: true,
            searchOptions: {
                'default': 'Search Permissions'
            },
            selectAll: true
        });
    });
</script>