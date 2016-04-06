# IndiPay
The Laravel 4.2 Package for Indian Payment Gateways. Currently supported gateway: <a href="http://www.ccavenue.com/">CCAvenue</a>, <a href="https://www.payumoney.com/">PayUMoney</a>, <a href="https://www.ebs.in">EBS</a>, <a href="http://www.citruspay.com/">CitrusPay</a>

<h2>Installation</h2>
<b>Step 1:</b> Install package using composer
<pre><code>
    composer require softon/indipay=~0.1
</pre></code>

<b>Step 2:</b> Add the service provider to the app/config/app.php file in Laravel
<pre><code>
    'Softon\Indipay\IndipayServiceProvider',
</pre></code>

<b>Step 3:</b> Add an alias for the Facade to the app/config/app.php file in Laravel
<pre><code>
    'Indipay' => 'Softon\Indipay\Facades\Indipay',
</pre></code>

<b>Step 4:</b> Publish the config & views by running in your terminal
<pre><code>
    php artisan config:publish softon/indipay
	php artisan view:publish softon/indipay
</pre></code>

<b>Step 5:</b> Modify the app\config\packages\softon\indipay\config.php to use appropriate gateway parameters. 

<h2>Usage</h2>

<pre><code> use Softon\Indipay\Facades\Indipay;  </code></pre>
Initiate Purchase Request and Redirect:-
<pre><code> 
      /* All Required Parameters by your Gateway */
      
      $parameters = [
      
        'tid' => '1233221223322',
        
        'order_id' => '1232212',
        
        'amount' => '1200.00',
        
      ];
      
      return Indipay::purchase($parameters);
</code></pre>
Get the Response from the Gateway (Note: response route should not have csrf filter):-
<pre><code> 
    Route::post('indipay/response', function(Request $request)
	{
    
        $response = Indipay::response($request);

        dd($response);
    
    }  
</code></pre>