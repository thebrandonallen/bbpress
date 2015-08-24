<?php

/**
 * @group extend
 * @group buddypress
 * @group groups
 * @group subscriptions
 */
class BBP_Tests_Extend_BuddyPress_Groups_Subscriptions extends BBP_UnitTestCase {

	protected $group_extension = null;
	protected $old_current_filter = null;
	protected $current_filter = null;
	protected $group;
	protected $group_id;

	public function setUp() {
		parent::setUp();

		if ( ! function_exists( 'buddypress' ) ) {
			return;
		}

		$this->group_extension = new BBP_Forums_Group_Extension;

		$this->group_id = $this->bp_factory->group->create( array( 'status' => 'public' ) );
		$this->group = groups_get_group( array( 'group_id' => $this->group_id ) );
	}

	public function tearDown() {
		parent::tearDown();

		$this->group_extension = null;
	}

	protected function set_current_filter( $filter = array() ) {
		global $wp_current_filter;
		$this->old_current_filter = $wp_current_filter;
		$wp_current_filter[] = $filter;
	}

	protected function restore_current_filter() {
		global $wp_current_filter;
		$wp_current_filter = $this->old_current_filter;
	}

	/**
	 * Copied from `BBP_Forums_Group_Extension::new_forum()`.
	 */
	private function attach_forum_to_group( $forum_id, $group_id ) {
		bbp_add_forum_id_to_group( $group_id, $forum_id );
		bbp_add_group_id_to_forum( $forum_id, $group_id );
	}

