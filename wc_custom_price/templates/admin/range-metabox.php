<?php
$post_id = get_the_ID();
$range_price = get_post_meta($post_id,'_range_price', TRUE);
$door_name = get_post_meta($post_id,'_range_door_name', TRUE);
$door_price = get_post_meta($post_id,'_range_door_price', TRUE);
$door_ratio = get_post_meta($post_id,'_range_door_ratio', TRUE);
$door_base_price = get_post_meta($post_id,'_range_door_base_price', TRUE);
$door_style = get_post_meta($post_id,'_range_door_style', TRUE);
$range_desc = get_post_meta($post_id,'_range_desc', TRUE);
?>
<table width="100%" class="meta_price_desc">
	<tr>
		<td>Price</td>
		<td><input type="text" name="price" required="true" value="<?php echo $range_price; ?>" ></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><textarea name="description"><?php echo $range_desc; ?></textarea></td>
	</tr>	
</table>
<hr/>
<h1>Doors Availabale Designs for this range</h1>
<hr/>
<table width="100%" id="customFields" class="fix_td">
	<tr>
		<th>Door Name</th>
		<th>Door Initial Price</th>
		<th>Door Ratio (%)</th>
		<th>Base Price(1%)</th>			
		<th>Door Style</th>
		<th>Action</th>
	</tr> 
<?php if (empty($door_style)) { ?> 
	<tr>
		<td><input type="text" name="door_name[]" required="true"></td>		
		<td><input type="text" name="door_price[]" required="true"></td>
		<td><input type="text" name="door_ratio[]" required="true"></td>
		<td><input type="text" name="door_base_price[]" required="true"></td>
		<td><label class="custom-file-upload"><input type="file" name="door_style[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td>
		<td><a href="#" class="add_row">+</a></td>
	</tr>     
<?php } else { 
foreach ($door_name as $key => $single_door) { 
		$image_url = wp_get_attachment_url($door_style[$key]);
		/*
		$arrs = explode(',',$door_ratio[$key]);
		print_r($arrs);

		$price = array();
		foreach ($arrs as $key => $arr) {
			$price[] = $arr * $door_price[$key] / 100;
		}
		print_r($price);
		echo '<hr/>';
		*/
		?>
	<tr>
		<td><input type="text" name="door_name[]" required="true" value="<?php echo $single_door;?>"></td>		
		<td><input type="text" name="door_price[]" required="true" value="<?php echo $door_price[$key];?>"></td>
		<td><input type="text" name="door_ratio[]" required="true" value="<?php echo $door_ratio[$key]; ?>"></td>
		<td><input type="text" name="door_base_price[]" required="true" value="<?php echo $door_base_price[$key]; ?>"></td>
		<td><label class="door_style_pic_lable">View Door <img src="<?php echo $image_url;?>" class="door_style_pic"></label>
			<input type="hidden" name="image[]" value="<?php echo $door_style[$key]; ?>"></td>
		<?php if($key == 0) { ?>
		<td><a href="#" class="add_row">+</a><a href="javascript:void(0);" class="remCF">-</a></td>
		<?php }else{ ?>
		<td><a href="javascript:void(0);" class="remCF">-</a></td>
		<?php } ?>
	</tr>

<?php } ?>



<?php } ?>	

</table>

<script type="text/javascript">
jQuery(document).ready(function($){
	$(".add_row").click(function(e){
		e.preventDefault();
		$("#customFields").append('<tr><td><input type="text" name="door_name[]" required="true"></td><td><input type="text" name="door_price[]" required="true"></td><td><input type="text" name="door_ratio[]" required="true"></td><td><input type="text" name="door_base_price[]" required="true"></td><td><label class="custom-file-upload"><input type="file" name="door_style[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td></td><td><a href="javascript:void(0);" class="remCF">-</a></td></tr>');
	});
    $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    $('#save_configuration').submit(function(e){
    	e.preventDefault();
    	$('.loading').show();
    	var formData = $(this).serializeArray(); 
    	//var formData = 12345; 
   		var data = {
			'action': 'save_my_configuration',
			'form_data': formData
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			$('.loading').hide();
			$('#res_con').html('<p>Configuration Updated</p>');
		});
    });
    /* Call Ajax for save values in database */
});
</script>
