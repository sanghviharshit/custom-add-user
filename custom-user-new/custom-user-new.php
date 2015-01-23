<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * and defines a function that starts the plugin.
 *
 * @link              http://about.me/harshit
 * @package Custom-User-New
 * @author Harshit Sanghvi {@link http://github.com/sanghviharshit}
 * @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
 *
 * @wordpress-plugin
 * Plugin Name: Custom New User Page
 * Description: Replaces existing Add Users page in site dashboard and allows to add users without sending an email confirmation to new users. Also adds custom text below add user form.
 * Plugin URI: http://github.com/sanghviharshit
 * Author: Harshit Sanghvi <sanghvi.harshit@gmail.com>
 * Author URI:        http://about.me/harshit
 * License: GPL2
 * Version: 0.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


define( 'CUN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CUN_PAGE_SLUG', 'custom-user-new.php');


class Custom_User_New {

    /** @var string $text_domain The text domain of the plugin */
    var $text_domain = 'cun_trans';
    /** @var string $plugin_dir The plugin directory path */
    var $plugin_dir;
    /** @var string $plugin_url The plugin directory URL */
    var $plugin_url;
    /** @var string $domain The plugin domain */
    var $domain;
    
    /**
     * Constructor.
     */
    function __construct() {
        
        $this->init_vars();
        $this->init();
    }

    /**
     * Initiate plugin.
     *
     * @return void
     */
    function init() {
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'user_new_form', array( $this , 'custom_content_below_add_user_form' ) );
    }

    /**
     * Initiate variables.
     *
     * @return void
     */
    function init_vars() {
        global $wpdb;
        /* Set plugin directory path */
        $this->plugin_dir = CUN_PLUGIN_DIR;
        /* Set plugin directory URL */
        $this->plugin_url = plugin_dir_url(__FILE__);
    }

    /**
     * Add CSS
     * @todo not yet used.
     * @return void
     */
    function admin_enqueue_scripts($hook) {
        // Including CSS file
    }

    /**
     * Loads the language file from the "languages" directory.
     *
     * @return void
     */
    function load_plugin_textdomain() {
        load_plugin_textdomain( $this->text_domain, null, dirname( plugin_basename( __FILE__ ) ) . '/includes/languages/' );
    }

    /**
     * Add Google Analytics options page.
     *
     * @return void
     */
    function admin_menu() {
        global $menu;
        global $submenu;

        //unset($submenu['users.php'][10]);

        if ( current_user_can('create_users') )
            $submenu['users.php'][10] = array(_x('Add New', 'user'), 'create_users', 'admin.php?page='.CUN_PAGE_SLUG);
        else
            $submenu['users.php'][10] = array(_x('Add New', 'user'), 'promote_users', 'admin.php?page='.CUN_PAGE_SLUG);


        add_submenu_page( 
                null, 
                'Add New User',
                'Add New User',
                'promote_users', 
                CUN_PAGE_SLUG,
                array( &$this, 'output_user_new_page' ) );

    }

    /**
     * Add network admin menu
     *
     * @todo remove, not used
     * @access public
     * @return void
     */
    function network_admin_menu() {

    }

    /**
     * Network add user page
     *
     * @access public
     * @return void
     */
    function output_network_user_new_page() {
        /* Get Network settings */
        $this->output_site_settings_page( 'network' );
    }

    /**
     * Admin Users->add user page output
     *
     * @return void
     */
    function output_user_new_page( $network = '' ) {
        require_once( $this->plugin_dir . "includes/user-new.php" );
    }

    /**
    * Adds Custom text on add users page below add user form.
    *
    * @access public
    */
    public function custom_content_below_add_user_form() {
        echo '<h3 style="font-weight:bold">Note:</h3>';
        echo '<ul style="font-weight:bold; font-size:14px; list-style:disc; margin-left:16px">';
            echo '<li>Enter netID@nyu.edu as the username.</li>';
            echo '<li>For adding more than 1 user at a time we recommend using our Bulk Import Users plugin. Instructions for adding bulk users can be found here - <a href="http://www.nyu.edu/servicelink/KB0012244" target="_blank">http://www.nyu.edu/servicelink/KB0012244</a></li>';
            echo '<li>If you receive a message indicating that the user already exists and you are unable to add them as an existing user, please try adding them via Bulk Import Users plugin.</li>';
        echo '</ul>';
    } 


}

global $custom_user_new;
$custom_user_new = new Custom_User_New();



