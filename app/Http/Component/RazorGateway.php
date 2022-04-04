<?php


namespace App\Http\Component;

use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
class RazorGateway
{
    private $api_key = 'rzp_live_bdFWYfELYV1p29';
    private $api_secret = 'ARKMr4pyg8tngzcPkUPdTa2j';
    private $REDIRECT_URL = '';

    public function __construct()
    {
        $this->REDIRECT_URL = url('razorpay/response');
    }

    public function startTransaction($customerBooking)
    {
        $customer = DB::table('customers')
            ->where('customer_id', $customerBooking->customer_id)
            ->first();
        $api = new Api('rzp_live_bdFWYfELYV1p29', 'ARKMr4pyg8tngzcPkUPdTa2j');

        $order  = $api->order->create(array('receipt' => '123', 'amount' => ($customerBooking->total_price*100), 'currency' => 'INR')); // Creates order
        $orderId = $order['id']; // Get the created Order ID
        // $order  = $api->order->fetch($orderId);
        // dd($order  = $api->order->fetch('order_GxaTeB445ZChwm'));
        // $mail=DB::table('customers')->where('customer_id',$customerBooking->customer_id)->get('customer_email');
// dd($mail);
        $responseData = [];
        $postData = array(
            "appId" => $this->api_key,
            "orderId" => $orderId,
            "orderAmount" => $customerBooking->total_price,
            "orderCurrency" => 'INR',
            "orderNote" => '',
            "customerName" => $customer->customer_name,
            "customerPhone" => $customer->customer_mobile,
            "customerEmail"=> !empty($customer->customer_email)?$customer->customer_email:'amanrwt99@gmail.com'
            // "customerEmail" => !empty($postData['customerEmail'])?$postData['customerEmail']:'amanrwt99@gmail.com',
//            "returnUrl" => $this->REDIRECT_URL,
//            "notifyUrl" => '',
        );
        if(!empty($orderId)){
            DB::table('customer_booking')->where('customer_booking_id',$customerBooking->customer_booking_id)->update(['orderId'=>$orderId]);
        }
//
//
//        ksort($postData);
//        $signatureData = "";
//        foreach ($postData as $key => $value) {
//            $signatureData .= $key . $value;
//        }
//        $signature = hash_hmac('sha256', $signatureData, $this->SECRET_KEY, true);


//         $ch = curl_init();
// //        https://api.cashfree.com/api/v2/cftoken/order
// //        https://test.cashfree.com/api/v2/cftoken/order
//         curl_setopt($ch, CURLOPT_URL, 'https://api.cashfree.com/api/v2/cftoken/order');
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"orderId\": \"{$postData['orderId']}\",\n  \"orderAmount\":{$postData['orderAmount']},\n  \"orderCurrency\": \"INR\"\n}");

//         $headers = array();
//         $headers[] = 'Content-Type: application/json';
//         $headers[] = 'X-Client-Id: '. $this->api_key;
//         $headers[] = 'X-Client-Secret: '.$this->api_secret;
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $result = curl_exec($ch);
//         if (curl_errno($ch)) {
//             echo 'Error:' . curl_error($ch);
//         }
//         curl_close($ch);
//         $result = json_decode($result, true);

//         $responseData['signature'] = $result['cftoken'];
        $responseData['orderId'] = $postData['orderId'];
        $responseData['orderAmount'] = $postData['orderAmount'];
        $responseData['orderCurrency'] = $postData['orderCurrency'];
        $responseData['orderNote'] = $postData['orderNote'];
        $responseData['customerName'] = $postData['customerName'];
        $responseData['customerPhone'] = $postData['customerPhone'];
        $responseData['customerEmail'] = $postData['customerEmail'];

        return $responseData;
    }

    public function isSignatureMatched($request) {
        $orderId = $request->orderId;
        $orderAmount = $request->orderAmount;
        $referenceId = $request->referenceId;
        $txStatus = $request->txStatus;
        $paymentMode = $request->paymentMode;
        $txMsg = $request->txMsg;
        $txTime = $request->txTime;
        $signature = $request->signature;
        $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
        $hash_hmac = hash_hmac('sha256', $data, $this->api_secret, true) ;
        $computedSignature = base64_encode($hash_hmac);

        return $signature == $computedSignature;
    }
}
