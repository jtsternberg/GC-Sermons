<?php

/**
 * Gets a GCS_Sermon_Post object from a post object or ID.
 *
 * @since  0.1.3
 *
 * @param  mixed $sermon         Post object or ID or (GCS_Sermon_Post object).
 * @param  bool  $throw_on_error Use if you have exception handling in place.
 *
 * @return GCS_Sermon_Post|false GCS_Sermon_Post object if successful
 */
function gc_get_sermon_post( $sermon = 0, $throw_on_error = false ) {
	if ( $sermon instanceof GCS_Sermon_Post ) {
		return $sermon;
	}

	$sermon = $sermon ? $sermon : get_the_id();

	try {

		$sermon = $sermon instanceof WP_Post ? $sermon : get_post( $sermon );
		$sermon = new GCS_Sermon_Post( $sermon );

	} catch ( Exception $e ) {
		if ( $throw_on_error ) {
			throw $e;
		}
		$sermon = false;
	}

	return $sermon;
}

/**
 * Gets a GCS_Series augmented term object from a term object or ID.
 *
 * @since  0.1.7
 *
 * @param  mixed $term Term object or ID.
 * @param  array       $args Array of arguments.
 *
 * @return GCS_Series|false GCS_Series object if successful.
 */
function gc_get_series_object( $term = 0, $args = array() ) {
	if ( ! $term ) {
		$term = is_tax( gc_sermons()->taxonomies->series->taxonomy() ) ? get_queried_object() : 0;
	}

	if ( $term ) {
		return gc_sermons()->taxonomies->series->get( $term, $args );
	}

	return false;
}

/**
 * Gets a GCS_Speaker augmented term object from a term object or ID.
 *
 * @since  0.1.7
 *
 * @param  mixed $term Term object or ID.
 * @param  array       $args Array of arguments.
 *
 * @return GCS_Speaker|false GCS_Speaker object if successful.
 */
function gc_get_speaker_object( $term = 0, $args = array() ) {
	if ( ! $term ) {
		$term = is_tax( gc_sermons()->taxonomies->speaker->taxonomy() ) ? get_queried_object() : 0;
	}

	if ( $term ) {
		return gc_sermons()->taxonomies->speaker->get( $term, $args );
	}

	return false;
}

/**
 * Gets a GCS_Topic augmented term object from a term object or ID.
 *
 * @since  0.1.7
 *
 * @param  mixed $term Term object or ID.
 * @param  array       $args Array of arguments.
 *
 * @return GCS_Topic|false GCS_Topic object if successful.
 */
function gc_get_topic_object( $term = 0, $args = array() ) {
	if ( ! $term ) {
		$term = is_tax( gc_sermons()->taxonomies->topic->taxonomy() ) ? get_queried_object() : 0;
	}

	if ( $term ) {
		return gc_sermons()->taxonomies->topic->get( $term, $args );
	}

	return false;
}

/**
 * Gets a GCS_Tag augmented term object from a term object or ID.
 *
 * @since  0.1.7
 *
 * @param  mixed $term Term object or ID.
 * @param  array       $args Array of arguments.
 *
 * @return GCS_Tag|false GCS_Tag object if successful.
 */
function gc_get_tag_object( $term = 0, $args = array() ) {
	if ( ! $term ) {
		$term = is_tax( gc_sermons()->taxonomies->tag->taxonomy() ) ? get_queried_object() : 0;
	}

	if ( $term ) {
		return gc_sermons()->taxonomies->tag->get( $term, $args );
	}

	return false;
}

/**
 * Gets a GCS_Scripture augmented term object from a term object or ID.
 *
 * @since  0.1.7
 *
 * @param  mixed $term Term object or ID.
 * @param  array       $args Array of arguments.
 *
 * @return GCS_Scripture|false GCS_Scripture object if successful.
 */
function gc_get_scripture_object( $term = 0, $args = array() ) {
	if ( ! $term ) {
		$term = is_tax( gc_sermons()->taxonomies->scripture->taxonomy() ) ? get_queried_object() : 0;
	}

	if ( $term ) {
		return gc_sermons()->taxonomies->scripture->get( $term, $args );
	}

	return false;
}

/**
 * Get's info for a series attached to the sermon.
 *
 * @since  0.1.3
 *
 * @param  mixed   $sermon          Post object or ID or (GCS_Sermon_Post object).
 * @param  boolean $args            Args array
 * @param  array   $get_series_args Args for GCS_Sermon_Post::get_series()
 *
 * @return string Sermon series info output.
 */
function gc_get_sermon_series_info( $sermon = 0, $args = array(), $get_series_args = array() ) {
	if ( ! ( $sermon = gc_get_sermon_post( $sermon ) ) ) {
		// If no sermon, bail.
		return '';
	}

	$args = wp_parse_args( $args, array(
		'remove_thumbnail'   => false,
		'remove_description' => true,
		'thumbnail_size'     => 'medium',
		'wrap_classes'       => '',
	) );

	$get_series_args['image_size'] = isset( $get_series_args['image_size'] )
		? $get_series_args['image_size']
		: $args['thumbnail_size'];

	if ( ! ( $series = $sermon->get_series( $get_series_args ) ) ) {
		// If no series, bail.
		return '';
	}

	$series->classes        = $args['wrap_classes'];
	$series->do_image       = ! $args['remove_thumbnail'] && $series->image;
	$series->do_description = ! $args['remove_description'] && $series->description;
	$series->url            = $series->term_link;

	$content = '';
	$content .= GCS_Style_Loader::get_template( 'list-item-style' );
	$content .= GCS_Template_Loader::get_template( 'list-item', (array) $series );

	// Not a list item.
	$content = str_replace( array( '<li', '</li' ), array( '<div', '</div' ), $content );

	return $content;
}

