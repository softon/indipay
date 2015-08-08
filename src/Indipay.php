<?php namespace Softon\Indipay;

use Softon\Indipay\Gateways\PaymentGatewayInterface;

class Indipay {

    protected $gateway;
    protected $view;


    /**
     * @param PaymentGatewayInterface $gateway
     */
    function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function send($mobile,$view,$params=[]){

        $message = $this->view->getView($view,$params)->render();
        return $this->gateway->sendSms($mobile,$message);
    }

    public function send_raw($mobile,$message){
        return $this->gateway->sendSms($mobile,$message);
    }
}