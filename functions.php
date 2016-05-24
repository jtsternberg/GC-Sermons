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
 * @param  boolean $do_thumbnail    Whether to ouput thumbnail.
 * @param  array   $get_series_args Args for GCS_Sermon_Post::get_series()
 *
 * @return string Sermon series info output.
 */
function gc_get_sermon_series_info( $sermon = 0, $do_thumbnail = true, $get_series_args = array() ) {
	$sermon = gc_get_sermon_post( $sermon );

	// If no sermon or no sermon series, bail.
	if ( ! $sermon || ! ( $series = $sermon->get_series( $get_series_args ) ) ) {
		return '';
	}

	$content = GCS_Template_Loader::get_template( 'sermon-series-info', array(
		'thumbnail'    => $do_thumbnail && $series->image ? $series->image : '',
		'series_url'   => $series->term_link,
		'series_title' => $series->name,
	) );

	return $content;
}

/**
 * Get's info for a speaker attached to the sermon.
 *
 * @since  NEXT
 *
 * @param  mixed   $sermon           Post object or ID or (GCS_Sermon_Post object).
 * @param  boolean $do_thumbnail     Whether to ouput thumbnail.
 * @param  array   $get_speaker_args Args for GCS_Sermon_Post::get_speaker()
 *
 * @return string Sermon speaker info output.
 */
function gc_get_sermon_speaker_info( $sermon = 0, $do_thumbnail = true, $get_speaker_args = array() ) {
	$sermon = gc_get_sermon_post( $sermon );

	// If no sermon or no sermon speaker, bail.
	if ( ! $sermon || ! ( $speaker = $sermon->get_speaker( $get_speaker_args ) ) ) {
		return '';
	}

	$content = GCS_Template_Loader::get_template( 'sermon-speaker-info', array(
		'thumbnail'    => $do_thumbnail && $speaker->image ? $speaker->image : '',
		'speaker_url'   => $speaker->term_link,
		'speaker_title' => $speaker->name,
	) );

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

