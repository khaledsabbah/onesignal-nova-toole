<?php

namespace Onesignal\OneSignalNotificationHistory\Http\Controllers;

use App\OnesignalDevice;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function historyDetails($id)
    {
        $data = $this->post("https://app.onesignal.com/api/v1/notifications/$id/history");
        $players = [];
        
        if (($handle = fopen($data['destination_url'], "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $players[$data[0]] = $data[1];
            }
            fclose($handle);
        }
        
        $devices = OnesignalDevice::with('users:username,name')->whereIn('token',array_keys($players))->get();//->pluck('users.*.username','users.*.name');
        return ['devices' => $devices];
    }
    /**
     * @param string   $path
     * @param array    $body
     *
     * @return  array
     */
    public function post(string $path) :array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $path,
            CURLOPT_POSTFIELDS=> json_encode(array(
                "events"=> "clicked",
                "app_id"=> config('constants.ONE_SIGNAL_APP_ID'),
                "email"=> config('constants.ONE_SIGNAL_EXPORTED_MAIL')
            )),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                'Authorization: Basic '.config("constants.ONE_SIGNAL_AUTHORIZATION_TOKEN"),
                'Cache-Control: no-cache'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ["destination_url" => ""];
        } else {
            return json_decode($response, true);
        }
    }
}

