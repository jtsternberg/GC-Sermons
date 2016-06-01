<?php
/**
 * GC Sermons Search
 *
 * @version 0.1.4
 * @package GC Sermons
 */

class GCSS_Sermons_Search_Run extends GCSS_Sermons_Run {

	/**
	 * The current search query.
	 *
	 * @var string
	 */
	protected $search_query = '';

	/**
	 * The current search results page number.
	 *
	 * @var int
	 */
	public $current_page = 0;

	/**
	 * The total number of search results pages.
	 *
	 * @var int
	 */
	public $total_pages = 0;

	/**
	 * Results of the call to shortcode_callback.
	 *
	 * @var mixed
	 */
	public $results = '';

	/**
	 * Constructor
	 *
	 * @since 0.1.3
	 *
	 * @param string         $search_query
	 * @param GCS_Sermons    $sermons
	 * @param GCS_Taxonomies $taxonomies
	 */
	public function __construct( $search_query, GCS_Sermons $sermons, GCS_Taxonomies $taxonomies ) {
		$this->search_query = $search_query;
		$this->current_page = absint( gc__get_arg( 'results-page', 1 ) );

		parent::__construct( $sermons, $taxonomies );
	}

	public function get_search_results( $atts = array(), $content = '' ) {
		add_filter( 'gcs_get_sermons_args', array( $this, 'filter_sermon_args' ) );

		$this->results = parent::shortcode_callback( $atts, $content );

		remove_filter( 'gcs_get_sermons_args', array( $this, 'filter_sermon_args' ) );

		return $this->results;
	}

	public function filter_sermon_args( $args ) {
		$args['s'] = sanitize_text_field( $this->search_query );
		return $args;
	}

	/**
	 * Make this method applicable.
	 *
	 * @since  [since]
	 *
	 * @return [type]  [description]
	 */
	public function get_initial_query_args() {
		$posts_per_page = (int) $this->att( 'per_page', get_option( 'posts_per_page' ) );
		$paged          = $this->current_page;
		$offset         = ( ( $paged - 1 ) * $posts_per_page ) + $this->att( 'list_offset', 0 );

		return compact( 'posts_per_page', 'paged', 'offset' );
	}

	protected function get_pagination( $total_pages ) {
		$this->total_pages = $total_pages;
		$nav = array( 'prev_link' => '', 'next_link' => '' );

		if ( ! $this->bool_att( 'remove_pagination' ) ) {
			$nav['prev_link'] = gc_search_get_previous_results_link();
			$nav['next_link'] = gc_search_get_next_results_link( $total_pages );
		}

		return $nav;
	}

	protected function get_wrap_classes() {
		return parent::get_wrap_classes() . ' gc-sermons-search-wrap';
	}

}
