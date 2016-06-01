<?php
/**
 * GC Sermons Sermons
 *
 * @version 0.1.5
 * @package GC Sermons
 */

class GCS_Sermons extends GCS_Post_Types_Base {

	/**
	 * The identifier for this object
	 *
	 * @var string
	 */
	protected $id = 'sermon';

	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Bypass temp. cache
	 *
	 * @var boolean
	 * @since  0.1.0
	 */
	public $flush = false;

	/**
	 * Default WP_Query args
	 *
	 * @var   array
	 * @since 0.1.0
	 */
	protected $query_args = array(
		'post_type'      => 'THIS(REPLACE)',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	);

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
				'rewrite' => array( 'slug' => 'sermons' ),
			),
		) );
		$this->query_args['post_type'] = $this->post_type();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'cmb2_admin_init', array( $this, 'fields' ) );
		add_filter( 'cmb2_override_excerpt_meta_value', array( $this, 'get_excerpt' ), 10, 2 );
		add_filter( 'cmb2_override_excerpt_meta_save', '__return_true' );
		add_filter( 'admin_init', array( $this, 'admin_hooks' ) );

		/**
		 * Enable image fallback. If Sermon does not have a feautured image, fall back
		 * to the sermon series image (if it exists).
		 *
		 * To disable:
		 * 	add_filter( 'gc_do_sermon_series_fallback_image', '__return_false' );
		 *
		 */
		if ( apply_filters( 'gc_do_sermon_series_fallback_image', true ) ) {
			add_filter( 'get_post_metadata', array( $this, 'featured_image_fallback_to_series_image' ), 10, 3 );
		}

		/**
		 * Enable future posts to be displayed.
		 *
		 * If false, future posts will be 'scheduled', WordPress' default behavior.
		 *
		 * To disable:
		 * 	add_filter( 'gc_display_future_sermsons', '__return_false' );
		 *
		 */
		if ( apply_filters( 'gc_display_future_sermsons', true ) ) {
			add_filter( 'wp_insert_post_data', array( $this, 'save_future_as_published' ), 10, 2 );
			if ( ! is_admin() ) {
				add_filter( 'the_title', array( $this, 'label_coming_soon' ), 10, 2 );
			}
		}
	}

	/**
	 * Initiate our admin hooks
	 *
	 * @since  0.1.1
	 * @return void
	 */
	public function admin_hooks() {
		add_action( 'dbx_post_advanced', array( $this, 'remove_default_boxes_for_sermons' ) );
		add_filter( "manage_edit-{$this->post_type()}_columns", array( $this, 'columns' ) );
	}

	/**
	 * Remove default excerpt/feat-image metaboxes for Sermons
	 *
	 * @since  0.1.3
	 *
	 * @return void
	 */
	public function remove_default_boxes_for_sermons() {
		$screen = get_current_screen();

		if ( isset( $screen->post_type ) && $this->post_type() === $screen->post_type ) {
			remove_meta_box( 'postexcerpt', null, 'normal' );
			remove_meta_box( 'postimagediv', null, 'side' );
		}
	}

	/**
	 * This provides a backup featured image for sermons by checking the sermon series
	 * for the series featured image. If a sermon has a featured image set, that will be used.
	 *
	 * @since  0.1.3
	 *
	 * @param null|array|string $value The value get_metadata() should return - a single metadata value,
	 *                                 or an array of values.
	 * @param  int    $object_id       Object ID.
	 * @param  string $meta_key        Meta key.
	 *
	 * @return mixed Sermon featured image id, or Series image id, or nothing.
	 */
	public function featured_image_fallback_to_series_image( $meta, $object_id, $meta_key ) {

		// Override thumbnail_id check and get the series image id as a fallback.
		if ( '_thumbnail_id' === $meta_key && $this->post_type() === get_post_type( $object_id ) ) {

			// Have to remove this filter to get the actual value to see if we need to do the work.
			remove_filter( 'get_post_metadata', array( $this, 'featured_image_fallback_to_series_image' ), 10, 3 );
			$id = get_post_thumbnail_id( $object_id );
			add_filter( 'get_post_metadata', array( $this, 'featured_image_fallback_to_series_image' ), 10, 3 );

			// Ok, no feat img id.
			if ( ! $id || ! get_post( $id ) ) {

				// Get sermon.
				$sermon = new GCS_Sermon_Post( get_post( $object_id ) );

				// Get series.
				$series = $sermon->get_series();

				// Send series image id.
				return isset( $series->image_id ) ? $series->image_id : $id;
			}
		}

		return $meta;
	}

	/**
	 * When a scheduled message post is saved, change the status back to 'publish'.
	 * This allows the future-date sermons to show on the front-end.
	 *
	 * @since  0.1.3
	 *
	 * @param  array  $data    Array of post data for update.
	 * @param  array  $postarr Full array of post data.
	 *
	 * @return array           Modified post data array.
	 */
	public function save_future_as_published( $data, $postarr ) {
		if (
			! isset( $postarr['ID'], $data['post_status'], $data['post_type'] )
			|| 'future' !== $data['post_status']
			|| 'sermonaudio' !== $data['post_type']
		) {
			return $data;
		}

		$data['post_status'] = 'publish';

		return $data;
	}

	public function label_coming_soon( $title, $post_id = 0 ) {
		static $now = null;
		static $done = array();

		$post_id = $post_id ? $post_id : get_the_id();

		if ( isset( $done[ $post_id ] ) ) {
			return $done[ $post_id ];
		}

		$now = null === $now ? gmdate( 'Y-m-d H:i:59' ) : $now;

		if ( mysql2date( 'U', get_post( $post_id )->post_date_gmt, false ) > mysql2date( 'U', $now, false ) ) {

			$coming_soon_prefix = apply_filters( 'gcs_sermon_coming_soon_prefix', '<span class="coming-soon-prefix">' . __( 'Coming Soon:', 'gc-sermons' ) . '</span> ', $post_id, $this );
			$title = $coming_soon_prefix . $title ;
		}

		$done[ $post_id ] = $title;

		return $title;
	}

	/**
	 * Add custom fields to the CPT
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function fields() {
		$fields = array(
			'gc_sermon_video_url' => array(
				'id'   => 'gc_sermon_video_url',
				'name' => __( 'Video URL', 'gc-sermons' ),
				'desc' => __( 'Enter a youtube, or vimeo URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'gc-sermons' ),
				'type' => 'oembed',
			),
			'gc_sermon_video_src' => array(
				'id'      => 'gc_sermon_video_src',
				'name'    => __( 'Video File', 'gc-sermons' ),
				'desc'    => __( 'Alternatively upload/select video from your media library.', 'gc-sermons' ),
				'type'    => 'file',
				'options' => array( 'url' => false ),
			),
			'gc_sermon_audio_url' => array(
				'id'   => 'gc_sermon_audio_url',
				'name' => __( 'Audio URL', 'gc-sermons' ),
				'desc' => __( 'Enter a soundcloud, spotify, or other oembed-supported web audio URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'gc-sermons' ),
				'type' => 'oembed',
			),
			'gc_sermon_audio_src' => array(
				'id'      => 'gc_sermon_audio_src',
				'name'    => __( 'Audio File', 'gc-sermons' ),
				'desc'    => __( 'Alternatively upload/select audio from your media library.', 'gc-sermons' ),
				'type'    => 'file',
				'options' => array( 'url' => false ),
			),
			'excerpt' => array(
				'id'   => 'excerpt',
				'name' => __( 'Excerpt', 'gc-sermons' ),
				'desc' => __( 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="https://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>' ),
				'type' => 'textarea',
				'escape_cb' => false,
			),
			'_thumbnail' => array(
				'id'   => '_thumbnail',
				'name' => __( 'Image', 'gc-staff' ),
				'desc' => __( 'Select an image if you want to override the series image for this sermon.', 'gc-sermons' ),
				'type' => 'file',
			),
			'gc_sermon_notes' => array(
				'id'   => 'gc_sermon_notes',
				'name' => __( 'Sermon Notes', 'gc-sermons' ),
				'type' => 'wysiwyg',
			),
		);

		$this->new_cmb2( array(
			'id'           => 'gc_sermon_metabox',
			'title'        => __( 'Sermon Details', 'gc-sermons' ),
			'object_types' => array( $this->post_type() ),
			'fields'       => $fields,
		) );

		$this->new_cmb2( array(
			'id'           => 'gc_related_links_metabox',
			'title'        => __( 'Related Links', 'gc-sermons' ),
			'object_types' => array( $this->post_type() ),
			'closed'       => true,
			'context'      => 'side',
			'priority'     => 'core',
			'fields'       => array(
				cmb2_related_links_field( array( 'id' => 'gc_related_links' ), array(
					'description' => __( 'Add links, or select from related content by clicking the search icon.', 'gc-sermons' ),
					'group_title' => __( 'Link {#}', 'gc-sermons' ),
					'link_title'  => __( 'Title', 'gc-sermons' ),
					'link_url'    => __( 'URL', 'gc-sermons' ),
					'find_text'   => __( 'Find/Select related content', 'gc-sermons' ),
				) ),
			),
		) );
	}

	public function get_excerpt( $data, $post_id ) {
		return get_post_field( 'post_excerpt', $post_id );
	}

	/**
	 * Registers admin columns to display.
	 * @since  0.1.0
	 * @param  array  $columns Array of registered column names/labels
	 * @return array           Modified array
	 */
	public function columns( $columns ) {
		$last = array_splice( $columns, 2 );
		$columns[ 'tax-'. $this->plugin->series->id ] = $this->plugin->series->taxonomy( 'singular' );

		// placeholder
		return array_merge( $columns, $last );
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since  0.1.0
	 * @param array $column  Column currently being rendered.
	 * @param int   $post_id ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		if ( 'tax-'. $this->plugin->series->id === $column ) {
			add_action( 'admin_footer', array( $this, 'admin_column_css' ) );

			// Get sermon post object
			$sermon = new GCS_Sermon_Post( get_post( $post_id ) );

			// If we have sermon series...
			if ( is_array( $sermon->series ) ) {

				// Then loop them (typically only one)
				foreach ( $sermon->series as $series ) {

					// Get augmented term object to get the thumbnail url
					$series = $this->plugin->series->get( $series, array( 'image_size' => 'thumb' ) );

					// Edit-term link
					$edit_link = get_edit_term_link( $series->term_id, $series->taxonomy, $this->post_type() );

					// Add the image, or the term name.
					if ( $series->image_url ) {
						$class = ' with-image';
						$title = ' title="'. esc_attr( $series->name ) .'"';
						$term = '<img style="max-width: 100px;" src="'. esc_url( $series->image_url ) .'" /></a>';
					} else {
						$class = $title = '';
						$term = $series->name;
					}

					echo '<div class="sermon-series'. $class .'"><a'. $title .' href="'. esc_url( $edit_link ) .'">'. $term .'</a></div>';
				}
			}
		}
	}

	public function admin_column_css() {
		GCS_Style_Loader::output_template( 'admin-column' );
	}

	/**
	 * Retrieve the most recent sermon with video media.
	 *
	 * @since  0.1.0
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent_with_video() {
		static $sermon = null;

		if ( null === $sermon || $this->flush ) {
			$sermon = $this->most_recent();

			if ( empty( $sermon->media['video'] ) ) {
				$sermon = $this->most_recent_with_media( 'video' );
			}
		}

		return $sermon;
	}

	/**
	 * Retrieve the most recent sermon with audio media.
	 *
	 * @since  0.1.0
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent_with_audio() {
		static $sermon = null;

		if ( null === $sermon || $this->flush ) {
			$sermon = $this->most_recent();

			if ( empty( $sermon->media['audio'] ) ) {
				$sermon = $this->most_recent_with_media( 'audio' );
			}
		}

		return $sermon;
	}

	/**
	 * Retrieve the most recent sermon.
	 *
	 * @since  0.1.0
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent() {
		static $sermon = null;

		if ( null === $sermon || $this->flush ) {
			$sermons = new WP_Query( apply_filters( 'gcs_recent_sermon_args', $this->query_args ) );
			$sermon = false;
			if ( $sermons->have_posts() ) {
				$sermon = new GCS_Sermon_Post( $sermons->post );
			}
		}

		return $sermon;
	}

	/**
	 * Retrieve a specific sermon.
	 *
	 * @since  0.1.0
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function get( $args ) {
		$args = wp_parse_args( $args, $this->query_args );
		$sermons = new WP_Query( apply_filters( 'gcs_get_sermon_args', $args ) );
		$sermon = false;
		if ( $sermons->have_posts() ) {
			$sermon = new GCS_Sermon_Post( $sermons->post );
		}

		return $sermon;
	}

	/**
	 * Retrieve sermons.
	 *
	 * @since  0.1.0
	 *
	 * @return WP_Query WP_Query object
	 */
	public function get_many( $args ) {
		$defaults = $this->query_args;
		unset( $defaults['posts_per_page'] );
		unset( $defaults['no_found_rows'] );
		$args['augment_posts'] = true;

		$args = apply_filters( 'gcs_get_sermons_args', wp_parse_args( $args, $defaults ) );
		$sermons = new WP_Query( $args );

		if (
			isset( $args['augment_posts'] )
			&& $args['augment_posts']
			&& $sermons->have_posts()
			// Don't augment for queries w/ greater than 100 posts, for perf. reasons.
			&& $sermons->post_count < 100
		) {
			foreach ( $sermons->posts as $key => $post ) {
				$sermons->posts[ $key ] = new GCS_Sermon_Post( $post );
			}
		}

		return $sermons;
	}

	/**
	 * Retrieve the most recent sermon with audio media.
	 *
	 * @since  0.1.0
	 *
	 * @param  string  $type Media type (audio or video)
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	protected function most_recent_with_media( $type = 'video' ) {
		$sermon = false;

		// Only audio/video allowed.
		$type = 'video' === $type ? $type : 'audio';

		$args = $this->query_args;
		$args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key' => "gc_sermon_{$type}_url",
			),
			array(
				'key' => "gc_sermon_{$type}_src",
			),
		);

		$sermons = new WP_Query( apply_filters( "gcs_recent_sermon_with_{$type}_args", $args ) );

		if ( $sermons->have_posts() ) {
			$sermon = new GCS_Sermon_Post( $sermons->post );
		}

		return $sermon;
	}

	/**
	 * Retrieve the most recent sermon which has terms in specified taxonomy.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $taxonomy_id GCS_Taxonomies_Base taxonomy id
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	public function most_recent_with_taxonomy( $taxonomy_id ) {
		$sermon = $this->most_recent();

		// No sermon post found at all.. oops
		if ( ! $sermon ) {
			return false;
		}

		try {
			$terms = $sermon->{$taxonomy_id};
		} catch ( Exception $e ) {
			return new WP_Error( __( '"%s" is not a valid taxonomy for %s.', 'gc-sermons' ), $taxonomy_id, $this->post_type( 'plural' ) );
		}

		if ( ! $terms || is_wp_error( $terms ) ) {
			$sermon = $this->find_sermon_with_taxonomy( $taxonomy_id, array( $sermon->ID ) );
		}

		return $sermon;
	}

	/**
	 * Searches for posts which have terms in a given taxonomy, while excluding previous tries.
	 *
	 * @since  0.1.0
	 *
	 * @param  string  $taxonomy_id GCS_Taxonomies_Base taxonomy id
	 * @param  array   $exclude     Array of excluded post IDs
	 *
	 * @return GCS_Sermon_Post|false  GC Sermon post object if successful.
	 */
	protected function find_sermon_with_taxonomy( $taxonomy_id, $exclude ) {
		static $count = 0;

		$args = $this->query_args;
		$args['post__not_in'] = $exclude;
		$args = apply_filters( 'gcs_find_sermon_with_taxonomy_args', $args );

		$sermons = new WP_Query( $args );

		if ( ! $sermons->have_posts() ) {
			return false;
		}

		$sermon = new GCS_Sermon_Post( $sermons->post );

		$terms = $sermon ? $sermon->{$taxonomy_id} : false;

		if ( ! $terms || is_wp_error( $terms ) ) {
			// Only try this up to 5 times
			if ( ++$count > 6 ) {
				return false;
			}

			$exclude = array_merge( $exclude, array( $sermon->ID ) );
			$sermon = $this->find_sermon_with_taxonomy( $taxonomy_id, $exclude );
		}

		return $sermon;
	}


}
