<?php
/**
 * GC Sermons Taxonomies Base
 *
 * @version 0.1.0
 * @package GC Sermons
 */

abstract class GCS_Taxonomies_Base extends Taxonomy_Core {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * GCS_Sermons object
	 *
	 * @var GCS_Sermons
	 * @since  0.1.0
	 */
	protected $sermons = null;

	/**
	 * The image meta key for this taxonomy, if applicable
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $image_meta_key = '';

	/**
	 * The default args array for self::get()
	 *
	 * @var array
	 * @since  NEXT
	 */
	protected $term_get_args_defaults = array(
		'image_size' => 512,
	);

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

		$filtered_args = apply_filters( 'gcs_taxonomies_'. $this->id, $args, $this );
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
	 * @since  0.1.0
	 *
	 * @param  boolean $get_single_term Whether to get the first term or all of them.
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent( $get_single_term = false ) {
		static $terms = null;

		if ( null === $terms ) {
			$sermon = $this->most_recent_sermon();

			if ( ! $sermon ) {
				$terms = false;
				return $terms;
			}

			$terms = $sermon->{$this->id};
			$terms = $terms && $get_single_term && is_array( $terms ) ? array_shift( $terms ) : $terms;
		}

		return $terms;
	}

	/**
	 * Retrieve the most recent sermon which has terms in this taxonomy.
	 *
	 * @since  0.2.0
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent_sermon() {
		return $this->sermons->most_recent_with_taxonomy( $this->id );
	}

	/**
	 * Get a single term object
	 *
	 * @since  NEXT
	 *
	 * @param  object|int $term Term id or object
	 * @param  array      $args Array of arguments.
	 *
	 * @return WP_Term|false    Term object or false
	 */
	public function get( $term, $args = array() ) {
		$term = isset( $term->term_id ) ? $term : get_term_by( 'id', $term_id, $this->taxonomy() );
		if ( ! isset( $term->term_id ) ) {
			return false;
		}

		$args = wp_parse_args( $args, $this->term_get_args_defaults );
		$args = apply_filters( "gcs_get_{$this->id}_default_args", $args, $term, $this );

		$term->term_link = get_term_link( $term );
		$term = $this->extra_term_data( $term, $args );

		return $term;
	}

	/**
	 * Sets extra term data on the the term object, including the image, if applicable
	 *
	 * @since  NEXT
	 *
	 * @param  WP_Term $term Term object
	 * @param  array   $args Array of arguments.
	 *
	 * @return WP_Term|false
	 */
	protected function extra_term_data( $term, $args ) {
		if ( $this->image_meta_key ) {
			$term = $this->add_image( $term, $args['image_size'] );
		}

		return $term;
	}

	/**
	 * Add term's image
	 *
	 * @since  NEXT
	 *
	 * @param  WP_Term $term Term object
	 * @param  string  $size Size of the image to retrieve
	 *
	 * @return mixed         URL if successful or set
	 */
	protected function add_image( $term, $size = '' ) {
		if ( ! $this->image_meta_key ) {
			return $term;
		}

		$img_id = get_term_meta( $term->term_id, $this->image_meta_key . '_id', 1 );
		if ( ! $img_id ) {

			$term->image = '';
			$term->image_url = get_term_meta( $term->term_id, $this->image_meta_key, 1 );

			return $term;
		}

		if ( $size ) {
			$size = is_numeric( $size ) ? array( $size, $size ) : $size;
		}

		$term->image = wp_get_attachment_image( $img_id, $size ? $size : 'thumbnail' );

		$src = wp_get_attachment_image_src( $img_id, $size ? $size : 'thumbnail' );
		$term->image_url = isset( $src[0] ) ? $src[0] : '';

		return $term;
	}

	/**
	 * Magic getter for our object. Allows getting but not setting.
	 *
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'id':
				return $this->id;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
}
