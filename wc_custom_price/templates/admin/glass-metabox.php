<?php
$post_id = get_the_ID();
$glass_name = get_post_meta($post_id,'_range_glass_name', TRUE);
$glass_price = get_post_meta($post_id,'_range_glass_price', TRUE);
$glass_style = get_post_meta($post_id,'_range_glass_style', TRUE);
?>

<table width="100%" id="customFieldsgls" class="fix_td">
	<tr>
		<th>Glass Name</th>
		<th>Glass Price</th>
		<th>Glass Style</th>
		<th>Action</th>
	</tr> 
<?php if (empty($glass_name)) { ?> 
	<tr>
		<td><input type="text" name="glass_name[]" required="true"></td>		
		<td><input type="text" name="glass_price[]" required="true"></td>
		<td><label class="custom-file-upload"><input type="file" name="glass_style[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td>
		<td><a href="#" class="add_row_gls">+</a></td>
	</tr>     
<?php } else { 
foreach ($glass_name as $key => $single_Glass) { 
		$image_url = wp_get_attachment_url($glass_style[$key]);
		/*
		$arrs = explode(',',$glass_ratio[$key]);
		print_r($arrs);

		$price = array();
		foreach ($arrs as $key => $arr) {
			$price[] = $arr * $glass_price[$key] / 100;
		}
		print_r($price);
		echo '<hr/>';
		*/
		?>
	<tr>
		<td><input type="text" name="glass_name[]" required="true" value="<?php echo $single_Glass;?>"></td>
		<td><input type="text" name="glass_price[]" required="true" value="<?php echo $glass_price[$key];?>"></td>
		<td><img src="<?php echo $image_url;?>" class="glass_style_pic" height="30" width="30">
			<input type="hidden" name="glass_image[]" value="<?php echo $glass_style[$key]; ?>"></td>
		<?php if($key == 0) { ?>
		<td><a href="#" class="add_row_gls">+</a><a href="javascript:void(0);" class="remCFgls">-</a></td>
		<?php }else{ ?>
		<td><a href="javascript:void(0);" class="remCFgls">-</a></td>
		<?php } ?>
	</tr>

<?php } ?>



<?php } ?>	

</table>

<script type="text/javascript">
jQuery(document).ready(function($){
	$(".add_row_gls").click(function(e){
		e.preventDefault();
		$("#customFieldsgls").append('<tr><td><input type="text" name="glass_name[]" required="true"></td><td><input type="text" name="glass_price[]" required="true"></td><td><label class="custom-file-upload"><input type="file" name="glass_style[]" required="true" accept="image/*"><span class="dashicons dashicons-upload"></span></label></td></td><td><a href="javascript:void(0);" class="remCFgls">-</a></td></tr>');
	});
    $("#customFieldsgls").on('click','.remCFgls',function(){
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
