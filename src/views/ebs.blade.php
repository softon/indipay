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
        <input type=hidden name="state" value="{{ isset($parameters['state']) ? $parameters['state'] : ''}}">
        <input type=hidden name="country" value="{{ $parameters['country'] }}">
        <input type=hidden name="postal_code" value="{{ $parameters['postal_code'] }}">
        <input type=hidden name="phone" value="{{ $parameters['phone'] }}">
        <input type=hidden name="email" value="{{ $parameters['email'] }}">

        <input type=hidden name="ship_name" value="{{ isset($parameters['ship_name']) ? $parameters['ship_name'] : '' }}">
        <input type=hidden name="ship_address" value="{{ isset($parameters['ship_address']) ? $parameters['ship_address'] : '' }}">
        <input type=hidden name="ship_city" value="{{ isset($parameters['ship_city']) ? $parameters['ship_city'] : '' }}">
        <input type=hidden name="ship_state" value="{{ isset($parameters['ship_state']) ? $parameters['ship_state'] : '' }}">
        <input type=hidden name="ship_country" value="{{ isset($parameters['ship_country']) ? $parameters['ship_country'] : '' }}">
        <input type=hidden name="ship_postal_code" value="{{ isset($parameters['ship_postal_code'])  ? $parameters['ship_postal_code'] : ''}}">
        <input type=hidden name="ship_phone" value="{{ isset($parameters['ship_phone']) ? $parameters['ship_phone'] : '' }}">
        <input type=hidden name="page_id" value="{{ isset($parameters['page_id']) ? $parameters['page_id'] : '' }}">
        <input type=hidden name="secure_hash" value="{{ $hash }}">

    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