/**
 * Get's info for a speaker attached to the sermon.
 *
 * @since  0.1.3
 *
 * @param  mixed   $sermon           Post object or ID or (GCS_Sermon_Post object).
 * @param  boolean $args             Args array
 * @param  array   $get_speaker_args Args for GCS_Sermon_Post::get_speaker()
 *
 * @return string Sermon speaker info output.
 */
function gc_get_sermon_speaker_info( $sermon = 0, $args = array(), $get_speaker_args = array() ) {
	if ( ! ( $sermon = gc_get_sermon_post( $sermon ) ) ) {
		// If no sermon, bail.
		return '';
	}

	$args = wp_parse_args( $args, array(
		'remove_thumbnail'   => false,
		'thumbnail_size'     => 'medium',
		'wrap_classes'       => '',
	) );

	$get_speaker_args['image_size'] = isset( $get_speaker_args['image_size'] )
		? $get_speaker_args['image_size']
		: $args['thumbnail_size'];

	if ( ! ( $speaker = $sermon->get_speaker( $get_speaker_args ) ) ) {
		// If no speaker, bail.
		return '';
	}

	$sermon = gc_get_sermon_post( $sermon );

	// If no sermon or no sermon speaker, bail.
	if ( ! $sermon || ! ( $speaker = $sermon->get_speaker( $get_speaker_args ) ) ) {
		return '';
	}

	$speaker->image  = ! $args['remove_thumbnail'] ? $speaker->image : '';
	$speaker->classes = $args['wrap_classes'];

	$content = GCS_Template_Loader::get_template( 'sermon-speaker-info', (array) $speaker );

	return $content;
}

/**
 * Get's related links output for the sermon.
 *
 * @since  0.1.3
 *
 * @param  mixed   $sermon Post object or ID or (GCS_Sermon_Post object).
 *
 * @return string Sermon related links output.
 */
function gc_get_sermon_related_links( $sermon = 0 ) {
	$sermon = gc_get_sermon_post( $sermon );

	// If no sermon or no related links, bail.
	if ( ! $sermon || ! ( $links = $sermon->get_meta( 'gc_related_links' ) ) || ! is_array( $links ) ) {
		return '';
	}

	$content = GCS_Template_Loader::get_template( 'related-links', array(
		'title' => __( 'Related Links', 'gc-sermons' ),
		'links' => $links,
	) );

	return $content;
}

/**
 * Get's video player for the sermon.
 *
 * @since  0.1.3
 *
 * @param  mixed $sermon Post object or ID or (GCS_Sermon_Post object).
 * @param  mixed $args   Arguments passed to GCS_Sermon_Post::get_video_player().
 *
 * @return string Sermon video player output.
 */
function gc_get_sermon_video_player( $sermon = 0, $args = array() ) {
	$sermon = gc_get_sermon_post( $sermon );

	// If no sermon or no related links, bail.
	if ( ! $sermon || ! ( $video_player = $sermon->get_video_player( $args ) ) ) {
		return '';
	}

	return $video_player;
}

/**
 * Get's audio player for the sermon.
 *
 * @since  0.1.3
 *
 * @param  mixed $sermon Post object or ID or (GCS_Sermon_Post object).
 * @param  mixed $args   Arguments passed to GCS_Sermon_Post::get_audio_player().
 *
 * @return string Sermon audio player output.
 */
function gc_get_sermon_audio_player( $sermon = 0, $args = array() ) {
	$sermon = gc_get_sermon_post( $sermon );

	// If no sermon or no related links, bail.
	if ( ! $sermon || ! ( $audio_player = $sermon->get_audio_player( $args ) ) ) {
		return '';
	}

	return $audio_player;
}

/**
 * Gets the next search results page link when using the search widget.
 *
 * @since  0.1.5
 *
 * @param  int $total_pages Total number of pages.
 *
 * @return string           Next results page link, if there is a next page.
 */
function gc_search_get_next_results_link( $total_pages ) {
	$page = absint( gc__get_arg( 'results-page', 1 ) );
	$link = '';

	if ( ++$page <= $total_pages ) {
		$link = sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( add_query_arg( 'results-page', $page ) ),
			apply_filters( 'gc_next_results_page_link_attributes', '' ),
			__( 'Older <span>&rarr;</span>', 'gc-sermons' )
		);
	}

	return $link;
}

/**
 * Gets the previous search results page link when using the search widget.
 *
 * @since  0.1.5
 *
 * @return string           Next results page link, if there is a previous page.
 */
function gc_search_get_previous_results_link() {
	$page = absint( gc__get_arg( 'results-page', 1 ) );
	$link = '';

	if ( $page-- > 1 ) {
		$url = $page > 1 ? add_query_arg( 'results-page', $page ) : remove_query_arg( 'results-page' );
		$link = sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( $url ),
			apply_filters( 'gc_previous_results_page_link_attributes', '' ),
			__( '<span>&larr;</span> Newer', 'gc-sermons' )
		);
	}

	return $link;
}

/**
 * Helper function for getting $_GET values with optional default value.
 *
 * @since  0.1.5
 *
 * @param  string  $arg     Query arg to check
 * @param  mixed  $default  Optional default value. Defaults to null.
 *
 * @return mixed            Result of query var or default.
 */
function gc__get_arg( $arg, $default = null ) {
	return isset( $_GET[ $arg ] ) ? $_GET[ $arg ] : $default;
}
