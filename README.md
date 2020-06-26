# IndiPay
The Laravel 5+ Package for Indian Payment Gateways. Currently supported gateway: <a href="http://www.ccavenue.com/">CCAvenue</a>, <a href="https://www.payumoney.com/">PayUMoney</a>, <a href="https://www.ebs.in">EBS</a>, <a href="http://www.citruspay.com/">CitrusPay</a> ,<a href="https://pay.mobikwik.com/">ZapakPay</a> (Mobikwik), <a href="https://dashboard.paytm.com/">Paytm</a>, <a href="http://mocker.in">Mocker</a>

<a href="https://github.com/softon/indipay/tree/laravel4">For Laravel 4.2 Package Click Here</a>

<h2>Installation</h2>
<b>Step 1:</b> Install package using composer
<pre><code>
    composer require softon/indipay
</pre></code>

<b>Step 2:</b> Add the service provider to the config/app.php file in Laravel (Optional for Laravel 5.5+)
<pre><code>
    Softon\Indipay\IndipayServiceProvider::class,
</pre></code>

<b>Step 3:</b> Add an alias for the Facade to the config/app.php file in Laravel (Optional for Laravel 5.5+)
<pre><code>
    'Indipay' => Softon\Indipay\Facades\Indipay::class,
</pre></code>

<b>Step 4:</b> Publish the config & Middleware by running in your terminal
<pre><code>
    php artisan vendor:publish --provider="Softon\Indipay\IndipayServiceProvider" 
</pre></code>

<b>Step 5:</b> Modify the app\Http\Kernel.php to use the new Middleware. 
This is required so as to avoid CSRF verification on the Response Url from the payment gateways.
<b>You may adjust the routes in the config file config/indipay.php to disable CSRF on your gateways response routes.</b>

> NOTE: You may also use the new `VerifyCsrfToken` middleware and add the routes in the `$except` array.

<pre><code>App\Http\Middleware\VerifyCsrfToken::class,</code></pre>
to
<pre><code>App\Http\Middleware\VerifyCsrfMiddleware::class,</code></pre>

<h2>Usage</h2>

Edit the config/indipay.php. Set the appropriate Gateway parameters. Also set the default gateway to use by setting the `gateway` key in config file. Then in your code... <br>
<pre><code> use Softon\Indipay\Facades\Indipay;  </code></pre>
Initiate Purchase Request and Redirect using the default gateway:-
```php 
      /* All Required Parameters by your Gateway will differ from gateway to gateway refer the gate manual */
      
      $parameters = [
        'transaction_no' => '1233221223322',
        'amount' => '1200.00',
        'name' => 'Jon Doe',
        'email' => 'jon@doe.com'
      ];
      
      $order = Indipay::prepare($parameters);
      return Indipay::process($order);
```
> Please check for the required parameters in your gateway manual. There is a basic validation in this package to check for it.

You may also use multiple gateways:-
```php 
      // gateway = CCAvenue / PayUMoney / EBS / Citrus / InstaMojo / ZapakPay / Paytm / Mocker
      
      $order = Indipay::gateway('Paytm')->prepare($parameters);
      return Indipay::process($order);
```
Get the Response from the Gateway (Add the Code to the Redirect Url Set in the config file. 
Also add the response route to the remove_csrf_check config item to remove CSRF check on these routes.):-
<pre><code> 
    public function response(Request $request)
    
    {
        // For default Gateway
        $response = Indipay::response($request);
        
        // For Otherthan Default Gateway
        $response = Indipay::gateway('NameOfGatewayUsedDuringRequest')->response($request);

        dd($response);
    
    }  
</code></pre>
The `Indipay::response` will take care of checking the response for validity as most gateways will add a checksum to detect any tampering of data. 

Important point to note is to store the transaction info to a persistant database before proceding to the gateway so that the status can be verified later.

## Payment Verification

From version v1.0.12 `Indipay` has started implementing verify method in some gateways so that the developer can verify the payment in case of pending payments etc.

```php
    $order = Indipay::verify([
        'transaction_no' => '3322344231223'
    ]);

```
The parameters to be passed, again depends on Gateway used.

> **Verify Feature Currently Supported in** : Paytm, Mocker