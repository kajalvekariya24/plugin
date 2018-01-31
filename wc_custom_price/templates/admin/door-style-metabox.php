<?php
	$post_id = get_the_ID();
	$range_door_style_pic_names = get_post_meta($post_id,'_range_door_style_pic_name', TRUE);
	$range_500_750 = get_post_meta($post_id,'_range_500_750', TRUE);
	$range_750_1000 = get_post_meta($post_id,'_range_750_1000', TRUE);
	$range_1000_1200 = get_post_meta($post_id,'_range_1000_1200', TRUE);
	$range_1200_1500 = get_post_meta($post_id,'_range_1200_1500', TRUE);
	$range_1500_2000 = get_post_meta($post_id,'_range_1500_2000', TRUE);
	$range_door_style_pic = get_post_meta($post_id,'_range_door_style_pic', TRUE);
?>


<table width="100%" id="customstyle" class="customstyle">
	<tr>
		<th>Door style</th>
		<th>500mm - 750mm</th>
		<th>750mm - 1000mm</th>
		<th>1000mm - 1200mm</th>
		<th>1200mm - 1500mm</th>
		<th>1500mm - 2000mm</th>			
	</tr> 
<?php if (empty($range_door_style_pic_names)) { ?> 
	<tr>
		<td><input type="text" name="door_style_pic_name[]" required="true"></td>		
		<td><input type="text" name="500-750[]" required="true"></td>
		<td><input type="text" name="750-1000[]" required="true"></td>
		<td><input type="text" name="1000-1200[]" required="true"></td>
		<td><input type="text" name="1200-1500[]" required="true"></td>
		<td><input type="text" name="1500-2000[]" required="true"></td>
		<td><label class="custom-file-upload"><input type="file" name="door_style_pic[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td>
		<td><a href="#" class="add_row_style">+</a></td>
	</tr>     
<?php } else { 
foreach ($range_door_style_pic_names as $key => $singlestyle) { 
		$image_url = wp_get_attachment_url($range_door_style_pic[$key]);	?>
	<tr>
		<td><input type="text" name="door_style_pic_name[]" required="true" value="<?php echo $singlestyle;?>" ></td>
		<td><input type="text" name="500-750[]" required="true" value="<?php echo $range_500_750[$key];?>"></td>
		<td><input type="text" name="750-1000[]" required="true" value="<?php echo $range_750_1000[$key];?>"></td>
		<td><input type="text" name="1000-1200[]" required="true" value="<?php echo $range_1000_1200[$key];?>"></td>
		<td><input type="text" name="1200-1500[]" required="true" value="<?php echo $range_1200_1500[$key];?>"></td>
		<td><input type="text" name="1500-2000[]" required="true" value="<?php echo $range_1500_2000[$key];?>"></td>
		<td><img src="<?php echo $image_url;?>" class="range_door_style_pic"><input type="hidden" name="image_style[]" value="<?php echo $range_door_style_pic[$key];?>"></td>

		<?php if($key == 0) { ?>
		<td><a href="#" class="add_row_style">+</a><a href="javascript:void(0);" class="remCF_style">-</a></td>
		<?php }else{ ?>
		<td><a href="javascript:void(0);" class="remCF_style">-</a></td>
		<?php } ?>
	</tr>  

<?php } ?>



<?php } ?>	

</table>

<script type="text/javascript">
jQuery(document).ready(function($){
	$(".add_row_style").click(function(e){
		e.preventDefault();
		$("#customstyle").append('<tr><td><input type="text" name="door_style_pic_name[]" required="true"></td><td><input type="text" name="500-750[]" required="true"></td><td><input type="text" name="750-1000[]" required="true"></td><td><input type="text" name="1000-1200[]" required="true"></td><td><input type="text" name="1200-1500[]" required="true"></td><td><input type="text" name="1500-2000[]" required="true"></td>		<td><label class="custom-file-upload"><input type="file" name="door_style_pic[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td><td><a href="javascript:void(0);" class="remCF_style">-</a></td></tr>');
	});
    $("#customstyle").on('click','.remCF_style',function(){
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
