<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $endPoint }}">
        <input type=hidden name=encRequest value="{{ $encRequest }}">
        <input type=hidden name=access_code value="{{ $accessCode }}">
    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

