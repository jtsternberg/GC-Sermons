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
	 */
	protected $play_button;

	/**
	 * Instance of GCS_Shortcodes_Sermons
	 *
	 * @var GCS_Shortcodes_Sermons
	 */
	protected $sermons;

	/**
	 * Instance of GCS_Shortcodes_Recent_Series
	 *
	 * @var GCS_Shortcodes_Recent_Series
	 */
	protected $series_info;

	/**
	 * Instance of GCS_Shortcodes_Recent_Speaker
	 *
	 * @var GCS_Shortcodes_Recent_Speaker
	 */
	protected $speaker_info;

	/**
	 * Instance of GCS_Shortcodes_Related_Links
	 *
	 * @var GCS_Shortcodes_Related_Links
	 */
	protected $related_links;

	/**
	 * Instance of GCS_Shortcodes_Video_Player
	 *
	 * @var GCS_Shortcodes_Video_Player
	 */
	protected $video_player;

	/**
	 * Instance of GCS_Shortcodes_Audio_Player
	 *
	 * @var GCS_Shortcodes_Audio_Player
	 */
	protected $audio_player;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->play_button   = new GCS_Shortcodes_Play_Button( $plugin );
		$this->sermons       = new GCS_Shortcodes_Sermons( $plugin );
		$this->series_info   = new GCS_Shortcodes_Recent_Series( $plugin );
		$this->series_info   = new GCS_Shortcodes_Recent_Speaker( $plugin );
		$this->series        = new GCS_Shortcodes_Series( $plugin );
		$this->related_links = new GCS_Shortcodes_Related_Links( $plugin );
		$this->video_player  = new GCS_Shortcodes_Video_Player( $plugin );
		$this->audio_player  = new GCS_Shortcodes_Audio_Player( $plugin );
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
