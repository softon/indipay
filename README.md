# IndiPay
The Laravel 5 Package for Indian Payment Gateways. Currently supported gateway: <a href="http://www.ccavenue.com/">CCAvenue</a>, <a href="https://www.payumoney.com/">PayUMoney</a>, <a href="https://www.ebs.in">EBS</a>, <a href="http://www.citruspay.com/">CitrusPay</a>

<a href="https://github.com/softon/indipay/tree/laravel4">For Laravel 4.2 Package Click Here</a>

<h2>Installation</h2>
<b>Step 1:</b> Install package using composer
<pre><code>
    composer require softon/indipay
</pre></code>

<b>Step 2:</b> Add the service provider to the config/app.php file in Laravel
<pre><code>
    Softon\Indipay\IndipayServiceProvider::class,
</pre></code>

<b>Step 3:</b> Add an alias for the Facade to the config/app.php file in Laravel
<pre><code>
    'Indipay' => Softon\Indipay\Facades\Indipay::class,
</pre></code>

<b>Step 4:</b> Publish the config & Middleware by running in your terminal
<pre><code>
    php artisan vendor:publish
</pre></code>

<b>Step 5:</b> Modify the app\Http\Kernel.php to use the new Middleware. 
This is required so as to avoid CSRF verification on the Response Url from the payment gateways.
<b>You may adjust the routes in the config file config/indipay.php to disable CSRF on your gateways response routes.</b>
<pre><code>
    'App\Http\Middleware\VerifyCsrfToken',
</pre></code>
to
<pre><code>
    'App\Http\Middleware\VerifyCsrfMiddleware',
</pre></code>

<h2>Usage</h2>

Edit the config/indipay.php. Set the appropriate Gateway and its parameters. Then in your code... <br>
<pre><code> use Softon\Indipay\Facades\Indipay;  </code></pre>
Initiate Purchase Request and Redirect using the default gateway:-
```php 
      /* All Required Parameters by your Gateway */
      
      $parameters = [
      
        'tid' => '1233221223322',
        
        'order_id' => '1232212',
        
        'amount' => '1200.00',
        
      ];
      
      $order = Indipay::prepare($parameters);
      return Indipay::process($order);
```

Initiate Purchase Request and Redirect using any of the configured gateway:-
```php 
      /* All Required Parameters by your Gateway */
      
      $parameters = [
      
        'tid' => '1233221223322',
        
        'order_id' => '1232212',
        
        'amount' => '1200.00',
        
      ];
      
      // gateway = CCAvenue / PayUMoney / EBS / Citrus / InstaMojo
      
      $order = Indipay::gateway('NameOfGateway')->prepare($parameters);
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
