<?php
/**
 * GC Sermons Sermon Series
 *
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_Series extends GCS_Taxonomies_Base {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = 'series';

	/**
	 * Constructor
	 * Register Taxonomy. See documentation in Taxonomy_Core, and in wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 * @param  object $sermons GCS_Sermons object.
	 * @return void
	 */
	public function __construct( $sermons ) {
		parent::__construct( $sermons, array(
			'labels' => array( __( 'Sermon Series', 'gc-sermons' ), __( 'Sermon Series', 'gc-sermons' ), 'gc-sermon-series' ),
			'args'   => array( 'hierarchical' => false ),
		) );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'cmb2_admin_init', array( $this, 'fields' ) );
	}

	/**
	 * Add custom fields to the CPT
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function fields() {
		$cmb = $this->new_cmb2( array(
			'id'           => 'gc_sermon_series_metabox',
			'taxonomies'   => array( $this->taxonomy() ),
			'object_types' => array( 'term' ),
			'fields'       => array(
				'gc_sermon_series_image' => array(
					'name' => __( 'Sermon Series Image', 'gc-sermons' ),
					'desc' => __( 'Select the series\' branding image', 'gc-sermons' ),
					'id'   => 'gc_sermon_series_image',
					'type' => 'file'
				),
			),
		) );
	}
}
