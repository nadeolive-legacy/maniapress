<?php
/*
  Plugin Name: ManiaPress Event Publisher
  Plugin URI: http://code.google.com/p/maniapress/
  Description: This plugin is part of ManiaPress: a suite to display your WordPress install directly in Maniaplanet. This plugin automatically sends an Event to Maniaplanet's ManiaHome so that people that bookmarked your Manialink will be notified.
  Version: 0.3.1
  Author: Nadeo
  Author URI: http://www.nadeo.com
  Tags: ubisoft, nadeo, maniaplanet, trackmania, shootmania, questmania, manialink
  License: LGPL v3
 */

require_once __DIR__.'/maniapress-core/maniapress-core.php';

function maniapress_event_publisher_hook($post)
{
	$notification = new \Maniaplanet\WebServices\Notification();
	$notification->message = $post->post_title;
	$notification->link = get_permalink($post->ID);

	try
	{
		$maniahome = new \Maniaplanet\WebServices\ManiaHome(
				maniapress_get_option('api-username'),
				maniapress_get_option('api-password'),
				maniapress_get_option('manialink'));
		$maniahome->postPublicNotification($notification);
	}
	catch(\Maniaplanet\WebServices\Exception $e)
	{
		trigger_error($e->getHTTPStatusCode().' '.$e->getHTTPStatusMessage().' '.$e->getMessage(),
			E_USER_ERROR);
	}
}

add_action('new_to_publish', 'maniapress_event_publisher_hook', 10, 1);
add_action('draft_to_publish', 'maniapress_event_publisher_hook', 10, 1);
add_action('pending_to_publish', 'maniapress_event_publisher_hook', 10, 1);
add_action('future_to_publish', 'maniapress_event_publisher_hook', 10, 1);
?>