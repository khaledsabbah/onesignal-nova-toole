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
        $data = $this->post(config('constants.ONE_SIGNAL_APP_URL')."/notifications/$id/history",
            [   'app_id' => config('constants.ONE_SIGNAL_APP_ID'),
                'events' => 'clicked',
                'email' => config('constants.ONE_SIGNAL_EXPORTED_MAIL'), 
            ],
            [   'Content-Type' => 'application/json',
                'Authorization' => "Basic ".config('constants.ONE_SIGNAL_AUTHORIZATION_TOKEN')
            ]);
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
    public function post(string $path,array $body = [],$headers = []) :array
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $path, [
            'headers' => $headers + [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            \GuzzleHttp\RequestOptions::JSON  => $body
        ]);
        return json_decode($response->getBody(),true);
    }
}

