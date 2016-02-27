<?php
/**
 * GC Sermons Taxonomies Base
 *
 * @version 0.1.0
 * @package GC Sermons
 */

abstract class GCS_Taxonomies_Base extends Taxonomy_Core {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  0.1.0
	 */
	protected $plugin = null;

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
		$this->hooks();

		// Register this taxonomy
		// First parameter should be an array with Singular, Plural, and Registered name
		// Second parameter is the register taxonomy arguments
		// Third parameter is post types to attach to.
		parent::__construct(
			$args['labels'],
			$args['args'],
			$args['post_types']
		);

		add_action( 'init', array( $this, 'filter_values' ), 4 );
	}

	public function filter_values( $args ) {
		$args = array(
			'singular'      => $this->singular,
			'plural'        => $this->plural,
			'taxonomy'      => $this->taxonomy,
			'arg_overrides' => $this->arg_overrides,
			'object_types'  => $this->object_types,
		);

		$filtered_args = apply_filters( 'gcs_taxonomies_'. $this->taxonomy(), $args, $this );
		if ( $filtered_args !== $args ) {
			foreach ( $args as $arg => $val ) {
				if ( isset( $filtered_args[ $arg ] ) ) {
					$this->{$arg} = $filtered_args[ $arg ];
				}
			}
		}
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.0
	 * @return void
	 */
	abstract function hooks();
}
