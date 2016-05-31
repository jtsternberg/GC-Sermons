<?php
/**
 * GC Sermons Shortcode - Run
 *
 * @version 0.1.4
 * @package GC Sermons
 */

class GCSS_Sermons_Run extends GCS_Shortcodes_Run_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_sermons';

	/**
	 * GCS_Sermons object
	 *
	 * @var   GCS_Sermons
	 * @since 0.1.0
	 */
	public $taxonomies;

	/**
	 * Constructor
	 *
	 * @since 0.1.3
	 *
	 * @param GCS_Sermons $sermons
	 * @param GCS_Taxonomies $taxonomies
	 */
	public function __construct( GCS_Sermons $sermons, GCS_Taxonomies $taxonomies ) {
		$this->taxonomies = $taxonomies;
		parent::__construct( $sermons );
	}

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'per_page'          => 10, // Will use WP's per-page option.
		'content'           => 'excerpt',
		'remove_thumbnail'  => false,
		'thumbnail_size'    => 'medium',
		'number_columns'    => 2,
		'list_offset'       => 0,
		'wrap_classes'      => '',
		'remove_pagination' => false,
		'related_speaker'   => 0,
		'related_series'    => 0,
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		$posts_per_page = (int) $this->att( 'per_page', get_option( 'posts_per_page' ) );
		$paged          = (int) get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$offset         = ( ( $paged - 1 ) * $posts_per_page ) + $this->att( 'list_offset', 0 );

		$args = compact( 'posts_per_page', 'paged', 'offset' );

		$args = $this->map_related_term_args( $args );

		if ( ! $args ) {
			// We failed the related term check.
			return '';
		}

		if ( ! isset( $args['post__not_in'] ) && is_singular( $this->sermons->post_type() ) ) {
			$args['post__not_in'] = array( get_queried_object_id() );
		}

		$sermons = $this->sermons->get_many( $args );

		if ( ! $sermons->have_posts() ) {
			return '';
		}

		// $this->shortcode_object->set_att( 'number_columns', 2 );
		// $this->shortcode_object->set_att( 'remove_thumbnail', false );

		$args = $this->get_pagination( $sermons->max_num_pages );

		$args['sermons']      = $this->map_sermon_args( $sermons );
		$args['wrap_classes'] = $this->get_wrap_classes();

		$content = '';
		$content .= GCS_Style_Loader::get_template( 'list-item-style' );
		$content .= GCS_Template_Loader::get_template( 'sermons-list', $args );

		return $content;
	}

	public function map_related_term_args( $args ) {

		$required = false;
		$passes   = false;
		$keys     = array(
			'series'  => 'related_series',
			'speaker' => 'related_speaker',
		);

		foreach ( $keys as $key => $param ) {

			if ( $term_id = absint( $this->att( $param ) ) ) {

				$args['tax_query'][] = array(
					'taxonomy' => $this->taxonomies->{$key}->taxonomy(),
					'field'    => 'id',
					'terms'    => $term_id,
				);

				continue;
			}

			if ( 'this' !== $this->att( $param ) ) {
				continue;
			}

			$required = true;

			try {
				$sermon = gc_get_sermon_post( get_queried_object(), true );

				$args['post__not_in'] = array( $sermon->ID );

				$method = 'get_' . $key;
				$term = $sermon->$method();

				if ( ! $term ) {
					throw new Exception( 'No '. $key . ' term.' );
				}

			} catch( Exception $e ) {
				continue;
			}

			$passes = true;

			$args['tax_query'][] = array(
				'taxonomy' => $this->taxonomies->{$key}->taxonomy(),
				'field'    => 'id',
				'terms'    => $term->term_id,
			);

		}

		if ( $required && ! $passes ) {
			// They wanted sermons associated to 'this', but that's not possible.
			return false;
		}

		return $args;
	}

	public function get_pagination( $total_pages ) {
		$nav = array( 'prev_link' => '', 'next_link' => '' );

		if ( ! $this->bool_att( 'remove_pagination' ) ) {
			$nav['prev_link'] = get_previous_posts_link( __( '<span>&larr;</span> Newer', 'gc-sermons' ), $total_pages );
			$nav['next_link'] = get_next_posts_link( __( 'Older <span>&rarr;</span>', 'gc-sermons' ), $total_pages );
		}

		return $nav;
	}

	public function get_wrap_classes() {
		$columns   = absint( $this->att( 'number_columns' ) );
		$columns   = $columns < 1 ? 1 : $columns;

		return $this->att( 'wrap_classes' ) . ' gc-' . $columns . '-cols gc-sermons-wrap';
	}

	public function map_sermon_args( $all_sermons ) {
		global $post;
		$sermons = array();

		$do_thumb        = ! $this->bool_att( 'remove_thumbnail' );
		$type_of_content = $this->att( 'content' );
		$thumb_size      = $this->att( 'thumbnail_size' );

		while ( $all_sermons->have_posts() ) {
			$all_sermons->the_post();

			$obj = $all_sermons->post;

			$sermon = array();
			$sermon['url']            = $obj->permalink();
			$sermon['name']           = $obj->title();
			$sermon['image']          = $do_thumb ? $obj->featured_image( $thumb_size ) : '';
			$sermon['do_image']       = (bool) $sermon['image'];
			$sermon['description']    = '';
			$sermon['do_description'] = (bool) $type_of_content;
			if ( $sermon['do_description'] ) {
				$sermon['description'] = 'excerpt' === $type_of_content
					? $obj->loop_excerpt()
					: apply_filters( 'the_content', $obj->post_content );
			}

			$sermons[] = $sermon;
		}

		wp_reset_postdata();

		return $sermons;
	}

}
