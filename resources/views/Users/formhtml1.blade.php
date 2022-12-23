<div class="form-group mb-4">
    <label for="formGroupExampleInput">First Name</label>
    <input type="text" name="first_name" required value="{{$user->first_name}}" class="form-control input-sm" placeholder='First Name' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Last Name</label>
    <input type="text" name="last_name" required value="{{$user->last_name}}" class="form-control input-sm" placeholder='Last Name' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Email</label>
    <input type="text" name="email" required value="{{$user->email}}" class="form-control input-sm" placeholder='Email' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Contact</label>
    <input type="text" name="contact" required value="{{$user->contact}}" class="form-control input-sm" placeholder='Contact' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Password</label>
    <input type="text" name="password" value="" class="form-control input-sm" placeholder='Password' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Confirm Password</label>
    <input type="text" name="confirm-password" value="" class="form-control input-sm" placeholder='Confirm Password' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Roles</label>
    <select name="roles[]" class="form-control input-sm" id="roles">
        <?php foreach($roles as $role){ ?>
                <option value="{{$role}}" <?php echo in_array($role,$userRole) ? 'selected="selected"':''; ?> >{{$role}}</option>
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

<link href="{!! asset('plugins/bootstrap-multiselect/jquery.multiselect.css')!!}" rel="stylesheet">
<script src="{!! asset('js/libs/jquery-3.1.1.min.js')!!}"></script>
<script src="{!! asset('plugins/bootstrap-multiselect/jquery.multiselect.js')!!}"></script>
<script>
    jq = jQuery.noConflict( true );
    jq('document').ready(function(){
        /*jq('select#roles').multiselect({
            columns: 3,
            placeholder: 'Select Role',
            search: true,
            searchOptions: {
                'default': 'Search Role'
            },
            selectAll: true
        });*/
    });
</script>