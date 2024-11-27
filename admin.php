<?php
# Called only in /wp-admin/edit.php* pages
add_action( 'load-edit.php', function()
{
	# Not our post_type, bail out
	if ( get_current_screen()->id != 'edit-page' )
		return;

	# Transient cache for pages IDs and Count
	# used in both hooks bellow
	do_cache_wpse_77721();

	# Check if our Query Var is defined and activate pre_get_posts
	if( isset( $_GET['account'] ) || isset( $_GET['checkout'] ) )
		add_action( 'pre_get_posts', 'pre_posts_wpse_77721' );

	add_filter( 'views_edit-page', 'custom_links_wpse_77721' );
});

/**
 * Only display comments of specific post_id
 */
function pre_posts_wpse_77721( $query )
{
	# Just to play safe, but I think the hook is quite specificaly called
	if( !is_admin() )
		return $query;

	global $pagenow;

	# If there's no cache, bail out
	$cache = get_transient( 'custom_page_links' );
	if( !$cache )
		return $query;

	# Define the IDs we want to query
	if( isset( $_GET['account'] ) )
		$ids = $cache['account']['ids'];
	else
		$ids = $cache['checkout']['ids'];

	# Here, too, just playing safe
	if( 'edit.php' == $pagenow && ( get_query_var('post_type') && 'page' == get_query_var('post_type') ) )
		$query->set( 'post__in', $ids );

	return $query;
}

/**
 * Add link to specific post comments with counter
 */
function custom_links_wpse_77721( $status_links )
{
	$cache = get_transient( 'custom_page_links' );
	$count_checkout = sprintf(
		'<span class="count">(%s)</span>',
		$cache['checkout']['count']
	);
	$count_account = sprintf(
		'<span class="count">(%s)</span>',
		$cache['account']['count']
	);

	$link_account = 'edit.php?post_type=page&account=1670';
	$link_checkout = 'edit.php?post_type=page&checkout=1669';
	$link_all = '<a href="edit.php?post_type=page">All</a>';
	$separator = 'CUSTOM LINKS &#x27BD;';

	if( isset( $_GET['checkout'] ) )
	{
		$status_links['all'] = $link_all;
		$status_links['my_sep'] = $separator;
		$status_links['account'] = "<a href='$link_account'>My Account $count_account</a>";
		$status_links['checkout'] = "<a href='$link_checkout' class='current'>Checkout $count_checkout</a>";
	}
	elseif( isset( $_GET['account'] ) )
	{
		$status_links['all'] = $link_all;
		$status_links['my_sep'] = $separator;
		$status_links['account'] = "<a href='$link_account' class='current'>My Account $count_account</a>";
		$status_links['checkout'] = "<a href='$link_checkout'>Checkout $count_checkout</a>";
	}
	else
	{
		$status_links['my_sep'] = $separator;
		$status_links['account'] = "<a href='$link_account'>My Account $count_account</a>";
		$status_links['checkout'] = "<a href='$link_checkout'>Checkout $count_checkout</a>";
	}

	return $status_links;
}

/**
 * Makes the query once every hour
 * holds the Parent and Children ID, plus the Children total pages count
 */
function do_cache_wpse_77721()
{
	if( !get_transient( 'custom_page_links' ) )
	{
		# Page 1
		$checkout_posts = get_children( 'post_parent=1669&post_type=page' );
		// To include the parent ID in the query
		$c_ids = array( '1669' );
		// Grab the children IDs
		foreach( $checkout_posts as $check )
			$c_ids[] = $check->ID;
		$checkout = array(
			'ids' => $c_ids,
			'count' => count( $checkout_posts )
		);

		# Page 2
		$account_posts = get_children( 'post_parent=1670&post_type=page' );
		$a_ids = array( '1670' );
		foreach( $account_posts as $acc )
			$a_ids[] = $acc->ID;
		$account = array(
			'ids' => $a_ids,
			'count' => count( $account_posts )
		);

		# Set transient
		$transient = array(
			'checkout' => $checkout,
			'account' => $account
		);
		set_transient( 'custom_page_links', $transient, 60*60 );
	}
}