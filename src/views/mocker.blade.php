<html>
<head>
    <title>IndiPay</title>
</head>
<body>
    <form method="post" name="redirect" action="{{ $end_point }}">
        @foreach ($data as $param_key=>$param_name)
        	<input type=hidden name="{{$param_key}}" value="{{ $param_name }}">
        @endforeach
    </form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

