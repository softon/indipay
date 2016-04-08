<?php namespace Softon\Indipay;

use Softon\Indipay\Gateways\PaymentGatewayInterface;

class Indipay {

    protected $gateway;

    /**
     * @param PaymentGatewayInterface $gateway
     */
    function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function purchase($parameters = array()){

        return $this->gateway->request($parameters)->send();

    }

    public function response($request)
    {
        return $this->gateway->response($request);
    }

    public function prepare($parameters = array())
    {
        return $this->gateway->request($parameters);
    }

    public function process($order)
    {
        return $order->send();
    }



}