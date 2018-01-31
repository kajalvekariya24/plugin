<?php
$post_id = get_the_ID();
$color_name = get_post_meta($post_id,'_range_color_name', TRUE);
$color_price = get_post_meta($post_id,'_range_color_price', TRUE);
$color_style = get_post_meta($post_id,'_range_color_style', TRUE);
?>

<table width="100%" id="customFieldsclr" class="fix_td">
	<tr>
		<th>Color Name</th>
		<th>Color Price</th>
		<th>Color Style</th>
		<th>Action</th>
	</tr> 
<?php if (empty($color_name)) { ?> 
	<tr>
		<td><input type="text" name="color_name[]" required="true"></td>		
		<td><input type="text" name="color_price[]" required="true"></td>
		<td><label class="custom-file-upload"><input type="file" name="color_style[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td>
		<td><a href="#" class="add_row_clr">+</a></td>
	</tr>     
<?php } else { 
foreach ($color_name as $key => $single_color) { 
		$image_url = wp_get_attachment_url($color_style[$key]);
		/*
		$arrs = explode(',',$color_ratio[$key]);
		print_r($arrs);

		$price = array();
		foreach ($arrs as $key => $arr) {
			$price[] = $arr * $color_price[$key] / 100;
		}
		print_r($price);
		echo '<hr/>';
		*/
		?>
	<tr>
		<td><input type="text" name="color_name[]" required="true" value="<?php echo $single_color;?>"></td>
		<td><input type="text" name="color_price[]" required="true" value="<?php echo $color_price[$key];?>"></td>
		<td><img src="<?php echo $image_url;?>" class="color_style_pic" height="30" width="30">
			<input type="hidden" name="color_image[]" value="<?php echo $color_style[$key]; ?>"></td>
		<?php if($key == 0) { ?>
		<td><a href="#" class="add_row_clr">+</a><a href="javascript:void(0);" class="remCFclr">-</a></td>
		<?php }else{ ?>
		<td><a href="javascript:void(0);" class="remCFclr">-</a></td>
		<?php } ?>
	</tr>

<?php } ?>



<?php } ?>	

</table>

<script type="text/javascript">
jQuery(document).ready(function($){
	$(".add_row_clr").click(function(e){
		e.preventDefault();
		$("#customFieldsclr").append('<tr><td><input type="text" name="color_name[]" required="true"></td><td><input type="text" name="color_price[]" required="true"></td><td><label class="custom-file-upload"><input type="file" name="color_style[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td></td><td><a href="javascript:void(0);" class="remCFclr">-</a></td></tr>');
	});
    $("#customFieldsclr").on('click','.remCFclr',function(){
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
