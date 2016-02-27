<?php
/**
 * GC Sermons Post Types Base
 *
 * @version 0.1.0
 * @package GC Sermons
 */

abstract class GCS_Post_Types_Base extends CPT_Core {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  0.1.0
	 */
	protected $plugin = null;

	protected $overrides_processed = false;

	/**
	 * Constructor
	 * Register Taxonomy. See documentation in Taxonomy_Core, and in wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin, $args ) {
		$this->plugin = $plugin;

		// Register this cpt
		// First parameter should be an array with Singular, Plural, and Registered name.
		parent::__construct(
			$args['labels'],
			$args['args']
		);

		$this->hooks();
		add_action( 'init', array( $this, 'filter_values' ), 4 );
	}

	public function filter_values() {
		if ( $this->overrides_processed ) {
			return;
		}

		$args = array(
			'singular'      => $this->singular,
			'plural'        => $this->plural,
			'post_type'     => $this->post_type,
			'arg_overrides' => $this->arg_overrides,
		);

		$filtered_args = apply_filters( 'gcs_post_types_'. $this->post_type, $args, $this );

		if ( $filtered_args !== $args ) {
			foreach ( $args as $arg => $val ) {
				if ( isset( $filtered_args[ $arg ] ) ) {
					$this->{$arg} = $filtered_args[ $arg ];
				}
			}
		}

		$this->overrides_processed = true;
	}

	/**
	 * Provides access to protected class properties.
	 * @since  0.2.0
	 * @param  boolean $key Specific CPT parameter to return
	 * @return mixed        Specific CPT parameter or array of singular, plural and registered name
	 */
	public function post_type( $key = 'post_type' ) {
		if ( ! $this->overrides_processed ) {
			$this->filter_values();
		}

		return parent::post_type( $key );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.0
	 * @return void
	 */
	abstract function hooks();

	public function new_cmb2( $args ) {
		$cmb_id = $args['id'];
		return new_cmb2_box( apply_filters( "gcs_cmb2_box_args_{$this->post_type}_{$cmb_id}", $args ) );
	}
}
