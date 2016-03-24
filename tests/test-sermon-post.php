<?php

class GCS_Sermon_Post_Test extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GCS_Sermon_Post') );
	}

	function test_class_access() {
		$sermons = gc_sermons()->sermons;
		$this->assertFalse( $sermons->most_recent() );
		$this->assertEquals( 'gc-sermons', $sermons->post_type() );

		// Create a post
		$this->factory->post->create( array(
			'post_type' => $sermons->post_type(),
		) );

		$sermons->flush = true;

		// And check if we found an instance
		$this->assertTrue( $sermons->most_recent() instanceof GCS_Sermon_Post );
	}
}
