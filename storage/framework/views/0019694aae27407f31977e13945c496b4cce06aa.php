<div class="form-group mb-4">
    <label for="formGroupExampleInput">Title</label>
    <input type="text" name="title" required value="<?php echo !empty($ad)? $ad->title:''; ?>" class="form-control input-sm" placeholder='Title' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Message</label>
    <input type="text" name="message" required value="<?php echo !empty($ad)? $ad->message:''; ?>" class="form-control input-sm" placeholder='Message' />
</div>

<div class="form-group mb-4">
    <?php $selected = !empty($ad) ? $ad->zone_id:''; ?>
    <label for="formGroupExampleInput">Assign Zone</label>
	<select name="zone_id" required="" class="form-control input-sm" onchange="getBeacon()" id="zone_id">
		<option value="">--Select Zone--</option>
		  <?php foreach($zones as $z){ ?>
            <option <?php echo $z->id == $selected ? 'selected="selected"':''?> value="<?php echo e($z->id); ?>"><?php echo e($z->name); ?></option>
		  <?php } ?>
	</select>
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Assign Beacon</label>
    <select name="beacon_id" required="" class="form-control input-sm"  id="beacon_id">
        <option value="">--Select Beacon--</option>
        <?php if(!empty($beacons)){
                foreach($beacons as $bc){?>
                    <option value="<?php echo e($bc->id); ?>" <?php echo  $bc->id == $ad->beacon_id ? 'selected="selected"':'';?> ><?php echo e($bc->unique_id); ?></option>
        <?php   }
            } ?>
    </select>
</div>


<button type="submit" class="btn btn-primary btn-sm">Save</button>
<a href="<?php echo e(url('messages')); ?>" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
    </span>
    <span class="text">Go Back</span>
</a>

<script type="text/javascript">
    function getBeacon(){
        $.ajax({
          method: "GET",
          url: "<?php echo e(url('ads/getBeacons')); ?>",
          data: { zone_id: $('#zone_id').val() }
        })
          .done(function( response ) {
                var selectedBeacon = '<?php echo e(!empty($ad->beacon_id)?$ad->beacon_id:0); ?>' * 1;
                var beaconArr = jQuery.parseJSON( response );
                htm = '<option value="">--Select Beacon--</option>';
                for (var key in beaconArr) {
                    var st = selectedBeacon == key ? 'selected="selected"':'';
                    htm += '<option value="'+key+'"'+st+' >'+beaconArr[key]+'</option>';
                }
                $('#beacon_id').html();
                $('#beacon_id').html(htm);

          });
    }
</script><?php /**PATH /opt/lampp/htdocs/bmmlatest/resources/views/Messages/formhtml.blade.php ENDPATH**/ ?>