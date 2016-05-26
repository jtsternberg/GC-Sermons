<?php

/**
 * Gets a GCS_Sermon_Post object from a post object or ID.
 *
 * @since  NEXT
 *
 * @param  mixed $sermon Post object or ID or (GCS_Sermon_Post object).
 *
 * @return GCS_Sermon_Post|false GCS_Sermon_Post object if successful
 */
function gc_get_sermon_post( $sermon = 0 ) {
	if ( $sermon instanceof GCS_Sermon_Post ) {
		return $sermon;
	}

	$sermon = $sermon ? $sermon : get_the_id();

	try {
		if ( $sermon instanceof WP_Post ) {
			$sermon = new GCS_Sermon_Post( $sermon );
		} elseif ( is_numeric( $sermon ) ) {
			$sermon = new GCS_Sermon_Post( get_post( $sermon ) );
		}
	} catch ( Exception $e ) {
		$sermon = false;
	}

	return $sermon;
}

/**
 * Get's info for a series attached to the sermon.
 *
 * @since  NEXT
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

	$content = '';
	$content .= GCS_Style_Loader::get_template( 'series-list-style' );
	$content .= GCS_Template_Loader::get_template( 'series-item', (array) $series );

	// Not a list item.
	$content = str_replace( array( '<li', '</li' ), array( '<div', '</div' ), $content );

	return $content;
}

/**
 * Get's info for a speaker attached to the sermon.
 *
 * @since  NEXT
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
 * @since  NEXT
 *
 * @param  mixed   $sermon Post object or ID or (GCS_Sermon_Post object).
 *
 * @return string Sermon speaker info output.
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

