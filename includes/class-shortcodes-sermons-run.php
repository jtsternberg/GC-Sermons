<?php
/**
 * GC Sermons Shortcode - Run
 *
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Sermons_Run extends GCS_Shortcodes_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_sermons';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_per_page'          => 10, // Will use WP's per-page option.
		'sermon_content'           => 'excerpt',
		'sermon_remove_thumbnail'  => false,
		'sermon_thumbnail_size'    => 'medium',
		'sermon_number_columns'    => 2,
		'sermon_list_offset'       => 0,
		'sermon_wrap_classes'      => '',
		'sermon_remove_pagination' => false,
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		$posts_per_page = (int) $this->att( 'sermon_per_page', get_option( 'posts_per_page' ) );
		$paged          = (int) get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$offset         = ( ( $paged - 1 ) * $posts_per_page ) + $this->att( 'sermon_list_offset', 0 );

		$sermons = gc_sermons()->sermons->get_many( compact( 'posts_per_page', 'paged', 'offset' ) );

		if ( ! $sermons->have_posts() ) {
			return '';
		}

		// $this->shortcode_object->set_att( 'sermon_number_columns', 2 );
		// $this->shortcode_object->set_att( 'sermon_remove_thumbnail', false );

		$args = $this->get_pagination( $sermons->max_num_pages );

		$args['sermons']      = $this->map_sermon_args( $sermons );
		$args['wrap_classes'] = $this->get_wrap_classes();

		$content = '';
		$content .= GCS_Style_Loader::get_template( 'list-item-style' );
		$content .= GCS_Template_Loader::get_template( 'sermons-list', $args );

		return $content;
	}

	public function get_pagination( $total_pages ) {
		$nav = array( 'prev_link' => '', 'next_link' => '' );

		if ( ! $this->bool_att( 'sermon_remove_pagination' ) ) {
			$nav['prev_link'] = get_previous_posts_link( __( '<span>&larr;</span> Newer', 'gc-sermons' ), $total_pages );
			$nav['next_link'] = get_next_posts_link( __( 'Older <span>&rarr;</span>', 'gc-sermons' ), $total_pages );
		}

		return $nav;
	}

	public function get_wrap_classes() {
		$columns   = absint( $this->att( 'sermon_number_columns' ) );
		$columns   = $columns < 1 ? 1 : $columns;

		return $this->att( 'sermon_wrap_classes' ) . ' gc-' . $columns . '-cols gc-sermons-wrap';
	}

	public function map_sermon_args( $all_sermons ) {
		global $post;
		$sermons = array();

		$do_thumb        = ! $this->bool_att( 'sermon_remove_thumbnail' );
		$type_of_content = $this->att( 'sermon_content' );
		$thumb_size      = $this->att( 'series_thumbnail_size' );

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

		return $sermons;
	}

}
