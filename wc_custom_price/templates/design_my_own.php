<?php
global $wpdb;
global  $woocommerce;
$table_name = $wpdb->prefix . 'product_configurator';
$min = $wpdb->get_var('SELECT MIN(min_width) FROM '.$table_name);
$max = $wpdb->get_var('SELECT MAX(min_width) FROM '.$table_name);

$currency =  get_woocommerce_currency_symbol();
?>
<div class="container_design_my_own">
<div class="can_wrapper">
	<div class="canvas_l">
	    <div class="canva_area"><canvas id="respondCanvas" width="100" height="100"></canvas></div>
		<div class="loading">
			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/loading.gif">
		</div>
		<div id="canvas_details"></div>
	</div>
	<div class="price_r">
		<h2>Price (<?php echo $currency; ?>)</h2>
		<hr/>
		<h2><?php echo $currency." "; ?><span id="price_initiate"></span></h2>
	</div>
</div>

<input type="hidden" name="min_width" value="<?php echo $min; ?>" id= "c_min_width">
<input type="hidden" name="max_width" value="<?php echo $max; ?>" id= "c_max_width">
<form name="steps" method="POST" id="con_steps">
			<ul class="tabs">
				<li class="tab-link current" data-tab="tab-1" id="tabli-1">Dimensions </li>
				<li class="tab-link" data-tab="tab-2" id="tabli-2">Range</li>
				<li class="tab-link" data-tab="tab-3" id="tabli-3">Amount of Doors</li>
				<li class="tab-link" data-tab="tab-4" id="tabli-4">Color</li>
				<li class="tab-link" data-tab="tab-5" id="tabli-5">Doors & Frame</li>
				<li class="tab-link" data-tab="tab-6" id="tabli-6">Door panel</li>
				<li class="tab-link" data-tab="tab-7" id="tabli-7">Liner</li>
				<li class="tab-link" data-tab="tab-8" id="tabli-8">Interior</li>
			</ul>
				
			<div id="tab-1" class="tab-content current">
				<div class="line_row">
					<label>Email</label>
					<input type="email" name="email" id="c_email" required="required">
				</div>
				<div class="line_row line_required">
					<label>floor to ceiling </label>
					<input type="number" name="floor_to_ceiling" required="required" id="c_floor_to_ceiling" value="2400">
					<input type="hidden" name="actual_door_height" id="actual_door_height" value="">
					<input type="hidden" name="door_width" id="door_width" value="">
				</div>	
				<div class="line_row line_required">
					<label>wall to wall</label>
					<input type="number" name="wall_to_wall" required="required" id="c_wall_to_wall " value="3600">
				</div>	
				<div class="line_row line_required">
					<label>Width </label>
					<input type="number" name="width" required="required" id="c_width" value="2000">
				</div>
				<div class="line_row">
					<button name="step1" type="button" value="Next" id="first_step">Next</button>
				</div>
			</div>

			<div id="tab-2" class="tab-content">
    				<?php $args = array( 'post_type' => 'Range', 'posts_per_page' => 10 );
					$loop = new WP_Query( $args );
					$row = 1;
					$first = '';
					while ( $loop->have_posts() ) : $loop->the_post();
						if($row == 1) {$first = 'checked';}else{$first = '';}
						$image = get_the_post_thumbnail_url($loop->ID);
						$price = get_post_meta(get_the_ID(),'_range_price',true);
						$desc = get_post_meta(get_the_ID(),'_range_desc',true);

						echo '<div class="ranges_box" id="'.get_the_ID().'"><label>';
						echo '<div class="range_desc">'.$desc.'</div>';
						echo '<input type="radio" class="ranges_box_radio" name="ranges_box" value="'.get_the_ID().'"'.$first.'>';
						echo '<img src="'.$image.'">
								<h2>'.get_the_title().'</h2>
								<div class="range_price"><h4>Price : '.$price.'</h4></div>';
						echo '</label></div>';
						$row++;		
				 	endwhile; wp_reset_postdata(); ?>
				<div class="line_row">
					<button  type="button" value="Next" id="back_at_step2" onclick="back_at_step2()">Back</button>
					<button  type="button" value="Next" id="continue_at_step2" onclick="continue_at_step2()">Continue</button>
				</div>				 	

			</div>
			
			<div id="tab-3" class="tab-content">
				<h3>How many doors do you want?</h3>
				<div id="no_of_doors"></div>
				<h3>What type of doors do you want?</h3>
				<div id="doors_design"></div>
				<div class="line_row">
					<button value="Next" type="button" id="back_at_step3" onclick="back_at_step3()">Back</button>
					<button value="Next" type="button" id="continue_at_step3" onclick="continue_at_step3()">Continue</button>
				</div>	
			</div>
			
			<div id="tab-4" class="tab-content">
				
				<div id="range_colors"></div>
				<div class="line_row">
					<button value="Next" type="button" id="back_at_step4" >Back</button>
					<button value="Next" type="button" id="continue_at_step4">Continue</button>
				</div>	
			</div>

			<div id="tab-5" class="tab-content">
				Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
			</div>
		</div>
</form>		