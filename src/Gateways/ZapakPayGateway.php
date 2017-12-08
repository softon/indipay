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

        //$this->checkParameters($this->parameters);

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
        $encResponse = $request->encResp;

        $rcvdString = $this->decrypt($encResponse,$this->workingKey);
        parse_str($rcvdString, $decResponse);

        return $decResponse;
    }


    /**
     * @param $parameters
     * @throws IndipayParametersMissingException
     */
    public function checkParameters($parameters)
    {
        $validator = Validator::make($parameters, [
            'merchant_id' => 'required',
            'currency' => 'required',
            'redirect_url' => 'required|url',
            'cancel_url' => 'required|url',
            'language' => 'required',
            'tid' => 'required',
            'order_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new IndipayParametersMissingException;
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


    public function getAllParamsCheckandUpdate() {
        //ksort($_POST);
        $all = '';
        foreach($_POST as $key => $value)   {
            if($key != 'checksum') {
                $all .= "'";
                if ($key == 'returnUrl') {
                    $all .= $this->sanitizedURL($value);
                } else {
                    $all .= $this->sanitizedParam($value);
                }
                $all .= "'";
            }
        }
        
        return $all;
    }
    public function outputForm($checksum) {
        //ksort($_POST);
        foreach($_POST as $key => $value) {
            if ($key == 'returnUrl') {
                echo '<input type="hidden" name="'.$key.'" value="'.Checksum::sanitizedURL($value).'" />'."\n";
            } else {
                echo '<input type="hidden" name="'.$key.'" value="'.Checksum::sanitizedParam($value).'" />'."\n";
            }
        }
        echo '<input type="hidden" name="checksum" value="'.$checksum.'" />'."\n";
    }
    
    public function verifyChecksum($checksum, $all, $secret) {
        $cal_checksum = Checksum::calculateChecksum($secret, $all);
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
    
    public function outputResponse($bool) {
        foreach($_POST as $key => $value) {
            if ($bool == 0) {
                if ($key == "responseCode") {
                    echo '<tr><td width="50%" align="center" valign="middle">'.$key.'</td>
                        <td width="50%" align="center" valign="middle"><font color=Red>***</font></td></tr>';
                } else if ($key == "responseDescription") {
                    echo '<tr><td width="50%" align="center" valign="middle">'.$key.'</td>
                        <td width="50%" align="center" valign="middle"><font color=Red>This response is compromised.</font></td></tr>';
                } else {
                    echo '<tr><td width="50%" align="center" valign="middle">'.$key.'</td>
                        <td width="50%" align="center" valign="middle">'.$value.'</td></tr>';
                }
            } else {
                echo '<tr><td width="50%" align="center" valign="middle">'.$key.'</td>
                    <td width="50%" align="center" valign="middle">'.$value.'</td></tr>';
            }
        }
        echo '<tr><td width="50%" align="center" valign="middle">Checksum Verified?</td>';
        if($bool == 1) {
            echo '<td width="50%" align="center" valign="middle">Yes</td></tr>';
        }
        else {
            echo '<td width="50%" align="center" valign="middle"><font color=Red>No</font></td></tr>';
        }
    }
    public function getAllResponseParams() {
        //ksort($_POST);
        $all = '';
        $checksumsequence= array("amount","bank","bankid",
                "cardId","cardScheme","cardToken","cardhashid","doRedirect","orderId","paymentMethod","paymentMode","responseCode",
                "responseDescription");
        foreach($checksumsequence as $seqvalue) {
            if(array_key_exists($seqvalue, $_POST)) {
                
                $all .= $seqvalue;
                $all .="=";
                if ($seqvalue == 'returnUrl') {
                    $all .= $_POST[$seqvalue];
                } else {
                    $all .= $_POST[$seqvalue];
                }
                $all .= "&";
                
                
                
            }
        }
        
        
        return $all;
    }

    




}