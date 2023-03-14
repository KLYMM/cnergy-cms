<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login English Site Newshub</title>
    @vite(['resources/js/app.js', 'resources/sass/init.scss'])
    <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png">
</head>

<body>
    <div id="auth">
        @foreach($errors->all() as $e)
        <div class="alert alert-danger alert-dismissible show fade">
            <strong>{{ $e }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endforeach
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href=""><img src="assets/images/logo/logo.svg" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Log in.</h1>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Sign in with <a href="{{'/auth/redirect'}}" class="font-bold">Google</a>.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>
    </div>
</body>

<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/app.js"></script>

</html>
