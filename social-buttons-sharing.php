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
add_action('admin_menu', 'register_settings');
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

// Main plugin function
add_action( 'admin_init', 'social_buttons_sharing_register_settings' );
function social_buttons_sharing_register_settings() {
// Function Facebook   	
   add_option('social_buttons_sharing_option_fb', 'This is URL Facebook FanPage');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_fb', 'social_buttons_sharing_callback'); 
// Function Instagram   
   add_option('social_buttons_sharing_option_ig', 'This is URL Instagram FanPage');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_ig', 'social_buttons_sharing_callback'); 
// Function Twitter   
   add_option('social_buttons_sharing_option_tw', 'This is URL Twitter FanPage');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_tw', 'social_buttons_sharing_callback'); 
 // Function Linkedin   
   add_option('social_buttons_sharing_option_in', 'This is URL Linkedin FanPage');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_in', 'social_buttons_sharing_callback'); 
 // Function Google+   
   add_option('social_buttons_sharing_option_gp', 'This is URL Google+ FanPage');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_gp', 'social_buttons_sharing_callback');   
// Function Size  
   add_option('social_buttons_sharing_option_size', 'This is SIZE social button icons');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_size', 'social_buttons_sharing_callback');  
// Function Align   
   add_option('social_buttons_sharing_option_align', 'This is Alignment social button icons');
   register_setting('social_buttons_sharing_options_group', 'social_buttons_sharing_option_align', 'social_buttons_sharing_callback');    
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
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_fb" id="facebook" value="<?php echo get_option('social_buttons_sharing_option_fb'); ?>">
</div>
<div class="form-check">
  <label class="form-check-label">Instagram:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_ig" id="instagram" value="<?php echo get_option('social_buttons_sharing_option_ig'); ?>">
</div>
<div class="form-check">
  <label class="form-check-label">Twitter:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_tw" id="twitter" value="<?php echo get_option('social_buttons_sharing_option_tw'); ?>">
</div>
<div class="form-check">
  <label class="form-check-label">Linkedin:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_in" id="linkedin" value="<?php echo get_option('social_buttons_sharing_option_in'); ?>">
</div>
<div class="form-check">
  <label class="form-check-label">Google+:</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_gp" id="google+" value="<?php echo get_option('social_buttons_sharing_option_gp'); ?>">
</div>
<div class="form-check">
  <label class="form-check-label">Size (px):</label>
    <input class="form-check-input" type="text" name="social_buttons_sharing_option_size" id="size" placeholder="enter icon size only number" onfocus="this.placeholder = ''" onblur="this.placeholder = 'enter icon size only number'" value="<?php echo get_option('social_buttons_sharing_option_size'); ?>">
</div>
<div class="form-check">
  <label class="form-check-label">Align:</label>
    <select class="form-check-select" name="social_buttons_sharing_option_align">
	<?php $align = get_option('social_buttons_sharing_option_align'); ?>
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
function social_buttons_share_show() {
	$facebook = get_option('social_buttons_sharing_option_fb');
	$instagram = get_option('social_buttons_sharing_option_ig');
	$twitter = get_option('social_buttons_sharing_option_tw');
	$linkedin = get_option('social_buttons_sharing_option_in');
	$google_plus = get_option('social_buttons_sharing_option_gp');	
	
	$size = get_option('social_buttons_sharing_option_size');
	$size = preg_replace("/[^0-9]/", "",$size);
	if ($size == ""){ $size = "50"; }
	
	$align = get_option('social_buttons_sharing_option_align');
	
	$fb_ico = plugins_url('/ico/fb.png', __FILE__);
	$ig_ico = plugins_url('/ico/ig.png', __FILE__);
	$tw_ico = plugins_url('/ico/tw.png', __FILE__);
	$in_ico = plugins_url('/ico/in.png', __FILE__);
	$gp_ico = plugins_url('/ico/gp.png', __FILE__);
	
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
add_shortcode('show_social_buttons', 'social_buttons_share_show');
?>
