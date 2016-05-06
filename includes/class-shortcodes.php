<?php
/**
 * GC Sermons Shortcodes
 * @version 0.1.3
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
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->play_button = new GCS_Shortcodes_Play_Button( $plugin );
	}

	/**
	 * Magic getter for our object. Allows getting but not setting.
	 *
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'play_button':
				return $this->{$field};
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
}
