<?php

class GCS_Sermon_Series_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Sermon_Series') );
	}

	function test_class_access() {
		$this->assertTrue( gc_sermons()->sermon_series instanceof GCS_Sermon_Series );
	}

  function test_taxonomy_exists() {
    $this->assertTrue( taxonomy_exists( 'gc-sermon-series' ) );
  }
}
