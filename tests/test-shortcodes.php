<?php

class GCS_Shortcodes_Test extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Shortcodes') );
	}

	function test_class_access() {
		gc_sermons()->hooks();
		$this->assertTrue( gc_sermons()->shortcodes instanceof GCS_Shortcodes );
	}
}
