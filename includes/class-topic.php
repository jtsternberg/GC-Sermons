<?php
/**
 * GC Sermons Topic
 *
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_Topic extends GCS_Taxonomies_Base {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = 'topic';

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
			'labels' => array( __( 'Topic', 'gc-sermons' ), __( 'Topics', 'gc-sermons' ), 'gcs-topic' ),
			'args'   => array(),
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
