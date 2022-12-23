<div class="form-group mb-4">
    <label for="formGroupExampleInput">Name</label>
    <input type="text" name="name" required value="<?php echo !empty($building)? $building->name:''; ?>" class="form-control input-sm" placeholder='Name' />
</div>


<div class="form-group mb-4">
    <label for="formGroupExampleInput">Details</label>
    <input type="text" name="detail" value="<?php echo !empty($building)? $building->description:''; ?>" class="form-control input-sm" placeholder='Details' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Address</label>
    <input type="text" name="address" value="<?php echo !empty($building)? $building->address:''; ?>" class="form-control input-sm" placeholder='Address' />
</div>

<?php $selected = !empty($building) ? $building->user_id:''; ?>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Client</label>
    <select name="user_id" class="form-control input-sm">
        <option value="">--Select Client--</option>
          <?php
            foreach($users as $usr){ ?>
            <option <?php echo $selected == $usr->id ? 'selected="selected"':''; ?> value="{{$usr->id}}">{{$usr->first_name.' '.$usr->last_name}}</option>
          <?php } ?>
    </select>
</div>

<button type="submit" class="btn btn-primary btn-sm">Save</button>
<a href="{{ url('buildings') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a>
