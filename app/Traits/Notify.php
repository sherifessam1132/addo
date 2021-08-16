<?php
namespace App\Traits;

use App\Models\Setting;

trait Notify{

    public function topicNotifyByFirebase($topic,$data = [])        // paramete 5 =>>>> $type
    {
        $setting = Setting::all()->first();

        define('API_ACCESS_KEY', $setting->firebase_key );
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $notification = [
            'title' => $data['title'],
            'body' => $data['body'],
            'sound' => "default",
            'color' => "#203E78"
        ];
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
        $fcmNotification = [
            "to" => '/topics/'.$topic,
            'notification' => $notification,
            'priority' => 'high',
            'notification_priority' => 'high',
            'data' => $data
        ];
        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        // dd($result);
        curl_close($ch);
        // return $result;
    }
    public function topicNotifyByFirebaseTokens($tokens,$data = [])        // paramete 5 =>>>> $type
    {
        $setting = Setting::all()->first();

        define('API_ACCESS_KEY', $setting->firebase_key );
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $notification = [
            'title' => $data['title'],
            'body' => $data['body'],
            'sound' => "default",
            'color' => "#203E78"
        ];
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
        $fcmNotification = [
            "registration_ids" => $tokens,
            'notification' => $notification,
            'priority' => 'high',
            'notification_priority' => 'high',
            'data' => $data
        ];
        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        // dd($result);
        curl_close($ch);
        // return $result;
    }
}
