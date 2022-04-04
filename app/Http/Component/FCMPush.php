<?php

namespace App\Http\Component;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
//$token = 'edCr3wn3PWw:APA91bFssNvsjC_R6xkYvg8IJb-E2s9SeFxOP5btLUDW5hPwmJTRGzhKX8AxvawMtiJxdlQzdFT2WQXsznJIPe86KUcI1FbR7Zt4tondNXKeLhSSL5_doOPzxNsknodvWvMksmxXHX8B';
//$payload = [
//    'title' => "This is title",
//    'description' => 'This is long text description',
//    'type' => 'payment',
//    'user_id' => 25,
//    'identifier' => 'order-123',
//];
//FCMPush::sendPushToSingleDevice($payload, $token);
class FCMPush
{
    private static $_CONFIG = [
        'ANDROID_API_KEY' => 'AAAABg2GnmY:APA91bHuN28jxCfj5h1sdTSMjt3afHswDVwStVastwFCLCjuNR_xafVsHDH_d--ef8RqV9RXbuS8v8TrPh_SC4zlbMwMLmnzdkhBPBST73fWRs_n0ZTFPoffC4Q0SAF3mK6pVa2H3isV',
        'ANDROID_URL' => 'https://fcm.googleapis.com/fcm/send',
    ];

    /**
     * @param array $payload Payload
     * @param string $token Token
     * @return void
     */
    public static function sendPushToSingleDevice($payload, $token)
    {
        self::gcmPush($payload, [$token]);
    }

    /**
     * @param array $payload Payload
     * @param string[] $tokens Token
     * @return void
     */
    public static function gcmPush($payload, $tokens, $debug = false)
    {
        $client = new Client();

        $requests = function ($url, $androidApiKey, $payLoad, $tokens) {
            $headers = [
                'Authorization' => 'key=' . $androidApiKey,
                'Content-Type' => 'application/json',
            ];
            $fields = [
                'data' => ['message' => $payLoad],
            ];
            $tokens = array_chunk($tokens, 900);
            foreach ($tokens as $token) {
                $fields['registration_ids'] = $token;
                yield new Request('POST', $url, $headers, \GuzzleHttp\json_encode($fields));
            }
        };

        $pool = new Pool($client, $requests(self::$_CONFIG['ANDROID_URL'], self::$_CONFIG['ANDROID_API_KEY'], $payload, $tokens), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // if($debug){
                //     dd($response->getBody()->getContents());        
                // }
            },
            'rejected' => function ($reason, $index) {
                // if($debug){
                //     dd($reason);     
                // }
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
    }
}
