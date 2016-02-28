<?php

class GCS_Speaker_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Speaker') );
	}

	function test_class_access() {
		$this->assertTrue( gc_sermons()->speaker instanceof GCS_Speaker );
	}

  function test_taxonomy_exists() {
    $this->assertTrue( taxonomy_exists( 'gcs-speaker' ) );
  }
}
