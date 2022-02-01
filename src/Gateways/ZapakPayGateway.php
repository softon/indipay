<?php namespace Softon\Indipay\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Softon\Indipay\Exceptions\IndipayParametersMissingException;

class ZapakPayGateway implements PaymentGatewayInterface {

    protected $parameters = array();
    protected $testMode = false;
    protected $secret = '';
    protected $merchantIdentifier = '';
    protected $checksum = '';
    protected $liveEndPoint = 'https://api.zaakpay.com/api/paymentTransact/V7';
    protected $testEndPoint = 'https://api.zaakpay.com/api/paymentTransact/V7';
    public $response = '';

    function __construct()
    {
        $this->secret = Config::get('indipay.zapakpay.secret');
        $this->merchantIdentifier = Config::get('indipay.zapakpay.merchantIdentifier');
        $this->testMode = Config::get('indipay.testMode');
        $this->parameters['merchantIdentifier'] = $this->merchantIdentifier;
        $this->parameters['returnUrl'] = url(Config::get('indipay.zapakpay.returnUrl'));
        
    }

    public function getEndPoint()
    {
        return $this->testMode?$this->testEndPoint:$this->liveEndPoint;
    }

    public function request($parameters)
    {
        $this->parameters = array_merge($this->parameters,$parameters);

        $this->checkParameters($this->parameters);

        $encodeString = $this->getAllParams($this->parameters);
        $this->checksum = $this->calculateChecksum($this->secret,$encodeString);

        return $this;

    }

    /**
     * @return mixed
     */
    public function send()
    {

        Log::info('Indipay Payment Request Initiated: ');
        return View::make('indipay::zapakpay')->with('params',$this->parameters)
                             ->with('checksum',$this->checksum)
                             ->with('endPoint',$this->getEndPoint());

    }


    /**
     * Check Response
     * @param $request
     * @return array
     */
    public function response($request)
    {
        $response = $request->all();
        $rcvd_checksum = $request->checksum;

        $rcvd_data = $this->getAllResponseParams($response);
        $checksum_check = $this->verifyChecksum($rcvd_checksum,$rcvd_data,$this->secret);

        if(!$checksum_check){
            return "Recieved Checksum Mismatch.";
        }

        $this->response = $response;

        return $this->response;
    }


    /**
     * @param $parameters
     * @throws IndipayParametersMissingException
     */
    public function checkParameters($parameters)
    {
        $validator = Validator::make($parameters, [
            'merchantIdentifier' => 'required',
            'buyerEmail' => 'required|email',
            'currency' => 'required',
            'orderId' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new IndipayParametersMissingException($validator->errors());
        }

    }


    public function calculateChecksum($secret_key, $all)
    {
        $hash = hash_hmac('sha256', $all , $secret_key);
        $checksum = $hash;
        return $checksum;
    }


    public function getAllParams($params) {
        //ksort($_POST);
        $all = '';
        
        
        $checksumsequence= array("amount","bankid","buyerAddress",
                "buyerCity","buyerCountry","buyerEmail","buyerFirstName","buyerLastName","buyerPhoneNumber","buyerPincode",
                "buyerState","currency","debitorcredit","merchantIdentifier","merchantIpAddress","mode","orderId",
                "product1Description","product2Description","product3Description","product4Description",
                "productDescription","productInfo","purpose","returnUrl","shipToAddress","shipToCity","shipToCountry",
                "shipToFirstname","shipToLastname","shipToPhoneNumber","shipToPincode","shipToState","showMobile","txnDate",
                "txnType","zpPayOption");
        
        
        foreach($checksumsequence as $seqvalue) {
            if(array_key_exists($seqvalue, $params)) {
                if(!$params[$seqvalue]=="")
                {
                    if($seqvalue != 'checksum') 
                    {
                            $all .= $seqvalue;
                            $all .="=";
                            if ($seqvalue == 'returnUrl') 
                            {
                                $params[$seqvalue] = $this->sanitizedURL($params[$seqvalue]);
                                $all .= $this->sanitizedURL($params[$seqvalue]);
                        } 
                            else 
                            {
                                $params[$seqvalue] = $this->sanitizedParam($params[$seqvalue]);
                                $all .= $this->sanitizedParam($params[$seqvalue]);
                            }
                        $all .= "&";
                        }
                }
                
            }
        }
        
        
        
        return $all;
    }



    
    public function verifyChecksum($checksum, $all, $secret) {
        $cal_checksum = $this->calculateChecksum($secret, $all);
        $bool = 0;
        if($checksum == $cal_checksum)  {
            $bool = 1;
        }
        
        return $bool;
    }
    
    public function sanitizedParam($param) {
        $pattern[0] = "%,%";
        $pattern[1] = "%#%";
        $pattern[2] = "%\(%";
        $pattern[3] = "%\)%";
        $pattern[4] = "%\{%";
        $pattern[5] = "%\}%";
        $pattern[6] = "%<%";
        $pattern[7] = "%>%";
        $pattern[8] = "%`%";
        $pattern[9] = "%!%";
        $pattern[10] = "%\\$%";
        $pattern[11] = "%\%%";
        $pattern[12] = "%\^%";
        $pattern[13] = "%=%";
        $pattern[14] = "%\+%";
        $pattern[15] = "%\|%";
        $pattern[16] = "%\\\%";
        $pattern[17] = "%:%";
        $pattern[18] = "%'%";
        $pattern[19] = "%\"%";
        $pattern[20] = "%;%";
        $pattern[21] = "%~%";
        $pattern[22] = "%\[%";
        $pattern[23] = "%\]%";
        $pattern[24] = "%\*%";
        $pattern[25] = "%&%";
        $sanitizedParam = preg_replace($pattern, "", $param);
        return $sanitizedParam;
    }
    
    public function sanitizedURL($param) {
        $pattern[0] = "%,%";
        $pattern[1] = "%\(%";
        $pattern[2] = "%\)%";
        $pattern[3] = "%\{%";
        $pattern[4] = "%\}%";
        $pattern[5] = "%<%";
        $pattern[6] = "%>%";
        $pattern[7] = "%`%";
        $pattern[8] = "%!%";
        $pattern[9] = "%\\$%";
        $pattern[10] = "%\%%";
        $pattern[11] = "%\^%";
        $pattern[12] = "%\+%";
        $pattern[13] = "%\|%";
        $pattern[14] = "%\\\%";
        $pattern[15] = "%'%";
        $pattern[16] = "%\"%";
        $pattern[17] = "%;%";
        $pattern[18] = "%~%";
        $pattern[19] = "%\[%";
        $pattern[20] = "%\]%";
        $pattern[21] = "%\*%";
        $sanitizedParam = preg_replace($pattern, "", $param);
        return $sanitizedParam;
    }
    

    public function getAllResponseParams($response) {

        $all = '';
        $checksumsequence= array("amount","bank","bankid",
                "cardId","cardScheme","cardToken","cardhashid","doRedirect",
                "orderId","paymentMethod","paymentMode","responseCode",
                "responseDescription");
        foreach($checksumsequence as $seqvalue) {
            if(array_key_exists($seqvalue, $response)) {
                
                $all .= $seqvalue;
                $all .="=";
                if ($seqvalue == 'returnUrl') {
                    $all .= $response[$seqvalue];
                } else {
                    $all .= $response[$seqvalue];
                }
                $all .= "&";
                
            }
        }
        
        
        return $all;
    }

    




}