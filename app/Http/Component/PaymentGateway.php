<?php


namespace App\Http\Component;


use Illuminate\Support\Facades\DB;

class PaymentGateway
{
    private $SECRET_KEY = "987dc84c97a538841f26bc2ec5c0cdf87ea5a009";
    private $APP_ID = '745138c7011e60c7bae8c081c31547';
    private $REDIRECT_URL = '';

    public function __construct()
    {
        $this->REDIRECT_URL = url('cashfree/response');
    }

    public function startTransaction($customerBooking)
    {
        $customer = DB::table('customers')
            ->where('customer_id', $customerBooking->customer_id)
            ->first();

        $responseData = [];
        $postData = array(
            "appId" => $this->APP_ID,
            "orderId" => $customerBooking->customer_booking_id,
            "orderAmount" => $customerBooking->total_price,
            "orderCurrency" => 'INR',
            "orderNote" => '',
            "customerName" => $customer->customer_name,
            "customerPhone" => $customer->customer_mobile,
            "customerEmail" => !empty($postData['customerEmail'])?$postData['customerEmail']:'amanrwt99@gmail.com',
//            "returnUrl" => $this->REDIRECT_URL,
//            "notifyUrl" => '',
        );
//
//
//        ksort($postData);
//        $signatureData = "";
//        foreach ($postData as $key => $value) {
//            $signatureData .= $key . $value;
//        }
//        $signature = hash_hmac('sha256', $signatureData, $this->SECRET_KEY, true);


        $ch = curl_init();
//        https://api.cashfree.com/api/v2/cftoken/order
//        https://test.cashfree.com/api/v2/cftoken/order
        curl_setopt($ch, CURLOPT_URL, 'https://api.cashfree.com/api/v2/cftoken/order');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"orderId\": \"{$postData['orderId']}\",\n  \"orderAmount\":{$postData['orderAmount']},\n  \"orderCurrency\": \"INR\"\n}");

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Client-Id: '. $this->APP_ID;
        $headers[] = 'X-Client-Secret: '.$this->SECRET_KEY;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result, true);

        $responseData['signature'] = $result['cftoken'];
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
        $hash_hmac = hash_hmac('sha256', $data, $this->SECRET_KEY, true) ;
        $computedSignature = base64_encode($hash_hmac);

        return $signature == $computedSignature;
    }
}
