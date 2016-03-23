<?php

class GCS_Play_Button_Shortcode_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Play_Button_Shortcode') );
	}

	function test_class_access() {
		$this->assertTrue( gc_sermons()->play-button-shortcode instanceof GCS_Play_Button_Shortcode );
	}
}
