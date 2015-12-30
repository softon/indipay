<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $endPoint }}">
        <input type=hidden name="channel" value="{{ $parameters['channel'] }}">
        <input type=hidden name="account_id" value="{{ $parameters['account_id'] }}">
        <input type=hidden name="reference_no" value="{{ $parameters['reference_no'] }}">
        <input type=hidden name="amount" value="{{ $parameters['amount'] }}">
        <input type=hidden name="mode" value="{{ $parameters['mode'] }}">
        <input type=hidden name="currency" value="{{ $parameters['currency'] }}">
        <input type=hidden name="description" value="{{ $parameters['description'] }}">
        <input type=hidden name="return_url" value="{{ $parameters['return_url'] }}">
        <input type=hidden name="name" value="{{ $parameters['name'] }}">
        <input type=hidden name="address" value="{{ $parameters['address'] }}">
        <input type=hidden name="city" value="{{ $parameters['city'] }}">
        <input type=hidden name="state" value="{{ $parameters['state'] or ''}}">
        <input type=hidden name="country" value="{{ $parameters['country'] }}">
        <input type=hidden name="postal_code" value="{{ $parameters['postal_code'] }}">
        <input type=hidden name="phone" value="{{ $parameters['phone'] }}">
        <input type=hidden name="email" value="{{ $parameters['email'] }}">

        <input type=hidden name="ship_name" value="{{ $parameters['ship_name'] or '' }}">
        <input type=hidden name="ship_address" value="{{ $parameters['ship_address'] or '' }}">
        <input type=hidden name="ship_city" value="{{ $parameters['ship_city'] or '' }}">
        <input type=hidden name="ship_state" value="{{ $parameters['ship_state'] or '' }}">
        <input type=hidden name="ship_country" value="{{ $parameters['ship_country'] or '' }}">
        <input type=hidden name="ship_postal_code" value="{{ $parameters['ship_postal_code']  or ''}}">
        <input type=hidden name="ship_phone" value="{{ $parameters['ship_phone'] or '' }}">
        <input type=hidden name="page_id" value="{{ $parameters['page_id'] or '' }}">
        <input type=hidden name="secure_hash" value="{{ $hash }}">

    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

