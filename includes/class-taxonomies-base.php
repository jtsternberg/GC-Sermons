<?php
/**
 * GC Sermons Taxonomies Base
 *
 * @version 0.1.0
 * @package GC Sermons
 */

abstract class GCS_Taxonomies_Base extends Taxonomy_Core {

	/**
	 * GCS_Sermons object
	 *
	 * @var GCS_Sermons
	 * @since  0.1.0
	 */
	protected $sermons = null;

	/**
	 * Constructor
	 * Register Taxonomy. See documentation in Taxonomy_Core, and in wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 * @param  object $sermons GCS_Sermons object.
	 * @return void
	 */
	public function __construct( $sermons, $args ) {
		$this->sermons = $sermons;
		$this->hooks();

		// Register this taxonomy
		// First parameter should be an array with Singular, Plural, and Registered name
		// Second parameter is the register taxonomy arguments
		// Third parameter is post types to attach to.
		parent::__construct(
			$args['labels'],
			$args['args'],
			array( $this->sermons->post_type() )
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

	public function new_cmb2( $args ) {
		$cmb_id = $args['id'];
		return new_cmb2_box( apply_filters( "gcs_cmb2_box_args_{$this->taxonomy}_{$cmb_id}", $args ) );
	}

	/**
	 * Retrieve the terms for the most recent post which has this taxonomy set.
	 *
	 * @since  NEXT
	 *
	 * @param  boolean $get_single_term Whether to get the first term or all of them.
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent( $get_single_term = false ) {
		static $terms = null;

		if ( null === $terms ) {
			$taxonomy = $this->taxonomy();
			$sermon = $this->sermons->most_recent_with_taxonomy( $taxonomy );

			if ( ! $sermon ) {
				$terms = false;
				return $terms;
			}

			$terms = $sermon->{$taxonomy}();
			$terms = $terms && $get_single_term && is_array( $terms ) ? array_shift( $terms ) : $terms;
		}

		return $terms;
	}
}
