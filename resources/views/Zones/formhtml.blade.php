<div class="form-group mb-4">
    <label for="formGroupExampleInput">Name</label>
    <input type="text" name="name" required value="<?php echo !empty($zone)? $zone->name:''; ?>" class="form-control input-sm" placeholder='Name' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Floor#</label>
    <input type="number" name="floor" required value="<?php echo !empty($zone)? $zone->floor:''; ?>" class="form-control input-sm" placeholder='Floor#' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Floor Name</label>
    <input type="text" name="floor_name" required value="<?php echo !empty($zone)? $zone->floor_name:''; ?>" class="form-control input-sm" placeholder='Floor Name' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Shop#</label>
    <input type="number" name="shop" required value="<?php echo !empty($zone)? $zone->shop:''; ?>" class="form-control input-sm" placeholder='Shop#' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Shop Name</label>
    <input type="text" name="shop_name" required value="<?php echo !empty($zone)? $zone->shop_name:''; ?>" class="form-control input-sm" placeholder='Shop Name' />
</div>

<?php $selected = !empty($zone) ? $zone->beacon_id:''; ?>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Check In</label>
    <select name="checkin" required="" class="form-control input-sm">
        <option value="">--Checkin Beacon--</option>
          <?php if(!empty($selectedBeacons)){ 
            foreach($selectedBeacons as $sb){ ?>
                <option <?php echo !empty($sb->checked_in) ? 'selected="selected"':''?> value="{{$sb->id}}">{{$sb->unique_id}}</option>
        <?php } 
            }
            foreach($beacons as $beacon){ ?>
            <option value="{{$beacon->id}}">{{$beacon->unique_id}}</option>
          <?php } ?>
    </select>
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Default Beacon</label>
    <select name="deafult_beacon" required="" class="form-control input-sm">
        <option value="">--Default Beacon--</option>
          <?php if(!empty($selectedBeacons)){ 
            foreach($selectedBeacons as $sb){ ?>
                <option <?php echo !empty($sb->selected) ? 'selected="selected"':''?> value="{{$sb->id}}">{{$sb->unique_id}}</option>
        <?php }
        }
         foreach($beacons as $beacon){ ?>
            <option value="{{$beacon->id}}">{{$beacon->unique_id}}</option>
          <?php } ?>
    </select>
</div>

<div class="form-group mb-4">
    
    <label for="formGroupExampleInput">Assign Beacon</label>
	<select id="beaconSelect" name="beacon_ids[]" required="" class="form-control search" multiple>
        <?php if(!empty($selectedBeacons)){ 
            foreach($selectedBeacons as $sb){ ?>
                <option selected="selected" value="{{$sb->id}}">{{$sb->unique_id}}</option>
        <?php } 
            }?>
	  <?php foreach($beacons as $beacon){ ?>
        <option value="{{$beacon->id}}">{{$beacon->unique_id}}</option>
	  <?php } ?>
	</select>
</div>


<button type="submit" class="btn btn-primary btn-sm">Save</button>
<a href="{{ url('zones') }}" class="btn btn-danger btn-icon-split">
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
        jq('select#beaconSelect').multiselect({
            columns: 2,
            placeholder: 'Select Beacons',
            search: true,
            searchOptions: {
                'default': 'Search Beacons'
            },
            selectAll: true
        });
    });
</script>