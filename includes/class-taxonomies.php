<?php
/**
 * GC Sermons Taxonomies
 * @version 0.1.2
 * @package GC Sermons
 */

class GCS_Taxonomies {

	/**
	 * Instance of GCS_Series
	 *
	 * @var GCS_Series
	 */
	protected $series;

	/**
	 * Instance of GCS_Speaker
	 *
	 * @var GCS_Speaker
	 */
	protected $speaker;

	/**
	 * Instance of GCS_Topic
	 *
	 * @var GCS_Topic
	 */
	protected $topic;

	/**
	 * Instance of GCS_Tag
	 *
	 * @var GCS_Tag
	 */
	protected $tag;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $sermons GCS_Sermons object.
	 * @return void
	 */
	public function __construct( $sermons ) {
		$this->series  = new GCS_Series( $sermons );
		$this->speaker = new GCS_Speaker( $sermons );
		$this->topic   = new GCS_Topic( $sermons );
		$this->tag     = new GCS_Tag( $sermons );
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
			case 'series':
			case 'speaker':
			case 'topic':
			case 'tag':
				return $this->{$field};
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
}
