<div class="form-group mb-4">
    <label for="formGroupExampleInput">Unique ID</label>
    <input type="text" name="unique_id" required value="<?php echo !empty($beacon)? $beacon->unique_id:''; ?>" class="form-control input-sm" placeholder='Unique ID' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Minor</label>
    <input type="text" name="minor" required value="<?php echo !empty($beacon)? $beacon->minor:''; ?>" class="form-control input-sm" placeholder='Minor' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Major</label>
    <input type="text" name="major" required value="<?php echo !empty($beacon)? $beacon->major:''; ?>" class="form-control input-sm" placeholder='Major' />
</div>


<div class="form-group mb-4">
    <label for="formGroupExampleInput">Ranging</label>
	<select name="ranging" required="" class="form-control input-sm">
		<option value="">--Select Type--</option>
		<option <?php echo !empty($beacon) && $beacon->ranging == 'immidiate' ? 'selected="selected"':''?> value="immidate">Immidate</option>
		<option <?php echo !empty($beacon) && $beacon->ranging == 'near' ? 'selected="selected"':''?> value="near">Near</option>
        <option <?php echo !empty($beacon) && $beacon->ranging == 'far' ? 'selected="selected"':''?> value="far">Far</option>
	</select>
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">UUID</label>
    <input type="text" name="UUID" required value="<?php echo !empty($beacon)? $beacon->UUID:''; ?>" class="form-control input-sm" placeholder='UUID' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Mac Address</label>
    <input type="text" name="mac_address" required value="<?php echo !empty($beacon)? $beacon->mac_address:''; ?>" class="form-control input-sm" placeholder='Mac Address' />
</div>
<?php if($role == 'shop'){ ?>
    <div class="form-group mb-4">
        <label for="formGroupExampleInput">Zone/Shop</label>
        <select name="zone_id" required="" class="form-control input-sm">
            <option value="">--Select Zone--</option>
            <?php foreach($zones as $zone){ ?>
                    <option <?php echo !empty($beacon) && $beacon->zone_id == $zone->id ? 'selected="selected"':''; ?> value="<?php echo e($zone->id); ?>"><?php echo e($zone->name.' | '.$zone->shop_name.' | '.$zone->shop); ?></option>
            <?php } ?>
        </select>
    </div>    
<?php }?>
<button type="submit" class="btn btn-primary btn-sm">Save</button>
<a href="<?php echo e(url('beacons')); ?>" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a><?php /**PATH /opt/lampp/htdocs/bmmlatest/resources/views/Beacons/formhtml.blade.php ENDPATH**/ ?>