	/**
	 * @covers BBP_Forums_Group_Extension::leave_group_unsubscribe
	 */
	public function test_leave_group_unsubscribe_when_user_banned_from_group() {
		$this->group->status = 'public';
		$this->group->save();

		$old_current_user = get_current_user_id();
		$u1 = $this->group->creator_id;
		$u2 = $this->factory->user->create( array(
			'user_login' => 'bbPress Groups User',
			'user_pass'  => 'password',
			'user_email' => 'bbp_user@example.org',
		) );
		wp_update_user( array( 'ID' => $u1, 'role' => 'administrator', ) );
		BP_UnitTestCase::set_current_user( $u1 );
		BP_UnitTestCase::add_user_to_group( $u2, $this->group_id );

		$f = $this->factory->forum->create();
		$t = $this->factory->topic->create( array(
			'post_parent' => $f,
			'post_author' => $u1,
		) );
		$this->attach_forum_to_group( $f, $this->group_id );

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		$this->set_current_filter( 'groups_ban_member' );

		$this->group_extension->leave_group_unsubscribe( $this->group_id, $u2 );

		$this->assertFalse( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertFalse( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		$this->group->status = 'private';
		$this->group->save();

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		$this->group_extension->leave_group_unsubscribe( $this->group_id, $u2 );

		$this->assertFalse( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertFalse( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		$this->group->status = 'hidden';
		$this->group->save();

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		add_filter( 'bbp_before_group_unsubscribe_parse_args', create_function( '', "
			return array(
				'leave' => array(
					'hidden'  => true,
					'private' => true,
					'public'  => true,
				),
				'remove' => array(
					'hidden'  => true,
					'private' => true,
					'public'  => true,
				),
				'ban' => array(
					'hidden'  => false,
					'private' => true,
					'public'  => true,
				),
			);"
		) );

		$this->group_extension->leave_group_unsubscribe( $this->group_id, $u2 );

		$this->restore_current_filter();

		$this->assertTrue( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertTrue( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		// Restore old user
		BP_UnitTestCase::set_current_user( $old_current_user );
	}

	/**
	 * @covers BBP_Forums_Group_Extension::leave_group_unsubscribe
	 */
	public function test_leave_group_unsubscribe_when_user_removed_from_group() {
		$this->group->status = 'public';
		$this->group->save();

		$old_current_user = get_current_user_id();
		$u1 = $this->group->creator_id;
		$u2 = $this->factory->user->create( array(
			'user_login' => 'bbPress Groups User',
			'user_pass'  => 'password',
			'user_email' => 'bbp_user@example.org',
		) );
		wp_update_user( array( 'ID' => $u1, 'role' => 'administrator', ) );
		BP_UnitTestCase::set_current_user( $u1 );
		BP_UnitTestCase::add_user_to_group( $u2, $this->group_id );

		$f = $this->factory->forum->create();
		$t = $this->factory->topic->create( array(
			'post_parent' => $f,
			'post_author' => $u1,
		) );
		$this->attach_forum_to_group( $f, $this->group_id );

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		$this->set_current_filter( 'groups_remove_member' );

		$this->group_extension->leave_group_unsubscribe( $this->group_id, $u2 );

		$this->assertFalse( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertFalse( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		$this->group->status = 'private';
		$this->group->save();

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		$this->group_extension->leave_group_unsubscribe( $this->group_id, $u2 );

		$this->assertFalse( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertFalse( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		$this->group->status = 'hidden';
		$this->group->save();

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		add_filter( 'bbp_before_group_unsubscribe_parse_args', create_function( '', "
			return array(
				'leave' => array(
					'hidden'  => true,
					'private' => true,
					'public'  => true,
				),
				'remove' => array(
					'hidden'  => false,
					'private' => true,
					'public'  => true,
				),
				'ban' => array(
					'hidden'  => true,
					'private' => true,
					'public'  => true,
				),
			);"
		) );

		$this->group_extension->leave_group_unsubscribe( $this->group_id, $u2 );

		$this->restore_current_filter();

		$this->assertTrue( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertTrue( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		// Restore old user
		BP_UnitTestCase::set_current_user( $old_current_user );
	}

	/**
	 * @covers BBP_Forums_Group_Extension::leave_group_unsubscribe
	 */
	public function test_leave_group_unsubscribe_when_user_leaves_group() {

		// See #BP6597.
		buddypress()->current_action = 'leave-group';

		$g = $this->bp_factory->group->create( array( 'status' => 'public' ) );
		$group = groups_get_group( array( 'group_id' => $g ) );

		$old_current_user = get_current_user_id();
		$u1 = $group->creator_id;
		$u2 = $this->factory->user->create( array(
			'user_login' => 'bbPress Groups User',
			'user_pass'  => 'password',
			'user_email' => 'bbp_user@example.org',
		) );
		BP_UnitTestCase::set_current_user( $u2 );
		BP_UnitTestCase::add_user_to_group( $u2, $g );

		$f = $this->factory->forum->create();
		$t = $this->factory->topic->create( array(
			'post_parent' => $f,
			'post_author' => $u1,
		) );
		$this->attach_forum_to_group( $f, $g );

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		$this->group_extension->leave_group_unsubscribe( $g, $u2 );

		$this->assertFalse( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertFalse( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		$group->status = 'private';
		$group->save();

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		$this->group_extension->leave_group_unsubscribe( $g, $u2 );

		$this->assertFalse( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertFalse( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		$group->status = 'hidden';
		$group->save();

		bbp_add_user_forum_subscription( $u2, $f );
		bbp_add_user_topic_subscription( $u2, $t );

		add_filter( 'bbp_before_group_unsubscribe_parse_args', create_function( '', "
			return array(
				'leave' => array(
					'hidden'  => false,
					'private' => true,
					'public'  => true,
				),
				'remove' => array(
					'hidden'  => true,
					'private' => true,
					'public'  => true,
				),
				'ban' => array(
					'hidden'  => true,
					'private' => true,
					'public'  => true,
				),
			);"
		) );

		$this->group_extension->leave_group_unsubscribe( $g, $u2 );

		$this->assertTrue( bbp_is_user_subscribed_to_forum( $u2, $f ) );
		$this->assertTrue( bbp_is_user_subscribed_to_topic( $u2, $t ) );

		// Reset BP current action
		buddypress()->current_action = '';

		// Restore old user
		BP_UnitTestCase::set_current_user( $old_current_user );
	}
}
