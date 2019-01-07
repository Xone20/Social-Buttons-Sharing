<?php
/*
Plugin Name: Social Buttons Sharing
URI: http://www.xcode.com/
Version: 1.0
Author: Luigi Xone
Author URI: http://www.xcode.com/
Description: Questo plugin permette di inserire all'interno del sito Wordpress i collegamenti verso i vostri social network.
*/

// SECURITY
if(!defined('ABSPATH')) exit;
if(defined('WP_INSTALLING') && WP_INSTALLING) {return;}

// ADD CSS FUNCTION
function style() {
   wp_register_style('style', plugins_url('css/style.css',__FILE__ ));
   wp_enqueue_style('style');
}
add_action( 'admin_init','style');

// We'll key on the slug for the settings page so set it here so it can be used in various places
define('MY_PLUGIN_SLUG', 'social-buttons-sharing-option');

// Register a callback for our specific plugin's actions
add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'social_buttons_sharing_action_links');
function social_buttons_sharing_action_links( $links ) {
   $links[] = '<a href="'. menu_page_url(MY_PLUGIN_SLUG, false) .'">Settings</a>';
   return $links;
}

// Create a normal admin menu
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'register_settings');
  add_action( 'admin_init', 'social_buttons_sharing_register_settings' );
}

function register_settings() {
   add_options_page('Social Buttons Sharing Settings', 'Social Buttons Sharing Settings', 'manage_options', MY_PLUGIN_SLUG, 'social_buttons_sharing_settings_page');

    //We just want to URL to be valid so now we're going to remove the item from the menu
    //The code below walks the global menu and removes our specific item by its slug
    global $submenu;
    if( array_key_exists('social-buttons-sharing-option' , $submenu))
    {
        foreach($submenu['social-buttons-sharing-option'] as $k => $v)
        {
            if( MY_PLUGIN_SLUG === $v[2] )
            {
                unset($submenu['social-buttons-sharing-option'][$k]);
            }
        }
    }
}

/**
* Sanitize each setting field as needed
*
* @param array $input Contains all settings fields as array keys
*/
function sanitize( $input )
{
    $new_input = array();
    if( isset( $input['social_buttons_sharing_option_fb'] ) )
        $new_input['social_buttons_sharing_option_fb'] = sanitize_text_field( $input['social_buttons_sharing_option_fb'] );
	
    if( isset( $input['social_buttons_sharing_option_ig'] ) )
        $new_input['social_buttons_sharing_option_ig'] = sanitize_text_field( $input['social_buttons_sharing_option_ig'] );	
	
    if( isset( $input['social_buttons_sharing_option_tw'] ) )
        $new_input['social_buttons_sharing_option_tw'] = sanitize_text_field( $input['social_buttons_sharing_option_tw'] );

    if( isset( $input['social_buttons_sharing_option_in'] ) )
        $new_input['social_buttons_sharing_option_in'] = sanitize_text_field( $input['social_buttons_sharing_option_in'] );

    if( isset( $input['social_buttons_sharing_option_gp'] ) )
        $new_input['social_buttons_sharing_option_gp'] = sanitize_text_field( $input['social_buttons_sharing_option_gp'] );

    if( isset( $input['social_buttons_sharing_option_size'] ) )
        $new_input['social_buttons_sharing_option_size'] = sanitize_text_field( $input['social_buttons_sharing_option_size'] );

    if( isset( $input['social_buttons_sharing_option_align'] ) )
        $new_input['social_buttons_sharing_option_align'] = sanitize_text_field( $input['social_buttons_sharing_option_align'] );		

        return $new_input;
}

// FUNCTION REGISTER SETTINGS
function social_buttons_sharing_register_settings() {
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_fb' );
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_ig' );
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_tw' );
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_in' );
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_gp' );
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_size' );
  register_setting( 'social_buttons_sharing_options_group', 'social_buttons_sharing_option_align' );   
}

