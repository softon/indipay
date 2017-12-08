<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $endPoint }}">
        @foreach($params as $param_key=>$param_value)
        	@if($param_key=='returnUrl')
        		<input type="hidden" name="{{ $param_key }}" value="{{ $param_value  }}" />
        	@else
				<input type="hidden" name="{{ $param_key }}" value="{{ $param_value  }}" />
        	@endif

        @endforeach
        <input type="hidden" name="checksum" value="{{ $checksum }}" />
    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

