<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $endPoint }}">
        <input type=hidden name="key" value="{{ $parameters['key'] }}">
        <input type=hidden name="hash" value="{{ $hash }}">
        <input type=hidden name="txnid" value="{{ $parameters['txnid'] }}">
        <input type=hidden name="amount" value="{{ $parameters['amount'] }}">
        <input type=hidden name="firstname" value="{{ $parameters['firstname'] }}">
        <input type=hidden name="email" value="{{ $parameters['email'] }}">
        <input type=hidden name="phone" value="{{ $parameters['phone'] }}">
        <input type=hidden name="productinfo" value="{{ $parameters['productinfo'] }}">
        <input type=hidden name="surl" value="{{ $parameters['surl'] }}">
        <input type=hidden name="furl" value="{{ $parameters['furl'] }}">
        <input type=hidden name="service_provider" value="{{ $parameters['service_provider'] }}">


        <input type=hidden name="lastname" value="{{ $parameters['lastname'] or '' }}">
        <input type=hidden name="curl" value="{{ $parameters['curl'] or '' }}">
        <input type=hidden name="address1" value="{{ $parameters['address1'] or '' }}">
        <input type=hidden name="address2" value="{{ $parameters['address2'] or '' }}">
        <input type=hidden name="city" value="{{ $parameters['city'] or '' }}">
        <input type=hidden name="state" value="{{ $parameters['state'] or '' }}">
        <input type=hidden name="country" value="{{ $parameters['country'] or '' }}">
        <input type=hidden name="zipcode" value="{{ $parameters['zipcode'] or '' }}">
        <input type=hidden name="udf1" value="{{ $parameters['udf1'] or '' }}">
        <input type=hidden name="udf2" value="{{ $parameters['udf2'] or '' }}">
        <input type=hidden name="udf3" value="{{ $parameters['udf3'] or '' }}">
        <input type=hidden name="udf4" value="{{ $parameters['udf4'] or '' }}">
        <input type=hidden name="udf5" value="{{ $parameters['udf5'] or '' }}">
        <input type=hidden name="pg" value="{{ $parameters['pg'] or '' }}">
    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

