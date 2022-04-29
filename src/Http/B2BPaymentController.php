<?php

namespace Helaplus\Laravelhelaplus\Http;

use Helaplus\Laravelhelaplus\Models\helaplusLog;
use Helaplus\Laravelhelaplus\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

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

    public static function c2bReceiver(){
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
        return json_encode(['code'=>0,'message'=>'success']);
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
            'paybill'=> config('laravelhelaplus.c2b.source'),
            'username'=> config('laravelhelaplus.c2b.initiator'),
            'password'=> config('laravelhelaplus.c2b.password'),
            'Amount'=> $amount,
            'RecieverIdentifierType'=> 4,
            'SenderIdentifierType'=> 4,
//            'ResultURL'=> config('laravelhelaplus.c2b.result_url',),
            'ResultURL'=> URL::to('helaplusb2b/revenueSettlementResponse'),
            'QueueTimeOutURL'=> config('laravelhelaplus.c2b.result_url',URL::to('helaplusb2b/revenueSettlementResponse')),
            'Remarks'=> config('laravelhelaplus.c2b.source'),
            'AccountReference'=> config('laravelhelaplus.c2b.source'),
            'Occasion'=> config('laravelhelaplus.c2b.source'),
        ];
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'revenue_settlement';
        $helaplusLog->endpoint = config('laravelhelaplus.c2b.helaplus_c2b_endpoint').'initiateRevenueSettlement';
        $helaplusLog->payload = json_encode($data);
        $helaplusLog->save();
        $response = Http::withToken(
            config('laravelhelaplus.helaplus_api_token')
        )->post(config('laravelhelaplus.c2b.helaplus_c2b_endpoint')."/initiateRevenueSettlement",$data)->body();
        $helaplusLog->response = $response;
        $helaplusLog->save();
        print_r($response);
        exit;
    }

    public static function initiateB2bTransferFromC2B($amount)
    {

        $data = [
            'Initiator'=> config('laravelhelaplus.c2b.initiator'),
            'SecurityCredential'=> config('laravelhelaplus.c2b.securitycredential'),
            'CommandID'=> "BusinessToBusinessTransfer",
            'initiator_identifier_type'=> 11,
            'PartyA'=> config('laravelhelaplus.c2b.source'),
            'PartyB'=> config('laravelhelaplus.b2b.source'),
            'paybill'=> config('laravelhelaplus.c2b.source'),
            'username'=> config('laravelhelaplus.c2b.initiator'),
            'password'=> config('laravelhelaplus.c2b.password'),
            'Amount'=> $amount,
            'RecieverIdentifierType'=> 4,
            'SenderIdentifierType'=> 4,
//            'ResultURL'=> config('laravelhelaplus.c2b.result_url',),
            'ResultURL'=> URL::to('helaplusb2b/b2bTransferResponse'),
            'QueueTimeOutURL'=> config('laravelhelaplus.c2b.result_url',URL::to('helaplusb2b/revenueSettlementResponse')),
            'Remarks'=> config('laravelhelaplus.c2b.source'),
            'AccountReference'=> config('laravelhelaplus.c2b.source'),
            'Occasion'=> config('laravelhelaplus.c2b.source'),
        ];
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'revenue_settlement';
        $helaplusLog->endpoint = config('laravelhelaplus.c2b.helaplus_c2b_endpoint')."/initiateB2b";
        $helaplusLog->payload = json_encode($data);
        $helaplusLog->save();
        $response = Http::withToken(
            config('laravelhelaplus.helaplus_api_token')
        )->post(config('laravelhelaplus.c2b.helaplus_c2b_endpoint')."/initiateB2b",$data)->body();
        $helaplusLog->response = $response;
        $helaplusLog->save();
        print_r($response);
        exit;
    }

    public static function initiateMmfToUtility($amount)
    {
        $data = [
            'Initiator'=> config('laravelhelaplus.b2c.initiator'),
            'SecurityCredential'=> config('laravelhelaplus.b2c.securitycredential'),
            'CommandID'=> "BusinessTransferFromMMFToUtility",
            'initiator_identifier_type'=> 11,
            'PartyA'=> config('laravelhelaplus.b2c.source'),
            'PartyB'=> config('laravelhelaplus.b2c.source'),
            'paybill'=> config('laravelhelaplus.b2c.source'),
            'username'=> config('laravelhelaplus.b2c.initiator'),
            'password'=> config('laravelhelaplus.b2c.password'),
            'Amount'=> $amount,
            'RecieverIdentifierType'=> 4,
            'SenderIdentifierType'=> 4,
            'ResultURL'=> URL::to('helaplusb2b/b2bMmfToUtlityTransferResponse'),
            'QueueTimeOutURL'=> config('laravelhelaplus.b2c.result_url',URL::to('helaplusb2b/b2bMmfToUtlityTransferResponse')),
            'Remarks'=> config('laravelhelaplus.b2c.source'),
            'AccountReference'=> config('laravelhelaplus.b2c.source'),
            'Occasion'=> config('laravelhelaplus.b2c.source'),
        ];
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'mmf_to_tulity';
        $helaplusLog->endpoint = config('laravelhelaplus.c2b.helaplus_c2b_endpoint')."/initiateB2b";
        $helaplusLog->payload = json_encode($data);
        $helaplusLog->save();
        $response = Http::withToken(
            config('laravelhelaplus.helaplus_api_token')
        )->post(config('laravelhelaplus.c2b.helaplus_c2b_endpoint')."/initiateB2b",$data)->body();
        $helaplusLog->response = $response;
        $helaplusLog->save();
        print_r($response);
        exit;
    }

    public static function revenueSettlementResponse(){
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'b2b_revenueSettlementReceiver';
        $helaplusLog->endpoint = '/helaplusb2b/revenueSettlementResponse';
        $helaplusLog->payload = file_get_contents('php://input');
        $helaplusLog->save();
        $response = json_decode($helaplusLog->payload);
        $working_account_balance = explode("Working Account|KES|",$response->data->response);
        $working_account_balance = explode("|",$working_account_balance[1]);
        self::initiateB2bTransferFromC2B($working_account_balance[0]);
//        self::sendB2BPayment($working_account_balance[0],config('b2b.source'),'BusinessTobusinessTransfer',$working_account_balance[0]);
        return $working_account_balance[0];
    }
 
    public static function b2bTransferResponse(){
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'b2bTransferResponse';
        $helaplusLog->endpoint = '/helaplusb2b/b2bTransferResponse';
        $helaplusLog->payload = file_get_contents('php://input');
        $helaplusLog->save();
        $response = json_decode($helaplusLog->payload);
        $working_account_balance = explode("CreditAccountBalance</Key><Value>Working Account|KES|",$response->data->response);
        $working_account_balance = explode("|",$working_account_balance[1]);
        self::initiateMmfToUtility($working_account_balance[0]);
//        self::sendB2BPayment($working_account_balance[0],config('b2b.source'),'BusinessTobusinessTransfer',$working_account_balance[0]);
        return $working_account_balance[0];
    }

    public static function b2bMmfToUtlityTransferResponse(){
        $helaplusLog = new helaplusLog();
        $helaplusLog->slug = 'b2bMmfToUtlityTransferResponse';
        $helaplusLog->endpoint = '/helaplusb2b/b2bMmfToUtlityTransferResponse';
        $helaplusLog->payload = file_get_contents('php://input');
        $helaplusLog->save();
        return response()->json(["code"=>0,"message"=>"success"],200);
    }

}