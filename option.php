
<?php

/*
  Plugin Name: Test
   
 */

// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('My Cool Plugin Settings', 'Cool Settings', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'fname' );
	register_setting( 'my-cool-plugin-settings-group', 'lname' );
	register_setting( 'my-cool-plugin-settings-group', 'city' );
	register_setting( 'my-cool-plugin-settings-group','number');
}

function my_cool_plugin_settings_page() {

	//add_option( 'myhack_extraction_length', '25555', '', 'yes' );  
  //add_site_option( 'my_option', 'tina' );
	//echo get_site_option( 'my_option' );
	// delete_option( 'my_option' ); 
	//get option     $admin_email = get_option( 'admin_email' );
	//echo get_site_option( 'siteurl' );
?>
<div class="wrap">
<h1>Your Plugin Name</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Fname</th>
        <td><input type="text" name="fname" value="<?php echo esc_attr( get_option('fname') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Lname</th>
        <td><input type="text" name="lname" value="<?php echo esc_attr( get_option('lname') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">City</th>
        <td><input type="text" name="city" value="<?php echo esc_attr( get_option('city') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Number</th>
        <td><input type="text" name="number" value="<?php echo esc_attr( get_option('number') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
