<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Area 51</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
		<!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js">
			$(document).ready(function() {
				setTimeout(function() {
					$('#firstresponder').focus();
				}, 100);
			});
		</script>
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header">Area 51</div>
			{{ Form::open(['data-remote' => 'data-remote', 'route' => ['adminauth.login'], 'method' => 'post', 'autocomplete' => 'off']) }}
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="email" id="firstresponder" name="email" class="form-control" placeholder="Email" required />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required />
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Sign In</button>  
                    
                    <p><a href="#">Forgot Password</a></p>
                    
                   
                </div>
            {{ Form::close() }}

            <div class="margin text-center">
                <span>&copy; SwissWork X</span>
                

            </div>
        </div>


        
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>