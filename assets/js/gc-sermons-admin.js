window.GCSermonsAdmin = window.GCSermonsAdmin || {};

( function( window, document, $, app, undefined ) {
	'use strict';

	var methodBackup = null;

	app.cache = function() {
		app.$ = {};
	};

	app.init = function() {
		app.cache();

		$( document.body )
			.on( 'keyup change', '.check-if-recent input[type="text"]', app.maybeToggle )
			.on( 'shortcode_button:open', app.showNotRecent );
	};

	app.maybeToggle = function( evt ) {
		var $this = $( evt.target );
		var value = $this.val();
		if ( ! value || '0' === value || 0 === value || 'recent' === value ) {
			$this.parents( '.cmb2-metabox' ).find( '.hide-if-not-recent' ).show();
		} else {
			$this.parents( '.cmb2-metabox' ).find( '.hide-if-not-recent' ).hide();
		}
	};

	app.showNotRecent = function() {
		$( '.scb-form-wrap .hide-if-not-recent' ).show();
	};

	$( app.init );

} )( window, document, jQuery, window.GCSermonsAdmin );
