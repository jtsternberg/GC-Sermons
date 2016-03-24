<?php

class GCS_Taxonomies_Test extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Taxonomies') );
	}

	function test_class_access() {
		$this->assertTrue( gc_sermons()->taxonomies instanceof GCS_Taxonomies );
	}
}
