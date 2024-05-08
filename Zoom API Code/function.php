<?php
//++++++++++++++++++++++++++++++++++++++++++++++
//Function Get Access Token
//++++++++++++++++++++++++++++++++++++++++++++++
function get_access_token($code)
{
    try {
        $client   = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            "headers"     => [
                "Authorization" => "Basic " . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET),
            ],
            'form_params' => [
                "grant_type"   => "authorization_code",
                "code"         => $code,
                "redirect_uri" => REDIRECT_URI,
            ],
        ]);
        $token = json_decode($response->getBody()->getContents(), true);
        return $token;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
//++++++++++++++++++++++++++++++++++++++++++++++
//Function Get Refresh Token
//++++++++++++++++++++++++++++++++++++++++++++++
function get_refresh_token($refresh_token)
{
    try {
        $client   = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            "headers"     => [
                "Authorization" => "Basic " . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET),
            ],
            'form_params' => [
                "grant_type"    => "refresh_token",
                "refresh_token" => $refresh_token,
            ],
        ]);
        $token = $response->getBody();
        return $token;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
//++++++++++++++++++++++++++++++++++++++++++++++
//Function Generate A Meeting Using Zoom API
//++++++++++++++++++++++++++++++++++++++++++++++
function create_a_zoom_meeting($meetingConfig = [])
{
    try {
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $jwtToken = $meetingConfig['token'];
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $requestBody = [
            'topic'      => $meetingConfig['topic'] ?? 'Code 180',
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
            CURLOPT_URL            => "https://api.zoom.us/v2/users/me/meetings",
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
    } catch (Exception $e) {
        if ($e->getCode() == 401) {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++
            $get_token = get_refresh_token($meetingConfig['refresh_token']);
            //++++++++++++++++++++++++++++++++++++++++++++++++++++
            $get_new_meeting_details = create_a_zoom_meeting([
                'topic'      => 'Let Learn Zoom API Intigration In PHP',
                'type'       => 2,
                'start_time' => date('Y-m-dTh:i:00') . 'Z',
                'password'   => mt_rand(),
                'token'      => $get_token,
            ]);
            if ($get_new_meeting_details['msg'] == 'success') {
                echo $get_new_meeting_details['response']['uuid'];
            } else {
                echo "OPPS!! Error";
            }
        } else {
            echo $e->getMessage();
        }
    }
}
