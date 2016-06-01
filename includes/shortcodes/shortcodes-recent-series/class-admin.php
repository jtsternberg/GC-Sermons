<?php
/**
 * GC Sermons Recent Series Shortcode - Admin
 * @version 0.1.5
 * @package GC Sermons
 */

class GCSS_Recent_Series_Admin extends GCSS_Recent_Admin_Base {

	/**
	 * Shortcode prefix for field ids.
	 *
	 * @var   string
	 * @since 0.1.3
	 */
	protected $prefix = 'rcnt_series_';

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Recent Series', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Recent Series', 'gc-sermons' ),
			'icon'           => 'dashicons-images-alt',
			// 'mceView'        => true, // The future
		);
	}

	/**
	 * Adds fields to the button modal using CMB2
	 *
	 * @param $fields
	 * @param $button_data
	 *
	 * @return array
	 */
	function fields( $fields, $button_data ) {

		$fields[] = array(
			'name'            => __( 'Sermon ID', 'gc-sermons' ),
			'desc'            => __( 'Blank, "recent", or "0" will get the most recent sermon\'s series info. Otherwise enter a post ID. Click the magnifying glass to search for a Sermon post.', 'gc-sermons' ),
			'id'              => $this->prefix . 'sermon_id',
			'type'            => 'post_search_text',
			'post_type'       => $this->run->sermons->post_type(),
			'select_type'     => 'radio',
			'select_behavior' => 'replace',
			'row_classes'     => 'check-if-recent',
		);

		$fields[] = array(
			'name'        => __( 'Filter Most Recent Sermon By:', 'gc-sermons' ),
			'desc'        => __( 'If setting "Sermon ID" above to blank, "recent", or "0", this setting determines which type of most recent sermon to get the series info for.', 'gc-sermons' ),
			'type'        => 'select',
			'id'          => $this->prefix . 'recent',
			'default'     => $this->atts_defaults['recent'],
			'row_classes' => 'hide-if-not-recent',
			'options'     => array(
				'recent' => __( 'Most Recent', 'gc-sermons' ),
				'audio' => __( 'Most Recent with Audio', 'gc-sermons' ),
				'video' => __( 'Most Recent with Video', 'gc-sermons' ),
			),
		);

		$fields[] = array(
			'name'    => __( 'Remove Thumbnail', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => $this->prefix . 'remove_thumbnail',
			'default' => false,
		);

		$fields[] = array(
			'name'    => __( 'Thumbnail Size (if included)', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => $this->prefix . 'thumbnail_size',
			'default' => $this->atts_defaults['thumbnail_size'],
		);

		return $fields;
	}

}
