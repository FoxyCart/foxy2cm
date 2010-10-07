<?php
require 'CMBase.php';

/**
 * Given a user, the name of a CampaignMonitor list, and the CampaignMonitor API credentials,
 * subscribe user to named list. <b>Will die() on any errors from CampaignMonitor.</b>
 *
 * @param array $user	Contains the information about the user to subscribe.  Keys:
 *                     'first_name'         => string; the user's first name
 *                     'last_name'          => string; the user's last name
 *                     'email'              => string; the user's email address
 *
 * @param string $list_name     The name of the list to subscribe to.
 *
 * @param string $api_key       The Campaign Monitor API key, go to:
 *                              http://www.campaignmonitor.com/api/required/ to find this and the next few keys.
 * @param string $client_id     Campaign Monitor client ID
 * @param string $campaign_id   Campaign Monitor campaign ID
 * @param string $list_id       Campaign Monitor list ID
 *
 * @return  boolean             Returns true if member subscribed to the list.
 */
function subscribe_user_to_list($user, $api_key, $client_id, $list_id) {
  $cm = new CampaignMonitor($api_key, $client_id, null, $list_id);

  $cm or die("Unable to connect to CampaignMonitor API, error: ".$cm->errorMessage);

  $result = $cm->subscriberAddWithCustomFields($user['email'],
   $user['email'], $user['first_name'] . ' ' . $user['last_name']);

  ($result['Code'] == 0 or preg_match("/already subscribed/i", $result['Message']) or preg_match("/Email Address exists/i", $result['Message'])) or
	 die("Unable to load call subscriberAddWithCustomFields()! " .
         "CampaignMonitor reported error:\n\tCode=" . $result['Code'] .
         "\n\tMsg=" . $result['Message'] . "\n");

  return true;    // All's well.
}
?>
