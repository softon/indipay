<?php namespace Softon\Indipay\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Softon\Indipay\Exceptions\IndipayParametersMissingException;

class MockerGateway implements PaymentGatewayInterface {

    protected $parameters = array();
    protected $service = 'default';
    
    protected $liveEndPoint = 'https://mocker.in/payment/';
    protected $testEndPoint = 'https://mocker.in/payment/';
    protected $statusEndPoint = 'https://mocker.in/payment/status';
    public $response = '';

    function __construct()
    {
        $this->service = Config::get('indipay.mocker.service');
        $this->testMode = Config::get('indipay.testMode');
        $this->parameters['redirect_url'] = url(Config::get('indipay.mocker.redirect_url'));
    }

    public function getEndPoint()
    {
        return $this->testMode?$this->testEndPoint.$this->service:$this->liveEndPoint.$this->service;
    }

    public function request($parameters)
    {
        $this->parameters = array_merge($this->parameters,$parameters);

        $this->checkParameters($this->parameters);

        return $this;

    }

    /**
     * @return mixed
     */
    public function send()
    {

        Log::info('Indipay Payment Request Initiated: ');
        return View::make('indipay::mocker')->with('data',$this->parameters)
                                            ->with('end_point',$this->getEndPoint());

    }


    /**
     * Check Response
     * @param $request
     * @return array
     */
    public function response($request)
    {
        $params = $request->all();
        if($params['transaction_status'] == 'success'){
            $params['status'] = 'success';
            return $params;
        }
        $params['status'] = 'failure';
        return $params;
    }

    /**
     * Check Response
     * @param $request
     * @return array
     */
    public function verify($parameters)
    {
        if(!isset($parameters['transaction_no']) || !isset($parameters['amount'])){
            throw new IndipayParametersMissingException;
        } 
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->statusEndPoint, [
            'query' => [
                'transaction_no' => $parameters['transaction_no'],
                'redirect_url' => $this->parameters['redirect_url']
            ]
        ]);
        $mocker_data = json_decode($res->getBody());
        if($mocker_data->amount == $parameters['amount']){
            $mocker_data['status'] = 'success';
            return $mocker_data;
        }
        $mocker_data['status'] = 'failure';
        return $mocker_data;
    }


    /**
     * @param $parameters
     * @throws IndipayParametersMissingException
     */
    public function checkParameters($parameters)
    {
        if($this->service == 'default') {
            $validator = Validator::make($parameters, [
                'transaction_no' => 'required',
                'redirect_url' => 'required|url',
                'amount' => 'required|numeric',
            ]);
            
        }elseif($this->service == 'instamojo'){
            $validator = Validator::make($parameters, [
                'amount' => 'required|numeric|between:9,200000',
                'redirect_url' => 'required|url',
            ]);
        }elseif($this->service == 'ccavenue'){
            $validator = Validator::make($parameters, [
                'amount' => 'required|numeric|between:9,200000',
                'redirect_url' => 'required|url',
            ]);
        }

        if ($validator->fails()) {
            throw new IndipayParametersMissingException($validator->errors());
        }

    }



}
