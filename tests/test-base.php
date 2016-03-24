<?php

class BaseTest extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GC_Sermons_Plugin') );
	}

	function test_get_instance() {
		$this->assertTrue( gc_sermons() instanceof GC_Sermons_Plugin );
	}
}
