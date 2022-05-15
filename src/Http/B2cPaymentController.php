<?php

namespace Helaplus\Laravelhelaplus\Http;

use Helaplus\Laravelhelaplus\Models\helaplusLog;
use Helaplus\Laravelhelaplus\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class B2cPaymentController extends Controller {

    public static function sendB2cPayment($amount,$receiver,$reference,$result_url){
        $data = [
            'InitiatorName'=> config('laravelhelaplus.b2c.initiator'),
            'SecurityCredential'=> config('laravelhelaplus.b2c.securitycredential'),
            'CommandID'=> "BusinessPayment",
            'PartyA'=> config('laravelhelaplus.b2c.source'),
            'PartyB'=> $receiver,
            'Amount'=> $amount,
            'ResultURL'=> $result_url,
            'callback_url'=> $result_url,
            'QueueTimeOutURL'=> $result_url,
            'Remarks'=> $reference,
            'AccountReference'=> $reference,
            'Occasion'=> $reference,
            ];
        $transaction = new Transaction();
        $transaction->type = 'b2c';
        $transaction->amount = $amount;
        $transaction->recipient = $receiver;
        $transaction->reference = $reference;
        $transaction->details = json_encode($data);
        $transaction->save();

        return Http::withToken(
            config('laravelhelaplus.helaplus_api_token')
        )->post(config('laravelhelaplus.b2c.helaplus_b2c_endpoint'),$data)->body();

    }


}