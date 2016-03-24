<?php

class GCS_PBS_Run_Test extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_PBS_Run') );
	}
}
