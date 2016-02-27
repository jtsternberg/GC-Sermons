<?php
/**
 * GC Sermons Sermons
 *
 * @version 0.1.0
 * @package GC Sermons
 */



class GCS_Sermons extends GCS_Post_Types_Base {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		// Register this cpt
		// First parameter should be an array with Singular, Plural, and Registered name.
		parent::__construct( $plugin, array(
			'labels' => array( __( 'Sermon', 'gc-sermons' ), __( 'Sermons', 'gc-sermons' ), 'gc-sermons' ),
			'args' => array(
				'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				'menu_icon' => 'dashicons-playlist-video',
			),
		) );

	}

	/**
	 * Initiate our hooks
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'cmb2_admin_init', array( $this, 'fields' ) );
		add_action( 'dbx_post_advanced', array( $this, 'remove_excerpt_box' ) );
		add_filter( 'cmb2_override_excerpt_meta_value', array( $this, 'get_excerpt' ), 10, 2 );
		add_filter( 'cmb2_override_excerpt_meta_save', '__return_true' );
	}

	public function remove_excerpt_box() {
		remove_meta_box( 'postexcerpt', null, 'normal' );
	}

	/**
	 * Add custom fields to the CPT
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function fields() {
		$prefix = 'gc_sermons_';


		$this->new_cmb2( array(
			'id'           => 'gc_sermon_metabox',
			'title'        => __( 'Sermon Details', 'gc-sermons' ),
			'object_types' => array( $this->post_type() ),
			'fields'       => array(
				'gc_sermon_video_url' => array(
					'id'   => 'gc_sermon_video_url',
					'name' => __( 'Video URL', 'gc-sermons' ),
					'desc' => __( 'Enter a youtube, or vimeo URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'cmb2' ),
					'type' => 'oembed',
				),
				'gc_sermon_video_src' => array(
					'id'   => 'gc_sermon_video_src',
					'name' => __( 'Video File', 'gc-sermons' ),
					'desc' => __( 'Alternatively upload/select video from your media library.', 'gc-sermons' ),
					'type' => 'file',
				),
				'excerpt' => array(
					'id'   => 'excerpt',
					'name' => __( 'Excerpt', 'gc-sermons' ),
					'desc' => __( 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="https://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>' ),
					'type' => 'textarea',
					'escape_cb' => false,
				),
				'gc_sermon_notes' => array(
					'id'   => 'gc_sermon_notes',
					'name' => __( 'Sermon Questions', 'gc-sermons' ),
					'type' => 'wysiwyg',
				),
			),
		) );

	}

	public function get_excerpt( $data, $post_id ) {
		return get_post_field( 'post_excerpt', $post_id );
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  0.1.0
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array
	 */
	public function columns( $columns ) {
		$new_column = array(
		);
		return array_merge( $new_column, $columns );
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since  0.1.0
	 * @param array $column  Column currently being rendered.
	 * @param int   $post_id ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {
		}
	}
}
