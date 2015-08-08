<?php namespace Softon\Indipay\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CCAvenueGateway implements PaymentGatewayInterface {

    protected $gwvars = array();
    protected $url = 'http://enterprise.smsgupshup.com/GatewayAPI/rest?';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode='';

    function __construct()
    {
        $this->gwvars['send_to'] = '';
        $this->gwvars['msg'] = '';
        $this->gwvars['method'] = 'sendMessage';
        $this->gwvars['userid'] = Config::get('sms.gupshup.userid');
        $this->gwvars['password'] = Config::get('sms.gupshup.password');
        $this->gwvars['v'] = "1.1";
        $this->gwvars['msg_type'] = "TEXT";
        $this->gwvars['auth_scheme'] = "PLAIN";
        $this->countryCode = Config::get('sms.countryCode');
    }

    public function getUrl()
    {
        foreach($this->gwvars as $key=>$val) {
            $this->request.= $key."=".urlencode($val);
            $this->request.= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;

    }

    public function sendSms($mobile,$message)
    {
        $mobile = $this->addCountryCode($mobile);

        if(is_array($mobile)){
            $mobile = $this->composeBulkMobile($mobile);
        }

        $this->gwvars['send_to'] = $mobile;
        $this->gwvars['msg'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        Log::info('Gupshup SMS Response: '.$this->response);
        return $this;

    }



    /**
     * Create Send to Mobile for Bulk Messaging
     * @param $mobile
     * @return string
     */
    private function composeBulkMobile($mobile)
    {
        return implode(',',$mobile);
    }

    /**
     * Prepending Country Code to Mobile Numbers
     * @param $mobile
     * @return array|string
     */
    private function addCountryCode($mobile)
    {
        if(is_array($mobile)){
            array_walk($mobile, function(&$value, $key) { $value = $this->countryCode.$value; });
            return $mobile;
        }

        return $this->countryCode.$mobile;
    }



    /**
     * Check Response
     * @return array
     */
    public function response(){
        $success = substr_count($this->response,'success');
        $error = substr_count($this->response,'error');

        return ['status'=>['success'=>$success,'error'=>$error],'response'=>$this->response];
    }




}