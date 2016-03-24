<?php

class GCS_Pbs_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Pbs') );
	}

	function test_class_access() {
		$this->assertTrue( gc_sermons()->pbs instanceof GCS_Pbs );
	}
}
