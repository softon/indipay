<?php namespace Softon\Indipay;

use Softon\Indipay\Gateways\CCAvenueGateway;
use Softon\Indipay\Gateways\CitrusGateway;
use Softon\Indipay\Gateways\EBSGateway;
use Softon\Indipay\Gateways\InstaMojoGateway;
use Softon\Indipay\Gateways\PaymentGatewayInterface;
use Softon\Indipay\Gateways\PayUMoneyGateway;

class Indipay {

    protected $gateway;

    /**
     * @param PaymentGatewayInterface $gateway
     */
    function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function purchase($parameters = array())
    {

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

    public function gateway($name)
    {
        switch($name)
        {
            case 'CCAvenue':
                $this->gateway = new CCAvenueGateway();
                break;

            case 'PayUMoney':
                $this->gateway = new PayUMoneyGateway();
                break;

            case 'EBS':
                $this->gateway = new EBSGateway();
                break;

            case 'Citrus':
                $this->gateway = new CitrusGateway();
                break;

            case 'InstaMojo':
                $this->gateway = new InstaMojoGateway();
                break;

        }

        return $this;
    }



}