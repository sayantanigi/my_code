<?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=
require_once 'vendor/autoload.php';
require_once 'function.php';
require_once 'config.php';
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=
if (!isset($_GET['code'])) {
	//++++++++++++++++++++++++++++++++++++++++++++++
    //Starting Of The ALL The Things
	//++++++++++++++++++++++++++++++++++++++++++++++
    $url = "https://zoom.us/oauth/authorize?response_type=code&client_id=" . CLIENT_ID . "&redirect_uri=" . REDIRECT_URI;
    echo '<a href="' . $url . '">Login with Zoom</a>';
} else {
	//++++++++++++++++++++++++++++++++++++++++++++++
	//Get The Access Token//
	//++++++++++++++++++++++++++++++++++++++++++++++
    $get_token = get_access_token($_GET['code']);
    //++++++++++++++++++++++++++++++++++++++++++++++
    //Create The Meeting//
	//++++++++++++++++++++++++++++++++++++++++++++++
    $get_new_meeting_details = create_a_zoom_meeting([
        'topic'         => 'Let Learn Zoom API Intigration In PHP',
        'type'          => 2,
        'start_time'    => date('Y-m-dTh:i:00') . 'Z',
        'password'      => mt_rand(),
        'token'         => $get_token['access_token'],
        'refresh_token' => $get_token['refresh_token'],
    ]);
    if ($get_new_meeting_details['msg'] == 'success') {
        echo $get_new_meeting_details['response']['uuid'];
    } else {
        echo "OPPS!! Error";
    }
}
