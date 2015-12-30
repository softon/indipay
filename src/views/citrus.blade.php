<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $endPoint }}">
        <input type=hidden name="merchantTxnId" value="{{ $parameters['merchantTxnId'] }}">
        <input type=hidden name="secSignature" value="{{ $hash }}">
        <input type=hidden name="orderAmount" value="{{ $parameters['orderAmount'] }}">
        <input type=hidden name="currency" value="{{ $parameters['currency'] }}">
        <input type=hidden name="returnUrl" value="{{ $parameters['returnUrl'] }}">
        <input type=hidden name="notifyUrl" value="{{ $parameters['notifyUrl'] }}">
    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

