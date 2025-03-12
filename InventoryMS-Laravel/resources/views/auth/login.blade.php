<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
    <title>Login | IMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/linearicons/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/Favicon akti.png')}}">
    <style>
        body {
            background: url('{{asset('assets/img/background.jpg')}}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Source Sans Pro', sans-serif;
        }
        .auth-box {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .header img {
            margin-bottom: 20px;
        }
        .btn-success {
        background-color: #7A0B0B;  
        border-color: #600900;
        }
        .btn-success:hover,
        .btn-success:focus {
        background-color: #600900;
        border-color: #600900;
        }
        .alert {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle">
                <div class="auth-box">
                    <div class="left">
                        <div class="content">
                            <div class="header text-center">
                                <img src="{{asset('assets/img/Favicon akti.png')}}" alt="IMS Logo" style="max-width: 100px; height: auto;">
                                <p class="lead">Login Admin</p>
                            </div>
                            <form class="form-auth-small" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="signin-email" class="control-label sr-only">Email</label>
                                    <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="signin-email" value="{{ old('email') }}" required placeholder="Email">
                                    @if ($errors->has('email'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <strong><i class="icon fa fa-ban"></i> Alert!</strong> &nbsp; {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="signin-password" class="control-label sr-only">Password</label>
                                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="signin-password" placeholder="Password">
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group clearfix">
                                    <label class="fancy-checkbox element-left">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span>Remember me</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg btn-block">LOGIN</button>
                            </form>
                        </div>
                    </div>
                    <div class="right">
                        <div class="overlay"></div>
                        <div class="content text">
                            <h1 class="heading">Inventory Management System AKTI</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        });
    </script>
</body>

</html>