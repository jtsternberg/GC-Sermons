<?php
/**
 * GC Sermons Async
 * @version 0.1.6
 * @package GC Sermons
 */

class GCS_Async extends WP_Async_Task {

	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since 0.1.1
	 */
	protected $plugin = null;

	/**
	 * @var string
	 */
	protected $action = 'set_object_terms';

	/**
	 * Constructor
	 *
	 * @since  0.1.1
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		parent::__construct();
	}

	/**
	 * Prepare data for the asynchronous request
	 *
	 * @throws Exception If for any reason the request should not happen
	 *
	 * @param array $data An array of data sent to the hook
	 *
	 * @return array
	 */
	protected function prepare_data( $data ) {
		$object_id = $data[0];
		$taxonomy = $data[3];

		if ( $this->plugin->sermons->post_type() !== get_post_type( $object_id ) ) {
			throw new Exception( 'We only want async tasks for sermons' );
		}

		return compact( 'object_id', 'taxonomy' );
	}

	/**
	 * Run the async task action
	 */
	protected function run_action() {
		if ( isset( $_POST['object_id'], $_POST['taxonomy'] ) ) {
			do_action( 'wp_async_set_sermon_terms', $_POST['object_id'], $_POST['taxonomy'] );
		}
	}

}
