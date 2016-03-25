<?php
/**
 * GC Sermons Sermon Post
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_Sermon_Post {

	/**
	 * Post object to wrap
	 *
	 * @var   WP_Post
	 * @since 0.1.0
	 */
	protected $post;

	/**
	 * Media data for the sermon post.
	 *
	 * @var array
	 */
	protected $media = array();

	/**
	 * Image data for the sermon post.
	 *
	 * @var array
	 */
	protected $images = array();

	/**
	 * Series terms for the sermon post.
	 *
	 * @var array
	 */
	protected $series = array();

	/**
	 * Speakers terms for the sermon post.
	 *
	 * @var array
	 */
	protected $speakers = array();

	/**
	 * Topics terms for the sermon post.
	 *
	 * @var array
	 */
	protected $topics = array();

	/**
	 * Tags terms for the sermon post.
	 *
	 * @var array
	 */
	protected $tags = array();

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  WP_Post $post Post object to wrap
	 * @return void
	 */
	public function __construct( WP_Post $post ) {
		$post_type = gc_sermons()->sermons->post_type();
		if ( $post->post_type !== gc_sermons()->sermons->post_type() ) {
			wp_die( 'Sorry, '. __CLASS__ .' expects a '. $post_type .' object.' );
		}

		// $this->taxonomies = gc_sermons()->taxonomies;
		$this->post = $post;
	}

	/**
	 * Initate the video/audio media object
	 *
	 * @since  0.1.0
	 *
	 * @return array Array of video/audio media info.
	 */
	protected function init_media() {
		$this->media = array(
			'video' => array(),
			'audio' => array(),
		);
		$this->add_media_type( 'video' );
		$this->add_media_type( 'audio' );
		return $this->media;
	}

	/**
	 * Add media info to the media array for $type
	 *
	 * @since 0.1.0
	 *
	 * @param string  $type type of media
	 */
	protected function add_media_type( $type = 'video' ) {
		// Only audio/video allowed.
		$type = 'video' === $type ? $type : 'audio';
		$media = false;

		if ( $media_url = get_post_meta( $this->ID, "gc_sermon_{$type}_url", 1 ) ) {
			$media = array(
				'type' => 'url',
				'value' => $media_url
			);
		} elseif ( $media_src = get_post_meta( $this->ID, "gc_sermon_{$type}_src_id", 1 ) ) {
			$media = array(
				'type' => 'attachment_id',
				'value' => $media_src,
				'attachment_url' => get_post_meta( $this->ID, "gc_sermon_{$type}_src", 1 )
			);
		} elseif ( $media_url = get_post_meta( $this->ID, "gc_sermon_{$type}_src", 1 ) ) {
			$media = array(
				'type' => 'url',
				'value' => $media_url,
			);
		}

		if ( $media ) {
			$this->media[ $type ] = $media;
		}

		return $this;
	}

	/**
	 * Wrapper for get_the_post_thumbnail which stores the results to the object
	 *
	 * @since  0.1.0
	 *
	 * @param  string|array $size  Optional. Image size to use. Accepts any valid image size, or
	 *	                            an array of width and height values in pixels (in that order).
	 *	                            Default 'full'.
	 * @param  string|array $attr Optional. Query string or array of attributes. Default empty.
	 * @return string             The post thumbnail image tag.
	 */
	public function featured_image( $size = 'full', $attr = '' ) {
		// Unique id for the passed-in attributes.
		$id = md5( $attr );

		if ( isset( $this->images[ $size ] ) ) {
			// If we got it already, then send it back
			if ( isset( $this->images[ $size ][ $id ] ) ) {
				return $this->images[ $size ][ $id ];
			} else {
				$this->images[ $size ][ $id ] = array();
			}
		} else {
			$this->images[ $size ] = array( $id = array() );
		}


		$this->images[ $size ][ $id ] = get_the_post_thumbnail( $this->ID, $size, $attr );

		return $this->images[ $size ][ $id ];
	}

	/**
	 * Wrapper for get_post_thumbnail_id
	 *
	 * @since  0.1.0
	 *
	 * @return string|int Post thumbnail ID or empty string.
	 */
	public function featured_image_id() {
		return get_post_thumbnail_id( $this->ID );
	}

	/**
	 * Wrapper for get_the_terms for the sermon_series taxonomy
	 *
	 * @since  0.1.0
	 *
	 * @return array  Array of series terms
	 */
	public function series() {
		if ( empty( $this->series ) ) {
			$this->series = $this->init_taxonomy( 'sermon_series' );
		}

		return $this->series;
	}

	/**
	 * Wrapper for get_the_terms for the speaker taxonomy
	 *
	 * @since  0.1.0
	 *
	 * @return array  Array of speaker terms
	 */
	public function speakers() {
		if ( empty( $this->speakers ) ) {
			$this->speakers = $this->init_taxonomy( 'speaker' );
		}

		return $this->speakers;
	}

	/**
	 * Wrapper for get_the_terms for the topic taxonomy
	 *
	 * @since  0.1.0
	 *
	 * @return array  Array of topic terms
	 */
	public function topics() {
		if ( empty( $this->topics ) ) {
			$this->topics = $this->init_taxonomy( 'topic' );
		}

		return $this->topics;
	}

	/**
	 * Wrapper for get_the_terms for the tag taxonomy
	 *
	 * @since  0.1.0
	 *
	 * @return array  Array of tag terms
	 */
	public function tags() {
		if ( empty( $this->tags ) ) {
			$this->tags = $this->init_taxonomy( 'tag' );
		}

		return $this->tags;
	}

	/**
	 * Initate the taxonomy.
	 *
	 * @since  0.1.0
	 *
	 * @param  string  $taxonomy Taxonomy to initiate
	 *
	 * @return array             Array of terms for this taxonomy.
	 */
	protected function init_taxonomy( $taxonomy ) {
		$tax_slug = gc_sermons()->taxonomies->{$taxonomy}->taxonomy();
		return get_the_terms( $this->ID, $tax_slug );
	}

	/**
	 * Magic getter for our object.
	 *
	 * @param string $property
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $property ) {
		$property = $this->translate_property( $property );

		// Automate
		switch ( $property ) {
			case 'series':
			case 'speakers':
			case 'topics':
			case 'tags':
				return $this->{$property}();
			case 'post':
				return $this->{$property};
			case 'media':
				// Lazy-load the media-getting
				if ( empty( $this->media ) ) {
					return $this->init_media();
				}
				return $this->media;
			default:
				// Check post object for property
				// In general, we'll avoid using same-named properties,
				// so the post object properties are always available.
				if ( isset( $this->post->{$property} ) ) {
					return $this->post->{$property};
				}
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $property );
		}
	}

	/**
	 * Magic isset checker for our object.
	 *
	 * @param string $property
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __isset( $property ) {
		$property = $this->translate_property( $property );

		// Automate
		switch ( $property ) {
			case 'series':
			case 'speakers':
			case 'topics':
			case 'tags':
				$terms = $this->{$property}();
				return ! empty( $terms );
			default:
				// Check post object for property
				// In general, we'll avoid using same-named properties,
				// so the post object properties are always available.
				return isset( $this->post->{$property} );
		}
	}

	/**
	 * Allow some variations on the object __getter
	 *
	 * @since  NEXXT
	 *
	 * @param  string  $property Object property to fetch
	 *
	 * @return string            Maybe-modified property name
	 */
	protected function translate_property( $property ) {
		// Translate
		switch ( $property ) {
			case 'sermon_series':
				$property = 'series';
				break;
			case 'speaker':
				$property = 'speakers';
				break;
			case 'topic':
				$property = 'topics';
				break;
			case 'tag':
				$property = 'tags';
				break;
		}

		return $property;
	}

}
