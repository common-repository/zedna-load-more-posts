<?php
/*
 * Plugin Name: Zedna Load more posts
 * Plugin URI: https://profiles.wordpress.org/zedna#content-plugins
 * Text Domain: zedna-load-more-posts
 * Domain Path: /languages
 * Description: Load more posts on the page. Wordpress must have set <a href="options-permalink.php">nice url</a> to <strong>Post name</strong>.
 * Version: 1.2
 * Author: Radek Mezulanik
 * Author URI: http://mezulanik.cz
 * License: GPL3
 */
 
 /**
  * Initialization. Add our script if needed on this page.
  */
 function zedna_load_more_posts_init() {
 	global $wp_query;
 
 	// Add code to index pages.
 	if( !is_singular() ) {	
 		// Queue JS and CSS
 		wp_enqueue_script(
 			'zedna-load-more-posts',
 			plugin_dir_url( __FILE__ ) . 'js/zedna-load-more-posts.js',
 			array('jquery'),
 			'1.0',
 			true
 		);
 		
 		wp_enqueue_style(
 			'zedna-load-more-posts-style',
 			plugin_dir_url( __FILE__ ) . 'css/zedna-load-more-posts.css',
 			false,
 			'1.0',
 			'all'
 		);
 		
 		
 		// What page are we on? And what is the pages limit?
 		$max = $wp_query->max_num_pages;
 		$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
    $posts_parent_container = get_option( 'posts_parent_container' );
    $pagination_container = get_option( 'pagination_container' );
    $article_contianer = get_option( 'article_contianer' );
    $load_on_scroll = get_option( 'load_on_scroll' );
    $lang_text_button_default = get_option( 'lang_text_button_default' );
    $lang_text_button_loading = get_option( 'lang_text_button_loading' );
    $lang_text_button_nopost = get_option( 'lang_text_button_nopost' );
 		
 		// Add some parameters for the JS.
 		wp_localize_script(
 			'zedna-load-more-posts',
 			'lmp_var',
 			array(
      'posts_parent_container' => $posts_parent_container,
      'pagination_container' => $pagination_container,
      'article_contianer' => $article_contianer,
      'load_on_scroll' => $load_on_scroll,
      'lang_text_button_default' => $lang_text_button_default,
      'lang_text_button_loading' => $lang_text_button_loading,
      'lang_text_button_nopost' => $lang_text_button_nopost,
 				'startPage' => $paged,
 				'maxPages' => $max,
 				'nextLink' => next_posts($max, false)
 			)
 		);
 	}
 }
 add_action('template_redirect', 'zedna_load_more_posts_init');


//Add admin page
add_action('admin_menu', 'zedna_load_more_posts_setttings_menu');
 
function zedna_load_more_posts_setttings_menu(){
	global $lmpImagePath;
 $lmpImagePath = plugins_url().'/'.dirname(plugin_basename(__FILE__));
        add_menu_page( __('Load more posts settings page','zedna-load-more-posts'), __('Load more posts','zedna-load-more-posts'), 'manage_options', 'load_more_posts', 'zedna_load_more_posts_settings_init',$lmpImagePath.'/img/lmp-ico.png'  );
  // Call update_spamfck function to update database
  add_action( 'admin_init', 'zedna_update_load_more_posts' );
}

// Create function to register plugin settings in the database
if( !function_exists("zedna_update_load_more_posts") )
{
function zedna_update_load_more_posts() {
  register_setting( 'load_more_posts-settings', 'posts_parent_container' );
  register_setting( 'load_more_posts-settings', 'pagination_container' );
  register_setting( 'load_more_posts-settings', 'article_contianer' );
  register_setting( 'load_more_posts-settings', 'load_on_scroll' );
  register_setting( 'load_more_posts-settings', 'lang_text_button_default' );
  register_setting( 'load_more_posts-settings', 'lang_text_button_loading' );
  register_setting( 'load_more_posts-settings', 'lang_text_button_nopost' );
}
}
 
