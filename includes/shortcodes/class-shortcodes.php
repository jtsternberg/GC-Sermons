<?php
/**
 * GC Sermons Shortcodes
 * @version 0.1.4
 * @package GC Sermons
 */

class GCS_Shortcodes {

	/**
	 * Instance of GCS_Shortcodes_Play_Button
	 *
	 * @var GCS_Shortcodes_Play_Button
	 * @since 0.1.0
	 */
	protected $play_button;

	/**
	 * Instance of GCS_Shortcodes_Sermons
	 *
	 * @var GCS_Shortcodes_Sermons
	 * @since 0.1.4
	 */
	protected $sermons;

	/**
	 * Instance of GCS_Shortcodes_Recent_Series
	 *
	 * @var GCS_Shortcodes_Recent_Series
	 * @since 0.1.4
	 */
	protected $recent_series;

	/**
	 * Instance of GCS_Shortcodes_Recent_Speaker
	 *
	 * @var GCS_Shortcodes_Recent_Speaker
	 * @since 0.1.4
	 */
	protected $recent_speaker;

	/**
	 * Instance of GCS_Shortcodes_Related_Links
	 *
	 * @var GCS_Shortcodes_Related_Links
	 * @since 0.1.4
	 */
	protected $related_links;

	/**
	 * Instance of GCS_Shortcodes_Video_Player
	 *
	 * @var GCS_Shortcodes_Video_Player
	 * @since 0.1.4
	 */
	protected $video_player;

	/**
	 * Instance of GCS_Shortcodes_Audio_Player
	 *
	 * @var GCS_Shortcodes_Audio_Player
	 * @since 0.1.4
	 */
	protected $audio_player;

	/**
	 * Instance of GCS_Shortcodes_Sermon_Search
	 *
	 * @var GCS_Shortcodes_Sermon_Search
	 * @since NEXT
	 */
	protected $search;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->play_button    = new GCS_Shortcodes_Play_Button( $plugin );
		$this->sermons        = new GCS_Shortcodes_Sermons( $plugin );
		$this->recent_series  = new GCS_Shortcodes_Recent_Series( $plugin );
		$this->recent_speaker = new GCS_Shortcodes_Recent_Speaker( $plugin );
		$this->series         = new GCS_Shortcodes_Series( $plugin );
		$this->related_links  = new GCS_Shortcodes_Related_Links( $plugin );
		$this->video_player   = new GCS_Shortcodes_Video_Player( $plugin );
		$this->audio_player   = new GCS_Shortcodes_Audio_Player( $plugin );
		$this->search         = new GCS_Shortcodes_Sermon_Search( $plugin );
	}

	/**
	 * Magic getter for our object. Allows getting but not setting.
	 *
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		return $this->{$field};
	}
}
