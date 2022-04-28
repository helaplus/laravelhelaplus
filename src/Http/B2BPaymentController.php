<?php

namespace Helaplus\Laravelhelaplus\Http;

use Helaplus\Laravelhelaplus\Models\helaplusLog;
use Helaplus\Laravelhelaplus\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class B2BPaymentController extends Controller {

    public static function sendB2BPayment($amount,$receiver,$command,$reference){
        $data = [
            'Initiator'=> config('laravelhelaplus.b2b.initiator'),
            'SecurityCredential'=> config('laravelhelaplus.b2b.securitycredential'),
            'CommandID'=> $command,
            'initiator_identifier_type'=> 11,
            'PartyA'=> config('laravelhelaplus.b2b.source'),
            'PartyB'=> $receiver,
            'Amount'=> $amount,
            'RecieverIdentifierType'=> 4,
            'SenderIdentifierType'=> 4,
            'ResultURL'=> config('laravelhelaplus.b2b.result_url'),
            'QueueTimeOutURL'=> config('laravelhelaplus.b2b.result_url'),
            'Remarks'=> $reference,
            'AccountReference'=> $reference,
            'Occasion'=> $reference,
            ];
        $transaction = new Transaction();
        $transaction->type = 'b2b';
        $transaction->amount = $amount;
        $transaction->recipient = $receiver;
        $transaction->reference = $reference;
        $transaction->details = json_encode($data);
        $transaction->save();

        return Http::withToken(
            config('laravelhelaplus.helaplus_api_token')
        )->post(config('laravelhelaplus.b2b.helaplus_b2b_endpoint'),$data)->body();

    }

    public static function c2bReceiver(Request $request){
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'b2b_c2bReceiver';
        $helaplusLog->endpoint = '/helaplusb2b/c2bReceiver';
        $helaplusLog->payload = file_get_contents('php://input');
        $helaplusLog->save();
        $xml = new \DOMDocument();
        $xml->loadXML($helaplusLog->payload);
        $shortcode = $xml->getElementsByTagName('BusinessShortCode')->item(0)->nodeValue;
        $amount = $xml->getElementsByTagName('TransAmount')->item(0)->nodeValue;
        $b2b = self::initiateRevenueSettlement($amount,$shortcode);
        print_r($b2b);
        exit;
        $b2b = B2BPaymentController::sendB2BPayment(10,$shortcode,$command_id,$shortcode);
        print_r($b2b);
    }


    public static function initiateRevenueSettlement($amount,$shortcode)
    {
 
        $data = [
            'Initiator'=> config('laravelhelaplus.c2b.initiator'),
            'SecurityCredential'=> config('laravelhelaplus.c2b.securitycredential'),
            'CommandID'=> "OrgRevenueSettlement",
            'initiator_identifier_type'=> 11,
            'PartyA'=> config('laravelhelaplus.c2b.source'),
            'PartyB'=> config('laravelhelaplus.c2b.source'),
            'Amount'=> $amount,
            'RecieverIdentifierType'=> 4,
            'SenderIdentifierType'=> 4,
            'ResultURL'=> config('laravelhelaplus.c2b.result_url'),
            'QueueTimeOutURL'=> config('laravelhelaplus.c2b.result_url'),
            'Remarks'=> config('laravelhelaplus.c2b.source'),
            'AccountReference'=> config('laravelhelaplus.c2b.source'),
            'Occasion'=> config('laravelhelaplus.c2b.source'),
        ];
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'revenue_settlement';
        $helaplusLog->endpoint = config('laravelhelaplus.c2b.helaplus_c2b_endpoint');
        $helaplusLog->payload = json_encode($data);
        $helaplusLog->save();
        $response = Http::withToken(
            config('laravelhelaplus.helaplus_api_token')
        )->post(config('laravelhelaplus.c2b.helaplus_c2b_endpoint'),$data)->body();
        $helaplusLog->response = $response->body();
        $helaplusLog->save();
        print_r($response);
        exit;
    }


    public static function B2BPaymentReceiver($amount,$receiver,$command,$reference){
        //TODO::Process Inbound callback
    }

}