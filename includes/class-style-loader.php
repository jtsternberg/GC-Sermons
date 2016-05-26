<?php
/**
 * GC Sermons Style Loader
 *
 * @since NEXT
 * @package GC Sermons
 */

/**
 * GC Sermons Style Loader.
 *
 * @since NEXT
 */
class GCS_Style_Loader extends GCS_Template_Loader {

	/**
	 * Template file extension
	 *
	 * @var string
	 * @since NEXT
	 */
	protected $extension = '.css';

	/**
	 * Whether to force loading of an already-loaded template.
	 *
	 * @var bool
	 * @since NEXT
	 */
	protected $force = false;

	/**
	 * Keep CSS templates from loading more than once per page.
	 *
	 * @var array
	 * @since NEXT
	 */
	protected static $done = array();

	/**
	 * HTML view template loader constructor.
	 *
	 * @since  NEXT
	 *
	 * @param string  $css_template The template file name, relative to the includes/templates/ folder - with or without .php extension
	 * @param string  $name     The name of the specialised template. If array, will take the place of the $args.
	 * @param array   $args     An array of arguments to extract as variables into the template
	 * @param bool    $force    Whether to force loading of an already-loaded template.
	 *
	 * @return void
	 */
	public function __construct( $css_template, $name = null, array $args = array(), $force = false ) {
		$this->force = $force;
		parent::__construct( $css_template, $name, $args );
	}

	/**
	 * Loads the view and outputs it
	 *
	 * @since  NEXT
	 *
	 * @param  boolean $echo Whether to output or return the template
	 *
	 * @return string        Rendered template
	 */
	public function load( $echo = false ) {
		$content = '';

		// If we haven't done the template before (or we're forcing it)...
		if ( ! isset( self::$done[ $this->template ] ) || $this->force ) {
			// Then get the content.
			$content = parent::load( false );
		}

		// If we got content...
		if ( $content ) {

			$content = $this->format_css_tag( $content );

			// Ok, this one is done, don't load it again.
			self::$done[ $this->template ] = $this->template;
		}

		if ( ! $echo ) {
			return $content;
		}

		echo $content;
	}

	/**
	 * Minifies css and wraps in style tag.
	 *
	 * @since  NEXT
	 *
	 * @param  string  $content CSS content to format/wrap.
	 *
	 * @return string           Formatted CSS in style tag.
	 */
	protected function format_css_tag( $content ) {

		// Remove comments.
		$content = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content );

		// Remove space after colons.
		$content = str_replace( ': ', ':', $content );
		// Remove whitespace.
		$content = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $content );

		// Then wrap in a style tag.
		$content = "\n<style type=\"text/css\" media=\"screen\">\n{$content}\n</style>\n";

		return $content;
	}

	/**
	 * Get a rendered HTML view with the given arguments and return the view's contents.
	 *
	 * @since  NEXT
	 *
	 * @param string  $template The template file name, relative to the includes/templates/ folder
	 *                          - without .php extension
	 * @param string  $name     The name of the specialised template. If array, will take the place of the $args.
	 * @param array   $args     An array of arguments to extract as variables into the template
	 *
	 * @return string           Rendered template output
	 */
	public static function get_template( $template, $name = null, array $args = array() ) {
		$view = new self( $template, $name, $args );
		return $view->load();
	}

	/**
	 * Render an HTML view with the given arguments and output the view's contents.
	 *
	 * @since  NEXT
	 *
	 * @param  string $template The template file name, relative to the includes/templates/ folder
	 *                          - without .php extension
	 * @param  string $name     The name of the specialised template. If array, will take the place of the $args.
	 * @param  array  $args     An array of arguments to extract as variables into the template
	 *
	 * @return void
	 */
	public static function output_template( $template, $name = null, array $args = array() ) {
		$view = new self( $template, $name, $args );
		$view->load( 1 );
	}

}
