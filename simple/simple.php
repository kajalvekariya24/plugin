 <?php
 
/*
  Plugin Name: Simple
  Description: Plugin for testing purpose
  Version: 1
  Author: AlphansoTech
  Author URI: http://alphansotech.com
 */
add_action('admin_menu', 'at_alphansotech_menu');
 
//js file add

add_action( 'wp_enqueue_scripts', 'my_enqueued_assets' );

function my_enqueued_assets() {
	wp_enqueue_script( 'my-script', plugin_dir_url( __FILE__ ) . '/js/my-script.js', array( 'jquery' ), '1.0', true );
}

function at_alphansotech_menu() {

   add_menu_page('Emplist', 'Emplist', 'manage_options', 'my-top-level-handle', 'my_magic_function');
   add_submenu_page( 'my-top-level-handle', 'Emprs', 'Emprs', 'manage_options', 'my-submenu-handle', 'my_magic_function_1');


  }
 




function my_magic_function()
{

  //add_option( 'myhack_extraction_length', '25555', '', 'yes' );  
  //add_site_option( 'my_option', 'tina' );
	//echo get_site_option( 'my_option' );
	// delete_option( 'my_option' ); 
	//get option     $admin_email = get_option( 'admin_email' );
	//echo get_site_option( 'siteurl' );

     echo 'hello';

	 ?>
      
      

<?php
}
   
function my_magic_function_1()
{
   echo 'ram';
}
?>

 