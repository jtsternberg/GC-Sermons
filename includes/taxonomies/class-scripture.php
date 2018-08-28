<?php
/**
 * GC Sermons Scripture References
 *
 * @package GC Sermons
 */

/**
 * GCS_Scripture
 * @since 0.1.3
 */
class GCS_Scripture extends GCS_Taxonomies_Base {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = 'scripture';

	/**
	 * Constructor
	 * Register Taxonomy. See documentation in Taxonomy_Core, and in wp-includes/taxonomy.php
	 *
	 * @since 0.1.3
	 * @param  object $sermons GCS_Sermons object.
	 * @return void
	 */
	public function __construct( $sermons ) {
		parent::__construct( $sermons, array(
			'labels' => array( __( 'Scripture Reference', 'gc-sermons' ), __( 'Scripture References', 'gc-sermons' ), 'gcs-scripture' ),
			'args' => array(
				'rewrite' => array( 'slug' => 'scripture-reference' ),
			),
		) );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.3
	 * @return void
	 */
	public function hooks() {
	}
}
