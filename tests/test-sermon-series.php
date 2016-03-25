<?php

class GCS_Series_Test extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Series') );
	}

	function test_class_access() {
		$this->assertTrue( gc_sermons()->series instanceof GCS_Series );
	}

  function test_taxonomy_exists() {
    $this->assertTrue( taxonomy_exists( 'gc-sermon-series' ) );
  }
}
