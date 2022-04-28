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
        $xml2 = new \DOMDocument();
        $xml2->loadXML($xml->textContent);
        $shortcode = $xml2->getElementsByTagName('BusinessShortCode')->item(0)->nodeValue;
        $amount = $xml2->getElementsByTagName('TransAmount')->item(0)->nodeValue;

        $command_id = "OrgRevenueSettlement";;
        $b2b = B2BPaymentController::sendB2BPayment($amount,$shortcode,$command_id,$shortcode);
        print_r($b2b);
        exit;
        $b2b = B2BPaymentController::sendB2BPayment(10,$shortcode,$command_id,$shortcode);
        print_r($b2b);
    }

    public function initiateRevenueSettlement($shortcode,$amount)
    {

        date_default_timezone_set('Africa/Nairobi');

        $primary_party = $shortcode;
        $receiver_party = $shortcode;
        $initiator_username = env('ichaama_b2b_apiuser');
        $initiator_pass = env('ichaama_b2b_pass');

        if ($api_key != 'nHAUXTyFn3nMNca3') {

            return json_encode(['error' => 1, 'message' => 'Invalid API Key']);
        }
        $timestamp_ = date("YdmHis");

        $spId = '100410';
        $SERVICEID = "100410000";
        $password = 'KenyaN!23';
        $timestamp_ = date("YdmHis");
        $real_pass = base64_encode(hash('sha256', $spId . "" . $password . "" . $timestamp_));

//        $initiator_pass = '8T554462';

        $securityCredential = self::getSecurityCredential($initiator_pass);

        if(!empty($request->input('OriginatorConversationID'))){
            $originId = $request->input('OriginatorConversationID');
        }else{
            $rand = rand(123456, 654321);
            $originId = $spId . "_amka_" . $rand;
        }
        $type = 2;
        $third_party_id = null;
        $reqTime = date('Y-m-d') . "T" . date('H:i:s') . ".0000521Z"; //2014-10-21T09:47:19.0000521Z

        $curlRequest = '
   <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:req="http://api-v1.gen.mm.vodafone.com/mminterface/request">
        <soapenv:Header>
          <tns:RequestSOAPHeader xmlns:tns="http://www.huawei.com/schema/osg/common/v2_1">
             <tns:spId>' . $spId . '</tns:spId>
             <tns:spPassword>' . $real_pass . '</tns:spPassword>
             <tns:timeStamp>' . $timestamp_ . '</tns:timeStamp>
             <tns:serviceId>' . $SERVICEID . '</tns:serviceId>
          </tns:RequestSOAPHeader>
        </soapenv:Header>
       <soapenv:Body>
          <req:RequestMsg>
            <![CDATA[
                <Request xmlns="http://api-v1.gen.mm.vodafone.com/mminterface/request">
                    <Transaction>
                        <CommandID>'.$command_id.'</CommandID>
                        <LanguageCode>0</LanguageCode>
                        <OriginatorConversationID>' . $originId . '</OriginatorConversationID>
                        <ConversationID></ConversationID>
                        <Remark>0</Remark>
                        <Parameters>
                            <Parameter>
                                    <Key>HeadOffice</Key>
                                    <Value></Value>
                            </Parameter>
                        </Parameters>
                        <ReferenceData>
                            <ReferenceItem>
                                <Key>QueueTimeoutURL</Key>
                                <Value>https://37.139.17.247:443/revenueSettlementResult</Value>
                             </ReferenceItem>
                        </ReferenceData>
                        <Timestamp>' . $reqTime . '</Timestamp>
                    </Transaction>
                    <Identity> 
                        <Caller>
                            <CallerType>2</CallerType>
                            <ThirdPartyID>' . $spId . '</ThirdPartyID>
                            <Password></Password>
                            <ResultURL>https://37.139.17.247:443/revenueSettlementResult</ResultURL>
                        </Caller>
                        <Initiator>
                            <IdentifierType>11</IdentifierType>
                            <Identifier>' . $initiator_username . '</Identifier>
                            <SecurityCredential>' . $securityCredential . '</SecurityCredential>
                            <ShortCode>' . $primary_party . '</ShortCode>
                        </Initiator>
                        <PrimaryParty>
                             <IdentifierType>4</IdentifierType>
                             <Identifier>' . $primary_party . '</Identifier>
                             <ShortCode>' . $primary_party . '</ShortCode>
                        </PrimaryParty>
                        <ReceiverParty>
                            <IdentifierType>4</IdentifierType>
                            <Identifier>' . $receiver_party . '</Identifier> 
                            <ShortCode>' . $receiver_party . '</ShortCode>
                        </ReceiverParty>
                        <AccessDevice>
                                <IdentifierType>4</IdentifierType>
                                <Identifier>1</Identifier>
                         </AccessDevice>
                    </Identity>
             <KeyOwner>1</KeyOwner>
            </Request>]]>
           </req:RequestMsg>
       </soapenv:Body>
    </soapenv:Envelope>
';
        return self::sendRequest($curlRequest);
    }


    public static function B2BPaymentReceiver($amount,$receiver,$command,$reference){
        //TODO::Process Inbound callback
    }

}