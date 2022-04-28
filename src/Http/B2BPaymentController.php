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
        $helaplusLog->payload = json_encode($request->all()); 
        $helaplusLog->save();

    }

    public static function B2BPaymentReceiver($amount,$receiver,$command,$reference){
        //TODO::Process Inbound callback
    }

}