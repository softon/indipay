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

        <input type=hidden name="lastname" @if(!empty($parameters['lastname'])) value="{{ $parameters['lastname'] }}" @endif>
        <input type=hidden name="curl" @if(!empty($parameters['curl'])) value="{{ $parameters['curl']}}" @endif>
        <input type=hidden name="address1" @if(!empty($parameters['address1'])) value="{{ $parameters['address1'] }}" @endif>
        <input type=hidden name="address2" @if(!empty($parameters['address2'])) value="{{ $parameters['address2'] }}" @endif>
        <input type=hidden name="city" @if(!empty($parameters['city'])) value="{{ $parameters['city'] }}" @endif>
        <input type=hidden name="state" @if(!empty($parameters['state'])) value="{{ $parameters['state'] }}" @endif>
        <input type=hidden name="country" @if(!empty($parameters['country'])) value="{{ $parameters['country'] }}" @endif>
        <input type=hidden name="zipcode" @if(!empty($parameters['zipcode'])) value="{{ $parameters['zipcode'] }}" @endif>
        <input type=hidden name="udf1" @if(!empty($parameters['udf1'])) value="{{ $parameters['udf1'] }}" @endif>
        <input type=hidden name="udf2" @if(!empty($parameters['udf2'])) value="{{ $parameters['udf2'] }}" @endif>
        <input type=hidden name="udf3" @if(!empty($parameters['udf3'])) value="{{ $parameters['udf3'] }}" @endif>
        <input type=hidden name="udf4" @if(!empty($parameters['udf4'])) value="{{ $parameters['udf4'] }}" @endif>
        <input type=hidden name="udf5" @if(!empty($parameters['udf5'])) value="{{ $parameters['udf5'] }}" @endif>
        <input type=hidden name="pg" @if(!empty($parameters['pg'])) value="{{ $parameters['pg'] }}" @endif>
    </form>
    <script language='javascript'>
        document.redirect.submit();
    </script>
</body>

</html>