// This is our plugins settings page
function social_buttons_sharing_settings_page() {
?>
  <div>
  <?php screen_icon(); ?>
  <h1>Social Buttons Sharing - General Settings</h1>
  <br />
  <h3>Use: shortcode [show_social_buttons]</h3>
  <br />
  <h2>Social Buttons Sharing URL</h2>
  <form method="post" action="options.php">
  <?php settings_fields('social_buttons_sharing_options_group'); ?>
  <div class="form-check">
  <label class="form-check-label">Facebook:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_fb" id="facebook" value="<?php echo esc_attr( get_option('social_buttons_sharing_option_fb') ); ?>">
  </div>
  <div class="form-check">
  <label class="form-check-label">Instagram:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_ig" id="instagram" value="<?php echo esc_attr( get_option('social_buttons_sharing_option_ig') ); ?>">
  </div>
  <div class="form-check">
  <label class="form-check-label">Twitter:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_tw" id="twitter" value="<?php echo esc_attr( get_option('social_buttons_sharing_option_tw') ); ?>">
  </div>
  <div class="form-check">
  <label class="form-check-label">Linkedin:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_in" id="linkedin" value="<?php echo esc_attr( get_option('social_buttons_sharing_option_in') ); ?>">
  </div>
  <div class="form-check">
  <label class="form-check-label">Google+:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_gp" id="google+" value="<?php echo esc_attr( get_option('social_buttons_sharing_option_gp') ); ?>">
  </div>
  <div class="form-check">
  <label class="form-check-label">Size (px):</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_size" id="size" placeholder="Enter icon size pixel (max size 256px)" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter icon size pixel (max size 256px)'" value="<?php echo esc_attr( get_option('social_buttons_sharing_option_size') ); ?>">
  </div>
  <div class="form-check">
  <label class="form-check-label">Align:</label>
    <select class="form-check-select" name="social_buttons_sharing_option_align">
	<?php $align = esc_attr( get_option('social_buttons_sharing_option_align') ); ?>
    <option value="<?php echo $align; ?>" selected><?php echo $align; ?></option>
	<?php if ($align != "left"){ ?><option value="left">left</option><?php } ?>
	<?php if ($align != "center"){ ?><option value="center">center</option><?php } ?>
	<?php if ($align != "right"){ ?><option value="right">right</option><?php } ?>	
    </select>
  </div>
			  
  <?php submit_button(); ?>
  </form>
  </div>
<?php
}

// Main pubblic plugin function
add_shortcode('show_social_buttons', 'social_buttons_share_show');
function social_buttons_share_show() {
	$facebook = esc_attr(esc_url( get_option('social_buttons_sharing_option_fb') ) );
	$instagram = esc_attr(esc_url( get_option('social_buttons_sharing_option_ig') ) );
	$twitter = esc_attr(esc_url( get_option('social_buttons_sharing_option_tw') ) );
	$linkedin = esc_attr(esc_url( get_option('social_buttons_sharing_option_in') ) );
	$google_plus = esc_attr(esc_url( get_option('social_buttons_sharing_option_gp') ) );	
	$size = esc_attr( get_option('social_buttons_sharing_option_size') );
	
	$size = preg_replace("/[^0-9]/", "",$size);
	if ($size == ""){ $size = "50"; }
	
	$align = esc_attr( get_option('social_buttons_sharing_option_align') );
	$fb_ico = esc_url(plugins_url('/ico/fb.png', __FILE__));
	$ig_ico = esc_url(plugins_url('/ico/ig.png', __FILE__));
	$tw_ico = esc_url(plugins_url('/ico/tw.png', __FILE__));
	$in_ico = esc_url(plugins_url('/ico/in.png', __FILE__));
	$gp_ico = esc_url(plugins_url('/ico/gp.png', __FILE__));	
	
	$content = "";
	$content .= "<div align='$align'>";
	$content .= "<a href='$facebook' target='_blank'><img src='$fb_ico' width='$size' height='$size' /></a>";
	$content .= "<a href='$instagram' target='_blank'><img src='$ig_ico' width='$size' height='$size' /></a>";
	$content .= "<a href='$twitter' target='_blank'><img src='$tw_ico' width='$size' height='$size' /></a>";
	$content .= "<a href='$linkedin' target='_blank'><img src='$in_ico' width='$size' height='$size' /></a>";
	$content .= "<a href='$google_plus' target='_blank'><img src='$gp_ico' width='$size' height='$size' /></a>";
	$content .= "</div>";
    return $content;		
} 

?>
