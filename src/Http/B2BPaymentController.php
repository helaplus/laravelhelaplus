<?php

namespace Helaplus\Laravelhelaplus\Http;


use Illuminate\Support\Facades\Http;

class B2BPaymentController extends Controller {
    public static function sendB2BPayment($amount,$receiver,$command,$reference){
        $data = [
            'Initiator'=> config('laravelhelaplus.b2binitiator'),
            'SecurityCredential'=> config('laravelhelaplus.b2bsecuritycredential'),
            'CommandID'=> $command,
            'initiator_identifier_type'=> 11,
            'PartyA'=> config('laravelhelaplus.b2bsource'),
            'PartyB'=> $receiver,
            'Amount'=> $amount,
            'RecieverIdentifierType'=> 4,
            'SenderIdentifierType'=> 4,
            'ResultURL'=> config('laravelhelaplus.b2bresult_url'),
            'QueueTimeOutURL'=> config('laravelhelaplus.b2bresult_url'),
            'Remarks'=> $reference,
            'AccountReference'=> $reference,
            'Occasion'=> $reference,
            ];  

        return Http::withToken([
            config('laravelhelaplus.helaplus_api_token')
        ])->post(config('laravelhelaplus.b2b.result_url'),$data)->body();

    }

}