<?php
/**
 * GC Sermons Tag
 *
 * @version 0.1.4
 * @package GC Sermons
 */

class GCS_Tag extends GCS_Taxonomies_Base {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = 'tag';

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
			'labels' => array( __( 'Tag', 'gc-sermons' ), __( 'Tags', 'gc-sermons' ), 'gcs-tag' ),
			'args'   => array(
				'hierarchical' => false,
				'rewrite' => array( 'slug' => 'sermon-tag' ),
			),
		) );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function hooks() {
	}
}
