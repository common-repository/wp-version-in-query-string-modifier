<?php if (! defined('ABSPATH')) exit;
/*
Plugin Name: WP Version in Query String Modifier
Plugin URI: https://wordpress.org/plugins/
Description: Modifies (update or remove) the version (query string 'ver' parameter) in media resoures' url. Depending on your needs, this plugin is useful for:
* updating the version value to an incrementing number (with a single click) for cache-busting purposes (this is the default value)
* removing the version parameter for optimization purposes
* option to disable modifying the url without disabling the plugin
Author: joesat
Version: 1.0.0.7
Author URI: ttps://wordpress.org/plugins/
License: GPL2
*/

/** define plugin namespace */
define( 'WPVQSM', 'wpvqsm_' );  

/** define template folder */
define( 'WPVQSM_TMPL_DIR', dirname(__FILE__) . '/tmpl/' );

/** plugin name constants */
define( 'WPVQSM_LONG_NAME', 'WP Version in Query String Modifier' );
define( 'WPVQSM_SHORT_NAME', 'WP Version Modifier' );

/**
 * fetch plugin options from DB   
 *
 * @since 1.6
 *
 * @return array            array list of plugin options, or return default values when not found        
 */
function wpvqsm_get_options() {
    $wpvqsm_default_values = array(
        'selection' => 'i',
        'increment' => '7',
		'addTime' => '0',
    );
    return get_option( WPVQSM . 'options', $wpvqsm_default_values );
}

/**
 * update plugin option
 *
 * @since 1.6
 *
 */
function wpvqsm_update_options( $options ) {
    update_option( WPVQSM . 'options', $options );
}

/**
 * modify query string from uri using WP utility remove_query_arg
 *
 * @since 1.0
 *
 * @param string $src       the target uri to format
 * @return string           Formatted uri with version (ver GET param) populated by plugin option - increment
 */
function wpvqsm_modify_version( $src ) {
    $options = wpvqsm_get_options();
    if ('i' === $options['selection']) {
        // override the ver param with our new value
		$addTime = '';
		if ((int)$options['addTime'] === 1) {
			$addTime = time();
		}
        return add_query_arg('ver', str_pad( $options['increment'], 3, '0', STR_PAD_LEFT ) . $addTime , $src);
    }
    else if ('r' === $options['selection']) {
        // remove ver in the query string
        $src = remove_query_arg('ver', $src);
        return $src;
    }
    else {
        // do nothing
		return $src;
    }
}

/**
 * add admin menu - under settings
 *
 * @since 1.0
 *
 */
function wpvqsm_menu() {
    add_options_page( WPVQSM_SHORT_NAME . ' Options', WPVQSM_SHORT_NAME, 'manage_options', WPVQSM, WPVQSM . 'options' );
}

/**
 * add settings link on plugin pa
 *
 * @since 1.1
 *
 */
function wpvqsm_settings_link( $links ) { 
    $settings_link = '<a href="options-general.php?page=' . WPVQSM . '">Settings</a>'; 
    array_unshift( $links, $settings_link ); 
    return $links; 
}

/**
 * renders plugin form page
 *
 * @since 1.0
 *
 */
function wpvqsm_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    
    // fetch options
    $options = wpvqsm_get_options();
    
    // template variable
    $ndx = str_pad( $options['increment'], 3, '0', STR_PAD_LEFT );
    $addTime = $options['addTime'];
    $updated = (!empty($_GET['settings-updated']) && 'true' === $_GET['settings-updated']) ? true : false;
    // include form
    require_once(WPVQSM_TMPL_DIR . 'menu.php');
}

/**
 * update plugin option and redirects back to plugin form page
 *
 * @since 1.0
 *
 */
function wpvqsm_update_callback() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $options = wpvqsm_get_options();
    $options['selection'] = $_POST['selection'];
    $options['addTime'] = $_POST['addTime'];
    wpvqsm_update_options( $options );
    wp_safe_redirect( 'options-general.php?settings-updated=true&page=' . WPVQSM );
}

/**
 * update plugin option - increment value for cache buster in static resource uri
 *
 * @since 1.0
 *
 */
function wpvqsm_ajax_callback() {
    if ( !current_user_can( 'manage_options' ) )  {
        die( 1 );
    }
    ob_start ();
    global $wpdb; 
    // no use for now but might be useful later
    //$action = $_POST['action'] ;    
    
    // get plugin option and update
    $options = wpvqsm_get_options();
    ++$options['increment'];
    wpvqsm_update_options( $options );
    ob_end_clean ();
    echo str_pad( $options['increment'], 3, '0', STR_PAD_LEFT );
    die( 1 );
}

/**
 * plugin activation process
 *
 * @since 1.0
 *
 */
function wpvqsm_activate() {
    $wpvqsm_default_values = array(
        'selection' => 'i',
        'increment' => '7',
        'addTime' => '0',
    );

    if ( !is_multisite() ) {
        add_option( WPVQSM . 'options', $wpvqsm_default_values );
    }
    else {
        global $wpdb;
        $blog_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            add_option( WPVQSM . 'options', $wpvqsm_default_values );
        }
        restore_current_blog();  
    }
}

/**
 * plugin deactivation process and clean-up
 *
 * @since 1.0
 *
 */
function wpvqsm_deactivate() {   
    if ( !is_multisite() ) {
        delete_option( WPVQSM . 'options' );
    }
    else {
        global $wpdb;
        $blog_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            delete_option( WPVQSM . 'options' );
        }
        restore_current_blog();  
    }
}

// add activation/deactivation hooks
register_activation_hook( __FILE__, WPVQSM . 'activate' );
register_deactivation_hook( __FILE__, WPVQSM . 'deactivate' );

// add admin functinality
if ( is_admin() ){
    $plugin = plugin_basename( __FILE__ );
    add_action( 'admin_menu', WPVQSM . 'menu' );
    add_action( 'wp_ajax_' . WPVQSM, WPVQSM . 'ajax_callback' );
    add_action( 'admin_post_' . WPVQSM, WPVQSM . 'update_callback' );
    add_filter( 'plugin_action_links_' . $plugin, WPVQSM . 'settings_link' );
}

add_filter( 'script_loader_src', WPVQSM. 'modify_version' );
add_filter( 'style_loader_src', WPVQSM. 'modify_version' );   