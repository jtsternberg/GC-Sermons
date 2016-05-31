<?php
/**
 * Plugin Name: GC Sermons
 * Plugin URI:  http://dsgnwrks.pro
 * Description: Manage sermons and sermon content in WordPress
 * Version:     0.1.4
 * Author:      jtsternberg
 * Author URI:  http://dsgnwrks.pro
 * Donate link: http://dsgnwrks.pro
 * License:     GPLv2
 * Text Domain: gc-sermons
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 jtsternberg (email : justin@dsgnwrks.pro)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */

// User composer autoload.
require 'vendor/autoload_52.php';

/**
 * Main initiation class
 *
 * @since  0.1.0
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class GC_Sermons_Plugin {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  0.1.0
	 */
	const VERSION = '0.1.4';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  0.1.0
	 */
	public static $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  0.1.0
	 */
	public static $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  0.1.0
	 */
	public static $basename = '';

	/**
	 * Array of plugin requirements, keyed by admin notice label.
	 *
	 * @var array
	 * @since  0.1.0
	 */
	protected $requirements = array();

	/**
	 * Array of plugin requirements which are not met.
	 *
	 * @var array
	 * @since  0.1.0
	 */
	protected $missed_requirements = array();

	/**
	 * Singleton instance of plugin
	 *
	 * @var GC_Sermons_Plugin
	 * @since  0.1.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of GCS_Sermons
	 *
	 * @var GCS_Sermons
	 */
	protected $sermons;

	/**
	 * Instance of GCS_Taxonomies
	 *
	 * @since 0.1.0
	 * @var GCS_Taxonomies
	 */
	protected $taxonomies;

	/**
	 * Instance of GCS_Shortcodes
	 *
	 * @since 0.1.0
	 * @var GCS_Shortcodes
	 */
	protected $shortcodes;

	/**
	 * Instance of GCS_Async
	 *
	 * @since 0.1.1
	 * @var GCS_Async
	 */
	protected $async;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  0.1.0
	 * @return GC_Sermons_Plugin A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  0.1.0
	 */
	protected function __construct() {
		self::$basename = plugin_basename( __FILE__ );
		self::$url      = plugin_dir_url( __FILE__ );
		self::$path     = plugin_dir_path( __FILE__ );

		$this->plugin_classes();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function plugin_classes() {
		require_once self::$path . 'functions.php';

		// Attach other plugin classes to the base plugin class.
		$this->sermons = new GCS_Sermons( $this );
		$this->taxonomies = new GCS_Taxonomies( $this->sermons );
		$this->async = new GCS_Async( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
		if ( ! defined( 'CMB2_LOADED' ) || ! defined( 'WDS_SHORTCODES_LOADED' ) ) {
			add_action( 'tgmpa_register', array( $this, 'register_required_plugin' ) );
		} else {
			$this->shortcodes = new GCS_Shortcodes( $this );
		}
	}

	/**
	 * Requires CMB2 to be installed
	 */
	public function register_required_plugin() {

		$plugins = array(
			array(
				'name'               => 'CMB2',
				'slug'               => 'cmb2',
				'required'           => true,
				'version'            => '2.2.1',
			),
			array(
				'name'         => 'WDS Shortcodes',
				'slug'         => 'wds-shortcodes',
				'source'       => 'https://raw.githubusercontent.com/WebDevStudios/WDS-Shortcodes/master/wds-shortcodes.zip',
				'required'     => true,
				'external_url' => 'https://github.com/WebDevStudios/WDS-Shortcodes',
			),
		);

		$config = array(
			'domain'       => 'gc-sermons',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'install_plugins',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'is_automatic' => true,
			'message'      => '',
			'strings'      => array(
				'page_title'                      => __( 'Install Required Plugins', 'cool-shortcode' ),
				'menu_title'                      => __( 'Install Plugins', 'cool-shortcode' ),
				'installing'                      => __( 'Installing Plugin: %s', 'cool-shortcode' ),
				// %1$s = plugin name
				'oops'                            => __( 'Something went wrong with the plugin API.', 'cool-shortcode' ),
				'notice_can_install_required'     => _n_noop( 'The "WDS Shortcodes" plugin requires the following plugin: %1$s.', 'This plugin requires the following plugins: %1$s.' ),
				// %1$s = plugin name(s)
				'notice_can_install_recommended'  => _n_noop( 'This plugin recommends the following plugin: %1$s.', 'This plugin recommends the following plugins: %1$s.' ),
				// %1$s = plugin name(s)
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
				// %1$s = plugin name(s)
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
				// %1$s = plugin name(s)
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
				// %1$s = plugin name(s)
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
				// %1$s = plugin name(s)
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this plugin: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this plugin: %1$s.' ),
				// %1$s = plugin name(s)
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
				// %1$s = plugin name(s)
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
				'return'                          => __( 'Return to Required Plugins Installer', 'cool-shortcode' ),
				'plugin_activated'                => __( 'Plugin activated successfully.', 'cool-shortcode' ),
				'complete'                        => __( 'All plugins installed and activated successfully. %s', 'cool-shortcode' ),
				// %1$s = dashboard link
			),
		);

		tgmpa( $plugins, $config );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public static function activate() {
		self::get_instance();
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Init hooks
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'gc-sermons', false, dirname( self::$basename ) . '/languages/' );
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  0.1.0
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			// add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( self::$basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  0.1.0
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {
		$this->requirements = array(
			array(
				sprintf( '<a href="%s">%s</a>', network_admin_url( 'plugin-install.php?tab=search&s=cmb2' ), __( 'CMB2', 'gc-sermons' ) ),
				defined( 'CMB2_LOADED' ),
			)
		);

		foreach ( $this->requirements as $requirement ) {
			list( $label, $condition ) = $requirement;
			if ( ! $condition ) {
				$this->missed_requirements[] = $label;
			}
		}

		return empty( $this->missed_requirements );
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'GC Sermons is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available. Requirements:', 'gc-sermons' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '<ol><li>'. implode( '</li><li>', $this->missed_requirements ) . '</li></ol>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.1.0
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'sermons':
			case 'taxonomies':
			case 'shortcodes':
				return $this->{$field};
			case 'series':
			case 'speaker':
			case 'topic':
			case 'tag':
				return $this->taxonomies->{$field};
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}
}

/**
 * Grab the GC_Sermons_Plugin object and return it.
 * Wrapper for GC_Sermons_Plugin::get_instance()
 *
 * @since  0.1.0
 * @return GC_Sermons_Plugin  Singleton instance of plugin class.
 */
function gc_sermons() {
	return GC_Sermons_Plugin::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( gc_sermons(), 'hooks' ) );
register_activation_hook( __FILE__, array( 'GC_Sermons_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'GC_Sermons_Plugin', 'deactivate' ) );

