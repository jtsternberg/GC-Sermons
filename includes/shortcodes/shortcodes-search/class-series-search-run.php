<?php
/**
 * GC Series Search
 *
 * @version 0.1.4
 * @package GC Sermons
 */

class GCSS_Series_Search_Run extends GCSS_Series_Run {

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
	 * @param string      $search_query
	 * @param GCS_Sermons $sermons
	 * @param GCS_Series  $series
	 */
	public function __construct( $search_query, $atts, GCS_Sermons $sermons, GCS_Series $series ) {
		$this->search_query = $search_query;
		$this->current_page = absint( gc__get_arg( 'results-page', 1 ) );

		parent::__construct( $sermons, $series );

		$this->create_shortcode_object(
			shortcode_atts( $this->atts_defaults, $atts, $this->shortcode ),
			''
		);
	}

	public function get_search_results() {
		$args = $this->get_initial_query_args();

		$number = $args['number'];
		$offset = $args['offset'];

		// We want to get them all. (well, up to 1000).
		$args['number'] = 1000;
		$args['offset'] = 0;
		$args['hide_empty'] = true;
		$allterms = $this->series->search( sanitize_text_field( $this->search_query ), $args );
		$allterms = $this->orderby_post_date( $allterms );

		if ( empty( $allterms ) ) {
			return '';
		}

		$count = count( $allterms );
		$total_pages = ceil( $count / $number );
		$allterms    = array_splice( $allterms, $offset, $number );

		if ( $count > 900 ) {
			// Whoops, warn!
			trigger_error( 'You have more than 900 sermon series terms, and search queries which are requesting greater than 900 sermon series terms. You may want to look into additional performance optimizations.', E_USER_WARNING );
		}

		$args = $this->get_pagination( $total_pages );

		$args['terms']        = array( $this->augment_terms( $allterms ) );
		$args['remove_dates'] = true;
		$args['wrap_classes'] = $this->get_wrap_classes();

		$this->results = '';
		$this->results .= GCS_Style_Loader::get_template( 'list-item-style' );
		$this->results .= GCS_Template_Loader::get_template( 'series-list', $args );

		return $this->results;
	}

	public function get_initial_query_args() {
		$number = (int) $this->att( 'per_page', get_option( 'number' ) );
		$offset = ( ( $this->current_page - 1 ) * $number ) + $this->att( 'list_offset', 0 );

		return compact( 'number', 'offset' );
	}

	public function get_pagination( $total_pages ) {
		$nav = array( 'prev_link' => '', 'next_link' => '' );

		if ( ! $this->bool_att( 'remove_pagination' ) ) {
			$nav['prev_link'] = gc_search_get_previous_results_link();
			$nav['next_link'] = gc_search_get_next_results_link( $total_pages );
		}

		return $nav;
	}

	public function get_wrap_classes() {
		return parent::get_wrap_classes() . ' gc-series-search-wrap';
	}

	public function augment_terms( $allterms ) {
		$do_thumb = ! $this->bool_att( 'remove_thumbnail' );
		$do_desc  = ! $this->bool_att( 'remove_description' );

		foreach ( $allterms as $key => $term ) {
			$term = $this->get_term_data( $term );

			$term->do_image       = $do_thumb && $term->image;
			$term->do_description = $do_desc && $term->description;
			$term->url            = $term->term_link;

			$allterms[ $key ] = $term;
		}

		return $allterms;
	}

	public function orderby_post_date( $allterms ) {
		$ordered = array();
		foreach ( $allterms as $key => $term ) {
			$query = new WP_Query( array(
				'post_status'      => 'publish',
				'posts_per_page'   => 1,
				'no_found_rows'    => true,
				'cache_results'    => false,
				'suppress_filters' => true,
				'tax_query'        => array(
					array(
						'taxonomy' => $this->series->taxonomy(),
						'field'    => 'slug',
						'terms'    => $term->slug,
					),
				),
			) );

			if ( $query->have_posts() ) {
				$ordered[ strtotime( $query->post->post_date ) ] = $term;
			}

		}

		krsort( $ordered );

		return $ordered;
	}

}
