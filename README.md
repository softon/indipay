# sms
Simple SMS Gateway Package for sending short text messages from your Application. Facade for Laravel 5.Currently supported Gateways Clickatell, MVaayoo, Gupshup, SmsAchariya, SmsCountry, SmsLane / Any HTTP/s based Gateways are supported by Custom Gateway. Log gateway can be used for testing.

<strong>Installation</strong>

<ol>
  <li>Edit the composer.json add to the require array & run composer update<br>
      <pre><code> "softon/sms": "dev-master" </code></pre>
      <pre><code> composer update </code></pre>
  </li>
  <li>Add the service provider to the config/app.php file in Laravel<br>
      <pre><code> 'Softon\Sms\SmsServiceProvider', </code></pre>
      
  </li>
  <li>Add an alias for the Facade to the config/app.php file in Laravel<br>
      <pre><code> 'Sms' => 'Softon\Sms\Facades\Sms', </code></pre>
      
  </li>
  <li>Publish the config & views by running <br>
      <pre><code> php artisan vendor:publish </code></pre>
      
  </li>
</ol>


<strong>Usage</strong>

Edit the config/sms.php. Set the appropriate Gateway and its parameters. Then in your code... <br>
<pre><code> use Softon\Sms\Facades\Sms;  </code></pre>
Send Single SMS:-
<pre><code> Sms::send('9090909090','sms.test',['param1'=>'Name 1']);  </code></pre>
Send Multiple SMS:-
<pre><code> Sms::send(['87686655455','1212121212','2323232323'],'sms.test',['param1'=>'Name 1']);  </code></pre>
With Response:-
<pre><code> Sms::send(['87686655455','1212121212','2323232323'],'sms.test',['param1'=>'Name 1'])->response();  </code></pre>


<strong>Custom Gateway</strong>

Actual Url : <code>http://example.com/api/sms.php?uid=737262316a&pin=YOURPIN&sender=your_sender_id&route=0&mobile=MOBILE&message=MESSAGE&pushid=1</code>

Config of Custom Gateway :

<pre><code> 
        'custom' => [                           
             'url' => 'http://example.com/api/sms.php?',
             'params' => [
                 'send_to_name' => 'mobile',
                 'msg_name' => 'message',
                 'others' => [
                     'uid' => '737262316a',
                     'pin' => 'YOURPIN',
                     'sender' => 'your_sender_id',
                     'route' => '0',
                     'pushid' => '1',
                 ],
             ],
             'add_code' => true,
         ],
 </code></pre>
