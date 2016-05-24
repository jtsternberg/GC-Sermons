<?php
/**
 * GC Sermons Shortcode Admin Base
 * @version 0.1.3
 * @package GC Sermons
 */

abstract class GCS_Shortcodes_Admin_Base extends WDS_Shortcode_Admin {
	/**
	 * Parent plugin class
	 *
	 * @var   GCS_Shortcodes_Base
	 * @since 0.1.0
	 */
	protected $run;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $run Main plugin object.
	 * @return void
	 */
	public function __construct( GCS_Shortcodes_Base $run ) {
		$this->run = $run;

		parent::__construct(
			$this->run->shortcode,
			GC_Sermons_Plugin::VERSION,
			$this->run->atts_defaults
		);
	}
}
