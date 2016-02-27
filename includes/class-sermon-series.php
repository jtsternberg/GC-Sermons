<?php
/**
 * GC Sermons Sermon Series
 *
 * @version 0.1.0
 * @package GC Sermons
 */



class GCS_Sermon_Series extends GCS_Taxonomies_Base {
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
	public function __construct( $plugin ) {
		parent::__construct( $plugin, array(
			'labels'     => array( __( 'Sermon Series', 'gc-sermons' ), __( 'Sermon Series', 'gc-sermons' ), 'gc-sermon-series' ),
			'args'       => array( 'hierarchical' => false ),
			'post_types' => array( $plugin->sermons->post_type() )
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
