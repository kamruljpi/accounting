<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>{{ trans('others.company_name')}}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<script src="{{ asset("js/stylesheets/jquery.min.js") }}"></script>
	
	<link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
</head>
<body>

	@yield('body')

	<script src="{{ asset("assets/scripts/frontend.js") }}" type="text/javascript"></script>
    <!--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>-->
<script src="{{ asset("js/bootstrap.min.js") }}"></script>
</body>
</html>