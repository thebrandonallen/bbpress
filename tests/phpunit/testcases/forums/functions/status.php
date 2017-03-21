<?php

/**
 * Tests for bbPress forum statuses and types functions.
 *
 * @group forums
 * @group functions
 * @group status
 */
class BBP_Tests_Forums_Functions_Status extends BBP_UnitTestCase {

	/**
	 * @covers ::bbp_get_forum_statuses
	 * @todo   Implement test_bbp_get_forum_statuses().
	 */
	public function test_bbp_get_forum_statuses() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_get_forum_types
	 * @todo   Implement test_bbp_get_forum_types().
	 */
	public function test_bbp_get_forum_types() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_close_forum
	 * @todo   Implement test_bbp_close_forum().
	 */
	public function test_bbp_close_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_open_forum
	 * @todo   Implement test_bbp_open_forum().
	 */
	public function test_bbp_open_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_categorize_forum
	 * @todo   Implement test_bbp_categorize_forum().
	 */
	public function test_bbp_categorize_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_normalize_forum
	 * @todo   Implement test_bbp_normalize_forum().
	 */
	public function test_bbp_normalize_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_publicize_forum
	 * @todo   Implement test_bbp_publicize_forum().
	 */
	public function test_bbp_publicize_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_privatize_forum
	 * @todo   Implement test_bbp_privatize_forum().
	 */
	public function test_bbp_privatize_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_hide_forum
	 * @todo   Implement test_bbp_hide_forum().
	 */
	public function test_bbp_hide_forum() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers ::bbp_transition_forum_status
	 */
	public function test_bbp_transition_forum_status() {
		$f = $this->factory->forum->create();

		$result = bbp_transition_forum_status(
			bbp_get_public_status_id(),
			'new',
			bbp_get_forum( $f )
		);

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );
	}

	/**
	 * @covers ::bbp_transition_forum_status_new_public
	 */
	public function test_bbp_transition_forum_status_new_public() {
		$f = $this->factory->forum->create();

		$result = bbp_transition_forum_status_new_public(
			bbp_get_public_status_id(),
			'new',
			bbp_get_forum( $f )
		);

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );

		$this->assertEquals( 'new_public', bbp_get_post_transitioned_status( $f ) );
	}

	/**
	 * @covers ::bbp_transition_forum_status_new_moderated
	 */
	public function test_bbp_transition_forum_status_new_moderated() {
		$f = $this->factory->forum->create();

		$result = bbp_transition_forum_status_new_moderated(
			bbp_get_trash_status_id(),
			'new',
			bbp_get_forum( $f )
		);

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );

		$this->assertEquals( 'new_moderated', bbp_get_post_transitioned_status( $f ) );
	}

	/**
	 * @covers ::bbp_transition_forum_status_public
	 */
	public function test_bbp_transition_forum_status_public() {
		$f = $this->factory->forum->create();

		$result = bbp_transition_forum_status_public(
			bbp_get_public_status_id(),
			bbp_get_trash_status_id(),
			bbp_get_forum( $f )
		);

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );

		$this->assertEquals( 'public', bbp_get_post_transitioned_status( $f ) );
	}

	/**
	 * @covers ::bbp_transition_forum_status_moderated
	 */
	public function test_bbp_transition_forum_status_moderated() {
		$f = $this->factory->forum->create();

		$result = bbp_transition_forum_status_moderated(
			bbp_get_trash_status_id(),
			bbp_get_public_status_id(),
			bbp_get_forum( $f )
		);

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );

		$this->assertEquals( 'moderated', bbp_get_post_transitioned_status( $f ) );
	}

	/**
	 * @covers ::bbp_transitioned_forum_status_new_public
	 */
	public function test_bbp_transitioned_forum_status_new_public() {
		$f = $this->factory->forum->create();

		$result = bbp_transitioned_forum_status_new_public( $f );

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );
	}

	/**
	 * @covers ::bbp_transitioned_forum_status_new_moderated
	 */
	public function test_bbp_transitioned_forum_status_new_moderated() {
		$f = $this->factory->forum->create( array(
			'post_status' => bbp_get_trash_status_id(),
		) );

		$result = bbp_transitioned_forum_status_new_moderated( $f );

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );
	}

	/**
	 * @covers ::bbp_transitioned_forum_status_public
	 */
	public function test_bbp_transitioned_forum_status_public() {
		$f = $this->factory->forum->create();

		wp_trash_post( $f );
		wp_untrash_post( $f );

		$result = bbp_transitioned_forum_status_public( $f );

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );
	}

	/**
	 * @covers ::bbp_transitioned_forum_status_moderated
	 */
	public function test_bbp_transitioned_forum_status_moderated() {
		$f = $this->factory->forum->create();

		wp_trash_post( $f );

		$result = bbp_transitioned_forum_status_moderated( $f );

		// A true result means the action was added, as failures return false.
		$this->assertTrue( $result );
	}
}
