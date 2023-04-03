<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $endPoint }}">
        @foreach($params as $param_key=>$param_value)
			<input type="hidden" name="{{ $param_key }}" value="{{ $param_value  }}" />
        @endforeach
        <input type="hidden" name="CHECKSUMHASH" value="{{ $checksum }}" />
    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

