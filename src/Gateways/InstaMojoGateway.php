<?php namespace Softon\Indipay\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Softon\Indipay\Exceptions\IndipayParametersMissingException;

class InstaMojoGateway implements PaymentGatewayInterface {

    protected $parameters = array();
    protected $merchantData = '';
    protected $encRequest = '';
    protected $testMode = false;
    protected $api_key = '';
    protected $auth_token = '';
    protected $liveEndPoint = 'https://www.instamojo.com/api/1.1/';
    protected $testEndPoint = 'https://test.instamojo.com/api/1.1/';
    public $response = '';

    function __construct()
    {
        $this->testMode = Config::get('indipay.testMode');
        $this->api_key = Config::get('indipay.instamojo.api_key');
        $this->auth_token = Config::get('indipay.instamojo.auth_token');

        $this->parameters['redirect_url'] = url(Config::get('indipay.instamojo.redirectUrl'));
    }

    public function getEndPoint($param='')
    {
        $endpoint = $this->testMode?$this->testEndPoint:$this->liveEndPoint;
        return $endpoint.$param;
    }

    public function request($parameters)
    {
        $this->parameters = array_merge($this->parameters,$parameters);

        $this->checkParameters($this->parameters);

        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->getEndPoint('payment-requests/'),
                                        [
                                            'headers'=> array(
                                                'X-Api-Key' => $this->api_key,
                                                'X-Auth-Token' => $this->auth_token,
                                            ),
                                            'form_params' => $this->parameters,
                                        ])->getBody()->getContents();
        $response = json_decode($response);

        if($response->success){
            $this->response = $response;
        }

        return $this;

    }

    /**
     * @return mixed
     */
    public function send()
    {

        Log::info('Indipay Payment Request Initiated: ');
        //dd($this->response->payment_request->longurl);
        return View::make('indipay::instamojo')->with('longurl',$this->response->payment_request->longurl);

    }


    /**
     * Check Response
     * @param $request
     * @return array
     */
    public function response($request)
    {
        $payment_request_id = Request::input('payment_request_id');
        $payment_id = Request::input('payment_id');

        $client = new \GuzzleHttp\Client();
        $response = $client->get($this->getEndPoint('payment-requests/'.$payment_request_id.'/'.$payment_id.'/'),
            [
                'headers'=> array(
                    'X-Api-Key' => $this->api_key,
                    'X-Auth-Token' => $this->auth_token,
                ),
            ])->getBody()->getContents();
        $response = json_decode($response);

        if($response->success){
            return $response;
        }

        return false;

    }


    /**
     * @param $parameters
     * @throws IndipayParametersMissingException
     */
    public function checkParameters($parameters)
    {
        $validator = Validator::make($parameters, [
            'purpose' => 'required|max:30',
            'amount' => 'required|numeric|between:9,200000',
            'buyer_name' => 'max:100',
            'email' => 'email|max:75',
            'phone' => 'digits:10',
        ]);

        if ($validator->fails()) {
            throw new IndipayParametersMissingException;
        }

    }




}
