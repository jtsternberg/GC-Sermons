<?php
/**
 * GC Sermons Search Shortcode
 *
 * @todo Use Javascript/Ajax pagination/filtering.
 *
 * @version 0.1.5
 * @package GC Sermons
 */
class GCS_Shortcodes_Sermon_Search_Run extends GCS_Shortcodes_Run_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_sermons_search';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'search'             => '',
		'per_page'           => 10, // Will use WP's per-page option.
		'content'            => 'excerpt',
		'remove_thumbnail'   => false,
		'thumbnail_size'     => 'medium',
		'number_columns'     => 2,

		// No admin UI.

		// Sermon specific
		'list_offset'        => 0,
		'wrap_classes'       => '',
		'remove_pagination'  => false,
		'related_speaker'    => 0,
		'related_series'     => 0,

		// Series specific
		'remove_description' => true,

		'sermon_search_args' => array(),
		'series_search_args' => array(),
	);

	/**
	 * GCS_Sermons object
	 *
	 * @var   GCS_Sermons
	 * @since 0.1.0
	 */
	public $taxonomies;

	/**
	 * The current search query.
	 *
	 * @var string
	 */
	protected $search_query = '';

	/**
	 * Constructor
	 *
	 * @since 0.1.3
	 *
	 * @param GCS_Sermons $sermons
	 * @param GCS_Taxonomies $taxonomies
	 */
	public function __construct( GCS_Sermons $sermons, GCS_Taxonomies $taxonomies ) {

		$this->taxonomies = $taxonomies;
		parent::__construct( $sermons );
	}

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		$this->search_query = gc__get_arg( 'sermon-search', '' );
		$show_results = gc__get_arg( 'results-for', '' );

		$series_slug = $this->taxonomies->series->taxonomy();
		$cpt_slug    = $this->sermons->post_type();

		$to_search   = $this->att( 'search' );
		$search_both = ! $to_search;

		$format      = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';
		$format      = apply_filters( 'search_form_format', $format );
		$template    = 'searchform' . ( 'xhtml' === $format ? '-' . $format : '' );

		$args = array(
			'search_query'  => $this->search_query,
			'action_url'    => add_query_arg( 'sermon-search', 1 ),
			'sermons_value' => $cpt_slug,
			'sermons_label' => $this->sermons->post_type( 'singular' ),
			'series_value'  => $series_slug,
			'series_label'  => $this->taxonomies->series->taxonomy( 'singular' ),
			'show_filter'   => $search_both,
			'show_results'  => $show_results,
		);

		$content = GCS_Template_Loader::get_template( $template, $args );

		// If a search was performed, let's get the results.
		if ( $this->search_query ) {

			if ( strlen( $this->search_query ) < 3 ) {
				// Uh-oh, we need at least 3 characters.
				$content .= GCS_Template_Loader::get_template( 'search-query-error' );
			} else {

				$show_sermon_results = ! $show_results || $cpt_slug === $show_results;
				$show_series_results = ! $show_results || $series_slug === $show_results;

				$search_sermons = $search_both || in_array( $to_search, array( 'sermons' ), 1 );
				$search_series  = $search_both || in_array( $to_search, array( 'series' ), 1 );

				if ( $search_sermons && $show_sermon_results ) {
					$content .= $this->sermon_search_results();
				}

				if ( $search_series && $show_series_results ) {
					$content .= $this->series_search_results();
				}

			}
		}

		return $content;
	}

	protected function sermon_search_results() {
		$atts = $this->atts;
		unset( $atts['search'] );
		unset( $atts['wrap_classes'] );

		$atts = wp_parse_args( $atts, $this->att( 'sermon_search_args', array() ) );

		$search = new GCSS_Sermons_Search_Run( $this->search_query, $this->sermons, $this->taxonomies );
		$search->get_search_results( $atts );

		return GCS_Template_Loader::get_template( 'sermon-search-results', array(
			'wrap_classes'  => $this->att( 'wrap_classes' ),
			'results'       => empty( $search->results ) ? __( 'No results.', 'gc-sermons' ) : $search->results,
			'search_notice' => sprintf(
				__( '%s search results for: <em>%s</em>', 'gc-sermons' ),
				$this->sermons->post_type( 'singular' ),
				esc_html( $this->search_query )
			),
		) );

		return $search;
	}

	protected function series_search_results() {
		$atts = $this->atts;
		unset( $atts['search'] );
		unset( $atts['wrap_classes'] );

		$atts = wp_parse_args( $atts, $this->att( 'series_search_args', array() ) );

		$search = new GCSS_Series_Search_Run( $this->search_query, $atts, $this->sermons, $this->taxonomies->series );
		$search->get_search_results();

		return GCS_Template_Loader::get_template( 'series-search-results', array(
			'wrap_classes'  => $this->att( 'wrap_classes' ),
			'results'       => empty( $search->results ) ? __( 'No results.', 'gc-sermons' ) : $search->results,
			'search_notice' => sprintf(
				__( '%s search results for: <em>%s</em>', 'gc-sermons' ),
				$this->taxonomies->series->taxonomy( 'singular' ),
				esc_html( $this->search_query )
			),
		) );
	}

}
