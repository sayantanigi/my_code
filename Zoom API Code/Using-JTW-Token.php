<?php
//++++++++++++++++++++++++++++++++++++++++++++++++
// Befour Using The Below Code Install Below Package 
// https://github.com/firebase/php-jwt (composer require firebase/php-jwt)
//++++++++++++++++++++++++++++++++++++++++++++++++
function create_a_zoom_meeting($meetingConfig = [])
{
    //++++++++++++++++++++++++++++++++++++++++++++++++
    $key     = '';
    $secret  = '';
    //++++++++++++++++++++++++++++++++++++++++++++++++
    $payload = [
        'iss' => $key,
        'exp' => strtotime('+1 minute'),
    ];
    $jwtToken   = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
    $zoomUserId = 'me';
    //++++++++++++++++++++++++++++++++++++++++++++++++
    $requestBody = [
        'topic'      => $meetingConfig['topic'] ?? 'New Meeting General Talk',
        'type'       => $meetingConfig['type'] ?? 2,
        'start_time' => $meetingConfig['start_time'] ?? date('Y-m-dTh:i:00') . 'Z',
        'duration'   => $meetingConfig['duration'] ?? 30,
        'password'   => $meetingConfig['password'] ?? mt_rand(),
        'timezone'   => 'Asia/Kolkata',
        'agenda'     => $meetingConfig['agenda'] ?? 'Interview Meeting',
        'settings'   => [
            'host_video'        => false,
            'participant_video' => true,
            'cn_meeting'        => false,
            'in_meeting'        => false,
            'join_before_host'  => true,
            'mute_upon_entry'   => true,
            'watermark'         => false,
            'use_pmi'           => false,
            'approval_type'     => 1,
            'registration_type' => 1,
            'audio'             => 'voip',
            'auto_recording'    => 'none',
            'waiting_room'      => false,
        ],
    ];
    //++++++++++++++++++++++++++++++++++++++++++++++++
    //++++++++++++++++++++++++++++++++++++++++++++++++
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
    curl_setopt_array($curl, array(
        CURLOPT_URL            => "https://api.zoom.us/v2/users/" . $zoomUserId . "/meetings",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => "",
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => json_encode($requestBody),
        CURLOPT_HTTPHEADER     => array(
            "Authorization: Bearer " . $jwtToken,
            "Content-Type: application/json",
            "cache-control: no-cache",
        ),
    ));
    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);
    //++++++++++++++++++++++++++++++++++++++++++++++++
    if ($err) {
        return [
            'success'  => false,
            'msg'      => 'cURL Error #:' . $err,
            'response' => null,
        ];
    } else {
        return [
            'success'  => true,
            'msg'      => 'success',
            'response' => json_decode($response, true),
        ];
    }
}