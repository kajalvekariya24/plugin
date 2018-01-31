<?php
/**
 * Plugin Name: WooCommerce Custom Price
 * Plugin URI: http://aistechnolabs.com
 * Description: Woocommerce plugin for change product price depends on new added features, dimension for furniture based on formula.
 * Version: 1.0.0
 * Author: AIS Technolabs
 * Author URI: http://aistechnolabs.com
 * Developer: PJ Panchal
 * Developer URI: http://aistechnolabs.com
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * WC requires at least: 2.2
 * WC tested up to: 2.3
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/*--------------------------------------------*/
// Create table for save configurations 
/*--------------------------------------------*/

function create_plugin_database_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'product_configurator';
	$sql = "CREATE TABLE $table_name (
	id integer(9) unsigned NOT NULL AUTO_INCREMENT,
	min_width integer(10) NOT NULL ,
	max_width integer(10) NOT NULL ,
	default_doors integer(10) NOT NULL ,
	min_doors integer(10) NOT NULL ,
	max_doors integer(10) NOT NULL ,
	initial_price integer(10) NOT NULL ,
	currentdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (id));";

	$table_name = $wpdb->prefix . 'range_configurator';
	$sql = "CREATE TABLE $table_name (
	range_id integer(9) unsigned NOT NULL AUTO_INCREMENT,
	range_name varchar(255) NOT NULL ,
	range_price varchar(255) NOT NULL ,
	range_image varchar(255) NOT NULL ,
	currentdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (range_id));";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'create_plugin_database_table' );



/*--------------------------------------------*/
// Check if WooCommerce is active
/*--------------------------------------------*/

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    echo '<div class="notice notice-warning is-dismissible">
        <p>Woocommerce plugin should install and activate for make <strong>WooCommerce Custom Price</strong> plugin works :(  </p>
    </div>';
}

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/*--------------------------------------------*/
/* Load CSS and JS files */
/*--------------------------------------------*/

