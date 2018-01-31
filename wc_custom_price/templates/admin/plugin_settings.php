<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'product_configurator';
$recoreds = $wpdb->get_results('SELECT * FROM '.$table_name, ARRAY_A );
?>
<div class="wrap">
<h1>Wardrobe Configurator Settings </h1><hr/>
<div class="loading">
	<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/loading.gif">
</div>
<form name="save_configuration" id="save_configuration" method="POST">
<table class="wp-list-table widefat fixed striped posts" id="customFields">
	<tr>
		<th>Min Width</th>
		<th>Max Width</th>
		<th>Doors</th>
		<th>Min Doors</th>
		<th>Max Doors</th>
		<th>Initial Price</th>
		<th></th>
	</tr>
<?php
if(!empty($recoreds)){
		foreach ($recoreds as $key => $recored) {
			echo '<tr>
			<td><input type="number" name="min_width[]" value="'.$recored[min_width] .'" required></td>
				<td><input type="number" name="max_width[]" value="'.$recored[max_width] .'" required></td>
				<td><input type="number" name="default_doors[]" value="'.$recored[default_doors] .'" required></td>
				<td><input type="number" name="min_doors[]" value="'.$recored[min_doors] .'" required></td>
				<td><input type="number" name="max_doors[]" value="'.$recored[max_doors] .'" required></td>
				<td><input type="number" name="initial_price[]" value="'.$recored[initial_price] .'" required></td>';

			if($key == 0){
			echo '<td><a href="javascript:void(0);" class="add_row"><span class="dashicons dashicons-plus"></span> Add</a></td>';
			}else{
			echo '<td><a href="javascript:void(0);" class="remCF"><span class="dashicons dashicons-minus"></span> Remove</a></td>';
			}
			echo '</tr>';	
			
		} 
}else{
		echo '<tr>
			<td><input type="number" name="min_width[]" value="'.$recored[min_width] .'" required></td>
				<td><input type="number" name="max_width[]" value="'.$recored[max_width] .'" required></td>
				<td><input type="number" name="default_doors[]" value="'.$recored[default_doors] .'" required></td>
				<td><input type="number" name="min_doors[]" value="'.$recored[min_doors] .'" required></td>
				<td><input type="number" name="max_doors[]" value="'.$recored[max_doors] .'" required></td>
				<td><input type="number" name="initial_price[]" value="'.$recored[initial_price] .'" required></td>
				<td><a href="javascript:void(0);" class="add_row"><span class="dashicons dashicons-plus"></span> Add</a></td>
			</tr>';
}


?>	
</table>
<div class="tablenav bottom">
	<div id="res_con"></div>
	<input type="submit" class="button" name="submit" value="Save Configuration">
</div>
</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	$(".add_row").click(function(){
		$("#customFields").append('<tr><td><input type="number" name="min_width[]" required></td><td><input type="number" name="max_width[]" required></td><td><input type="number" name="default_doors[]" required></td><td><input type="number" name="min_doors[]" required></td><td><input type="number" name="max_doors[]" required></td><td><input type="number" name="initial_price[]" required></td><td><a href="javascript:void(0);" class="remCF"><span class="dashicons dashicons-minus"></span> Remove</a></td></tr>');
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
