<?php

namespace App\Http\Services;

class FCMService
{
    public static function send($token, $notification,$image='',$id=0)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "to":"'.$token.'",
            "priority": "high",
            "notification": {
                "title": "'.$notification['title'].'",
                "body" : "'.$notification['body'].'",
            },
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: key='.config('fcm.token').'',
            'Content-Type: application/json'
          ),
        ));

        curl_exec($curl);
        curl_close($curl);
    }
}

// "image" : "'.$image.'",
// "data": {
//     "id": "'.$id.'"
// }
