<?php
/**
 * GC Sermons Taxonomies
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_Taxonomies {

	/**
	 * Instance of GCS_Sermon_Series
	 *
	 * @var GCS_Sermon_Series
	 */
	protected $sermon_series;

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
		$this->sermon_series = new GCS_Sermon_Series( $sermons );
		$this->speaker = new GCS_Speaker( $sermons );
		$this->topic = new GCS_Topic( $sermons );
		$this->tag = new GCS_Tag( $sermons );
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
			case 'sermon_series':
			case 'speaker':
			case 'topic':
			case 'tag':
				return $this->{$field};
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
}