function su_load_scripts() {
    wp_enqueue_script('configuratior_js', plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery'), '0.1.0', true);
    wp_enqueue_style('configuratior_css', plugin_dir_url( __FILE__ ) . 'css/steps.css');

    wp_localize_script('configuratior_js', 'configuratior_ajax',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'su_load_scripts');

function admin_style() {
  wp_enqueue_style('configuratior_css', plugin_dir_url( __FILE__ ) . 'css/steps.css');
}
add_action('admin_enqueue_scripts', 'admin_style');


/*--------------------------------------------*/
/* Add Admin menu for save conditions */
/*--------------------------------------------*/

add_action( 'admin_menu', 'extra_post_info_menu' );

function extra_post_info_menu(){

	$page_title = 'WC Wardrobe Configurator';
	$menu_title = 'WC	 Configurator';
	$capability = 'manage_options';
	$menu_slug  = 'wardrobe-configurator';
	$function   = 'wardrobe_configurator_page';
	$icon_url   = 'dashicons-admin-settings';
	$position   = 30;

  	add_menu_page( $page_title,$menu_title,$capability,$menu_slug,$function,$icon_url, $position );
	
	$range_title = 'Range Configurator';
	$menu_title = 'Range Configurator';
	$capability = 'manage_options';
	$menu_slug  = 'wardrobe-configurator';
	$function   = 'range_configurator_page';
	$icon_url   = 'dashicons-admin-settings';
	$position   = 30;

	add_submenu_page( $menu_slug, $range_title, $menu_title, $capability, 'Range', $function);
}



function wardrobe_configurator_page(){
	include_once( 'templates/admin/plugin_settings.php');
}

function range_configurator_page(){
	include_once( 'templates/admin/range.php');
}

/*--------------------------------------------*/
// Add Shortcode for make steps 
/*--------------------------------------------*/
add_shortcode('create_my_own_wardrobe', 'create_my_own_wardrobe');
function create_my_own_wardrobe() {
	include_once( 'templates/design_my_own.php');
}


/*--------------------------------------------*/
// SAVE CONFIGURATION OF ADMIN SETTINGS 
/*--------------------------------------------*/
add_action('wp_ajax_nopriv_save_my_configuration', 'save_my_configuration');
add_action( 'wp_ajax_save_my_configuration', 'save_my_configuration' );
function save_my_configuration() {
	$all_datas = $_POST['form_data'];
	global $wpdb;
	$table_name = $wpdb->prefix . 'product_configurator';
	$delete = $wpdb->query("TRUNCATE TABLE $table_name");

	$min_width = array();
	$max_width = array();
	$default_doors = array();
	$min_doors = array();
	$max_doors = array();

	if($all_datas){
		foreach ($all_datas as $all_data) {
			if($all_data['name'] ==  'min_width[]') $min_width[] = $all_data['value'];
			if($all_data['name'] ==  'max_width[]') $max_width[] = $all_data['value'];
			if($all_data['name'] ==  'default_doors[]') $default_doors[] = $all_data['value'];
			if($all_data['name'] ==  'min_doors[]') $min_doors[] = $all_data['value'];
			if($all_data['name'] ==  'max_doors[]') $max_doors[] = $all_data['value'];
			if($all_data['name'] ==  'initial_price[]') $initial_price[] = $all_data['value'];
		}
	}

	foreach ($min_width as $key => $single_width) {
		$table_name = $wpdb->prefix . 'product_configurator';
		$wpdb->insert($table_name, array(
		    'min_width' => $min_width[$key],
			'max_width' => $max_width[$key],
			'default_doors' => $default_doors[$key],
			'min_doors' => $min_doors[$key],
			'max_doors' => $max_doors[$key],
			'initial_price' => $initial_price[$key],
		));
	}
	//print_r($default_doors);
	wp_die(); // this is required to terminate immediately and return a proper response
}


/*--------------------------------------------*/
// GET SAVED CONFIGURATION 
/*--------------------------------------------*/

add_action('wp_ajax_nopriv_get_canvas_attrs', 'get_canvas_attrs');
add_action( 'wp_ajax_get_canvas_attrs', 'get_canvas_attrs' );
function get_canvas_attrs(){
	$width = $_POST['width'] + 1 ;
	global $wpdb;
	$table_name = $wpdb->prefix . 'product_configurator';
	$qry = 'SELECT * FROM '.$table_name.' WHERE '.$width.' BETWEEN '.$table_name.'.min_width AND '.$table_name.'.max_width ' ;
	$pars = $wpdb->get_row($qry);
	$pars->{"width"} = $width;

	echo json_encode($pars);
	wp_die();
}



add_action('wp_ajax_nopriv_get_door_width', 'get_door_width');
add_action( 'wp_ajax_get_door_width', 'get_door_width' );
function get_door_width(){
	$data = $_POST['selectedRange'];
	$select_door = $data['select_door'];
	$width = $data['width'];

	if($select_door == 2) $door_width = ($width + (1*35)) / $select_door;
	if($select_door == 3) $door_width = ($width + (2*35)) / $select_door;
	if($select_door == 4) $door_width = ($width + (2*35)) / $select_door;
	if($select_door == 5) $door_width = ($width + (4*35)) / $select_door;
	if($select_door == 6) $door_width = ($width + (5*35)) / $select_door;

	echo round($door_width) ;
	wp_die();
}



/*--------------------------------------------*/
// SAVE RANGE SETTINGS 
/*--------------------------------------------*/

add_action('wp_ajax_nopriv_save_my_range_configuration', 'save_my_range_configuration');
add_action( 'wp_ajax_save_my_range_configuration', 'save_my_range_configuration' );
function save_my_range_configuration(){
	
	$formdata = $_POST['formData'];

	$table_name = $wpdb->prefix . 'product_configurator';
		$wpdb->insert($table_name, array(
		    'min_width' => $min_width[$key],
			'max_width' => $max_width[$key],
			'default_doors' => $default_doors[$key],
			'min_doors' => $min_doors[$key],
			'max_doors' => $max_doors[$key],
			'initial_price' => $initial_price[$key],
		));

	echo json_encode($pars);
	wp_die();
}


/*--------------------------------------------*/
// GET RANGE SETTINGS / AVAILABLE DOORS 
/*--------------------------------------------*/

add_action('wp_ajax_nopriv_get_doors_and_style', 'get_doors_and_style');
add_action( 'wp_ajax_get_doors_and_style', 'get_doors_and_style' );
function get_doors_and_style(){
	
	$ranges_box_radio = $_POST['ranges_box_radio'];

	echo $ranges_box_radio;
	echo json_encode($pars);
	wp_die();
}


/* HAVE DOORS FROM SELECTED RANGE */
add_action('wp_ajax_nopriv_get_range_doors', 'get_range_doors');
add_action( 'wp_ajax_get_range_doors', 'get_range_doors' );
function get_range_doors(){
	$post_id = $_POST['selectedRange'];

	$rangeDoors = get_post_meta($post_id,'_range_door_style',TRUE);
	$door_ratio = get_post_meta($post_id,'_range_door_ratio',TRUE);
	$door_base_price = get_post_meta($post_id,'_range_door_base_price',TRUE);

	$doors = '';
	$i = 1;
	$doors .= '<div class="range_door_style">';
	 foreach ($rangeDoors as $key => $rangeDoor) {

	 	$singleRatio = $door_ratio[$key];
	 	$basePrice = $door_base_price[$key];

	 	$src  = wp_get_attachment_image_src($rangeDoor,'full');
	 	if($i==1){$check = 'checked';} else{$check = '';}
	 	$doors .= '<div class="doors_style"><label><input type="radio" name="doorstyle" data-price="'.$basePrice.'" data-ratio="'.$singleRatio.'" value="'.$rangeDoor.'" '.$check.'><img src="'.$src[0].'"></lable></div>';
	 	$i++;
	 }
	 $i = 1;
	 $doors .= "</div>";

	 $rangeDoorsPics = get_post_meta($post_id,'_range_door_style_pic',TRUE);
	 $doors .= '<div class="range_door_style_pic">';
	 if(!empty($rangeDoorsPics)){
	 	 $doors .= '<h3>Select type of Panel</h3>';
		 foreach ($rangeDoorsPics as $rangeDoorsPic) {
		 	$src  = wp_get_attachment_image_src($rangeDoorsPic,'full');
		 	if($i==1){$check = 'checked';} else{$check = '';}
		 	$doors .= '<div class="doors_style_pic"><label><input type="radio" name="doorstylepic" value="'.$rangeDoorsPic.'" '.$check.'><img src="'.$src[0].'"></lable></div>';
		 	$i++;
		 }
	 }
	 $doors .= "</div>";

	echo $doors;
	wp_die();
}



/* HAVE COLORS FROM SELECTED RANGE */
add_action('wp_ajax_nopriv_get_range_color', 'get_range_color');
add_action( 'wp_ajax_get_range_color', 'get_range_color' );
function get_range_color(){
	$post_id = $_POST['selectedRange'];

	$color_name = get_post_meta($post_id,'_range_color_name', TRUE);
	$color_price = get_post_meta($post_id,'_range_color_price', TRUE);
	$color_style = get_post_meta($post_id,'_range_color_style', TRUE);

	$colorGlass = '';
	$i = 1;
	$colorGlass .= '<div id="color_design">';

	if(!empty($color_style)){
		$colorGlass .= '<h3>Color</h3>';
		foreach ($color_style as $key => $s_color_style) {

		 	$colorprice = $color_price[$key];
		 	$colorstyle = $color_style[$key];

		 	$src  = wp_get_attachment_image_src($s_color_style,'full');
			if($i==1){$check = 'checked';} else{$check = '';}
		 	$colorGlass .= '<div class="doors_style_pic"><label><input type="radio" name="doorcolor" data-price="'.$colorprice.'" value="'.$s_color_style.'" '.$check.'><img src="'.$src[0].'"></lable></div>';
		 	$i++;
			# code...
		}
	}

	$glass_name = get_post_meta($post_id,'_range_glass_name', TRUE);
	$glass_price = get_post_meta($post_id,'_range_glass_price', TRUE);
	$glass_style = get_post_meta($post_id,'_range_glass_style', TRUE);	

	$i = 1;

	if(!empty($glass_style)){
		$colorGlass .= '<h3>Glass</h3>';
		foreach ($glass_style as $key => $s_glass_style) {

		 	$glsprice = $glass_price[$key];
		 	$glsstyle = $glass_style[$key];

		 	$src  = wp_get_attachment_image_src($s_glass_style,'full');
			if($i==1){$check = 'checked';} else{$check = '';}
		 	$colorGlass .= '<div class="doors_style_pic"><label><input type="radio" name="doorglass" data-price="'.$glsprice.'" value="'.$s_glass_style.'" '.$check.'><img src="'.$src[0].'"></lable></div>';
		 	$i++;
			# code...
		}
	}
	 
	 $colorGlass .= "</div>";

	echo $colorGlass;
	wp_die();
}

/* HAVE PRICE ACCORDING TO RANGE SELECTION  */
add_action('wp_ajax_nopriv_get_range_price', 'get_range_price');
add_action( 'wp_ajax_get_range_price', 'get_range_price' );
function get_range_price(){
	$post_id = $_POST['selectedRange'];
	$rangePrice = get_post_meta($post_id,'_range_price',TRUE);
	echo $rangePrice;
	wp_die();
}



add_action( 'post_edit_form_tag', 'custom_edit_form_tag' );
/**
 * Callback for WordPress 'post_edit_form_tag' action.
 * 
 * Append enctype - multipart/form-data and encoding - multipart/form-data
 * to allow image uploads for post type 'post'.
 * 
 * @global WP_Post $post
 */
function custom_edit_form_tag() {

    global $post;

    if ( $post && 'post' === $post->post_type ) {
        printf( ' enctype="multipart/form-data" encoding="multipart/form-data" ' );
    }

}
add_action('post_edit_form_tag', 'add_post_enctype');

function add_post_enctype() {
    echo ' enctype="multipart/form-data"';
}
/*--------------------------------------------*/
// REGISTER RANGE POST TYPE AND META 
/*--------------------------------------------*/

add_action('init', 'range_register');
function range_register() {
	$labels = array(
		'name' => _x('Ranges', 'post type general name'),
		'singular_name' => _x('Range', 'post type singular name'),
		'add_new' => _x('Add New', 'review'),
		'add_new_item' => __('Add New Range'),
		'edit_item' => __('Edit Range'),
		'new_item' => __('New Range'),
		'view_item' => __('View Range'),
		'search_items' => __('Search Range'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-images-alt',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail')
	  ); 
	register_post_type( 'range' , $args );
}

function wporg_add_custom_box()
{
    $screens = ['Range', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id',           // Unique ID
            'Doors Availabale Designs for this range',  // Box title
            'wporg_custom_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'wporg_add_custom_box');


function wporg_custom_box_html($post)
{
	include_once( 'templates/admin/range-metabox.php');
    ?>
    <hr/>

    <?php
}

function door_style_add_custom_box()
{
    $screens = ['Range', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'door_style_box_id',           // Unique ID
            'Doors style for this range',  // Box title
            'door_style_custom_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'door_style_add_custom_box');


function door_style_custom_box_html($post)
{
	include_once( 'templates/admin/door-style-metabox.php');
    ?>
    <hr/>

    <?php
}


function color_meta_box()
{
    $screens = ['Range', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'color_meta_box_id',           // Unique ID
            'Colors for this range',  // Box title
            'color_meta_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'color_meta_box');


function color_meta_box_html($post)
{
	include_once( 'templates/admin/color-metabox.php');
    ?>
    <hr/>

    <?php
}



function glass_meta_box()
{
    $screens = ['Range', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'glass_meta_box_id',           // Unique ID
            'Glass for this range',  // Box title
            'glass_meta_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'glass_meta_box');


function glass_meta_box_html($post)
{
	include_once( 'templates/admin/glass-metabox.php');
    echo '<hr/>';
}


function wporg_save_postdata($post_id)
{
	$exist_styles = $_POST['image'];
	
	$post_id = get_the_ID();
	$door_style = array();

	/* SAVE DOOR RNAGE STYLE */
	if($exist_styles) {$door_style = $exist_styles; }

	$files = $_FILES["door_style"];  
	$new_files = '';

	if (is_array($files['name']) || is_object($files['name']))
	{
	foreach ($files['name'] as $key => $value) {       
    if ($files['name'][$key]) { 
        $file = array( 
            'name' => $files['name'][$key],
            'type' => $files['type'][$key], 
            'tmp_name' => $files['tmp_name'][$key], 
            'error' => $files['error'][$key],
            'size' => $files['size'][$key]
        ); 
        $_FILES = array ("my_image_upload" => $file); 
        foreach ($_FILES as $file => $array) {              
	      $pid = get_the_ID();

	      $attach_id = media_handle_upload( $file, $pid);
	      if($attach_id) 
		  {
		  	$door_style[] = $attach_id;
		  }
	      //var_dump($attach_id);
	      
	        }
	    } 
	}
	}
	// UPDATE POST META 
	update_post_meta($post_id,'_range_price',$_POST['price']);
	update_post_meta($post_id,'_range_desc',$_POST['description']);
	update_post_meta($post_id,'_range_door_name',$_POST['door_name']);
	update_post_meta($post_id,'_range_door_price',$_POST['door_price']);
	update_post_meta($post_id,'_range_door_base_price',$_POST['door_base_price']);
	update_post_meta($post_id,'_range_door_ratio',$_POST['door_ratio']);
	update_post_meta($post_id,'_range_door_style',$door_style);

	
	/* SAVE DOOR STYLE */

	$exist_styles = $_POST['image_style'];
	$door_style2 = array();
	/* SAVE DOOR RNAGE STYLE */
	if($exist_styles) {$door_style2 = $exist_styles; }
	$files2 = $_FILES["door_style_pic"];  
	$new_files = '';
	if (is_array($files2['name']) || is_object($files2['name']))
	{
	foreach ($files2['name'] as $key => $value) {       
    if ($files2['name'][$key]) { 
        $file = array( 
            'name' => $files2['name'][$key],
            'type' => $files2['type'][$key], 
            'tmp_name' => $files2['tmp_name'][$key], 
            'error' => $files2['error'][$key],
            'size' => $files2['size'][$key]
        ); 
        $_FILES = array ("my_image_upload" => $file); 
        foreach ($_FILES as $file => $array) {              
	      $pid = get_the_ID();

	      $attach_id = media_handle_upload( $file, $pid);
	      if($attach_id) 
		  {
		  	$door_style2[] = $attach_id;
		  }
	      //var_dump($attach_id);
	      
	        }
	    } 
	}
	}
	// UPDATE POST META 
	update_post_meta($post_id,'_range_door_style_pic_name',$_POST['door_style_pic_name']);
	update_post_meta($post_id,'_range_500_750',$_POST['500-750']);
	update_post_meta($post_id,'_range_750_1000',$_POST['750-1000']);
	update_post_meta($post_id,'_range_1000_1200',$_POST['1000-1200']);
	update_post_meta($post_id,'_range_1200_1500',$_POST['1200-1500']);
	update_post_meta($post_id,'_range_1500_2000',$_POST['1500-2000']);	
	update_post_meta($post_id,'_range_door_style_pic',$door_style2);


	/* SAVE COLOR STYLE */

	$exist_styles_clr = $_POST['color_image'];
	$door_style3 = array();
	/* SAVE DOOR RNAGE STYLE */
	if($exist_styles_clr) {$door_style3 = $exist_styles_clr; }
	$files3 = $_FILES["color_style"];
	$new_files = '';
	if (is_array($files3['name']) || is_object($files3['name']))
	{
	foreach ($files3['name'] as $key => $value) {       
    if ($files3['name'][$key]) { 
        $file = array( 
            'name' => $files3['name'][$key],
            'type' => $files3['type'][$key], 
            'tmp_name' => $files3['tmp_name'][$key], 
            'error' => $files3['error'][$key],
            'size' => $files3['size'][$key]
        ); 
        $_FILES = array ("my_image_upload" => $file); 
        foreach ($_FILES as $file => $array) {              
	      $pid = get_the_ID();

	      $attach_id = media_handle_upload( $file, $pid);
	      if($attach_id) 
		  {
		  	$door_style3[] = $attach_id;
		  }
	      //var_dump($attach_id);
	      
	        }
	    } 
	}
	}
	// UPDATE POST META 
	update_post_meta($post_id,'_range_color_name',$_POST['color_name']);
	update_post_meta($post_id,'_range_color_price',$_POST['color_price']);
	update_post_meta($post_id,'_range_color_style',$door_style3);	

	/* SAVE GLASS STYLE */

	$exist_styles_gls = $_POST['glass_image'];
	$door_style4 = array();
	/* SAVE DOOR RNAGE STYLE */
	if($exist_styles_gls) {$door_style4 = $exist_styles_gls; }
	$files4 = $_FILES["glass_style"];
	$new_files = '';
	if (is_array($files4['name']) || is_object($files4['name']))
	{
	foreach ($files4['name'] as $key => $value) {       
    if ($files4['name'][$key]) { 
        $file = array( 
            'name' => $files4['name'][$key],
            'type' => $files4['type'][$key], 
            'tmp_name' => $files4['tmp_name'][$key], 
            'error' => $files4['error'][$key],
            'size' => $files4['size'][$key]
        ); 
        $_FILES = array ("my_image_upload" => $file); 
        foreach ($_FILES as $file => $array) {              
	      $pid = get_the_ID();

	      $attach_id = media_handle_upload( $file, $pid);
	      if($attach_id) 
		  {
		  	$door_style4[] = $attach_id;
		  }
	      //var_dump($attach_id);
	      
	        }
	    } 
	}
	}
	// UPDATE POST META 
	update_post_meta($post_id,'_range_glass_name',$_POST['glass_name']);
	update_post_meta($post_id,'_range_glass_price',$_POST['glass_price']);
	update_post_meta($post_id,'_range_glass_style',$door_style4);	

}
add_action('save_post', 'wporg_save_postdata');









/* DUPLICATION FOR POSTS */

function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;
 
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
 
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
 
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );
 
		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
 
		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
function rd_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
}
 
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
?>