function zedna_load_more_posts_settings_init(){
$posts_parent_container = (get_option('posts_parent_container') != '') ? get_option('posts_parent_container') : '#main';
$pagination_container = (get_option('pagination_container') != '') ? get_option('pagination_container') : '.pagination';
$article_contianer = (get_option('article_contianer') != '') ? get_option('article_contianer') : 'article';
$load_on_scroll = get_option('load_on_scroll');
$lang_text_button_default = (get_option('lang_text_button_default') != '') ? get_option('lang_text_button_default') : __('Load more posts','zedna-load-more-posts');
$lang_text_button_loading = (get_option('lang_text_button_loading') != '') ? get_option('lang_text_button_loading') : __('Loading posts...','zedna-load-more-posts');
$lang_text_button_nopost = (get_option('lang_text_button_nopost') != '') ? get_option('lang_text_button_nopost') : __('No more posts to load','zedna-load-more-posts');
?>

<h1><?php echo __('Zedna load more posts settings','zedna-load-more-posts');?></h1>
<form method="post" action="options.php">
  <?php settings_fields( 'load_more_posts-settings' ); ?>
  <?php do_settings_sections( 'load_more_posts-settings' ); ?>
  <table class="form-table">
    <tr valign="top">
      <th scope="row"><?php echo __('Posts parent container','zedna-load-more-posts');?>:</th>
      <td><input type="text" name="posts_parent_container" value="<?php echo $posts_parent_container;?>" />
        <?php echo __('e.g. "#content"','zedna-load-more-posts');?></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('Pagination container','zedna-load-more-posts');?>:</th>
      <td><input type="text" name="pagination_container" value="<?php echo $pagination_container;?>" />
        <?php echo __('e.g. ".pagination"','zedna-load-more-posts');?></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('Post container','zedna-load-more-posts');?>:</th>
      <td><input type="text" name="article_contianer" value="<?php echo $article_contianer;?>" />
        <?php echo __('container of article in loop, usually "article"','zedna-load-more-posts');?></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('Load on scroll','zedna-load-more-posts');?>:</th>
      <td><input type='checkbox' id='load_on_scroll' name='load_on_scroll' value='1'
    <?php if ( 1 == $load_on_scroll ) echo 'checked="checked"'; ?> />
    <label for="load_on_scroll"><?php echo __('load more posts when user scroll to bottom of page','zedna-load-more-posts');?></label></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('Custom text','zedna-load-more-posts');?></th>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('Load more posts','zedna-load-more-posts');?></th>
      <td><input type="text" name="lang_text_button_default" value="<?php echo $lang_text_button_default;?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('Loading posts...','zedna-load-more-posts');?></th>
      <td><input type="text" name="lang_text_button_loading" value="<?php echo $lang_text_button_loading;?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php echo __('No more posts to load','zedna-load-more-posts');?></th>
      <td><input type="text" name="lang_text_button_nopost" value="<?php echo $lang_text_button_nopost;?>" /></td>
    </tr>
  </table>
  <?php submit_button(); ?>
</form>

<p><?php echo __('If you like this plugin, please donate us for faster upgrade','zedna-load-more-posts');?></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHFgYJKoZIhvcNAQcEoIIHBzCCBwMCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYB56P87cZMdKzBi2mkqdbht9KNbilT7gmwT65ApXS9c09b+3be6rWTR0wLQkjTj2sA/U0+RHt1hbKrzQyh8qerhXrjEYPSNaxCd66hf5tHDW7YEM9LoBlRY7F6FndBmEGrvTY3VaIYcgJJdW3CBazB5KovCerW3a8tM5M++D+z3IDELMAkGBSsOAwIaBQAwgZMGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIqDGeWR22ugGAcK7j/Jx1Rt4pHaAu/sGvmTBAcCzEIRpccuUv9F9FamflsNU+hc+DA1XfCFNop2bKj7oSyq57oobqCBa2Mfe8QS4vzqvkS90z06wgvX9R3xrBL1owh9GNJ2F2NZSpWKdasePrqVbVvilcRY1MCJC5WDugggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xNTA2MjUwOTM4MzRaMCMGCSqGSIb3DQEJBDEWBBQe9dPBX6N8C2F2EM/EL1DwxogERjANBgkqhkiG9w0BAQEFAASBgAz8dCLxa+lcdtuZqSdM+s0JJBgLgFxP4aZ70LkZbZU3qsh2aNk4bkDqY9dN9STBNTh2n7Q3MOIRugUeuI5xAUllliWO7r2i9T5jEjBlrA8k8Lz+/6nOuvd2w8nMCnkKpqcWbF66IkQmQQoxhdDfvmOVT/0QoaGrDCQJcBmRFENX-----END PKCS7-----
">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit"
    alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<?php
} 
 ?>
