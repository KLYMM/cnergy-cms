<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>KLY - NewsHub</title>

    <link rel="shortcut icon" href="../../images/logo/logo.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../../images/logo/favicon.png" type="image/png">
    @vite(['resources/js/app.js', 'resources/sass/init.scss', 'resources/sass/components/_menu.scss'])
    <script type="text/javascript">
        function timeNow() {
            // Refresh rate in milli seconds
            var refresh = 1000;
            mytime = setTimeout('displayTimeNow()', refresh)
        }

        function displayTimeNow() {
            var x = new Date()
            document.getElementById('footerTime').innerHTML = x;
            timeNow();
        }
    </script>
</head>
<body onload=displayTimeNow();>
    <div id="app">

        <x-sidebar />
        <div id="main" class='layout-navbar'>
            <x-navbar />
            <div id="main-content">
                <div class="page-heading">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <strong>{{ session('status') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible show fade">
                            <strong>
                                {{ $error }}
                            </strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endforeach
                    @yield('body')
                </div>

                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <span id='footerTime'></span>
                        </div>
                        <div class="float-end">
                            <p>KapanLagi Youniverse</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.slim.js" integrity="sha256-dWvV84T6BhzO4vG6gWhsWVKVoa4lVmLnpBOZh/CAHU4=" crossorigin="anonymous"></script>
    <script type="module">
        $(document).ready(function() {
    
            // Add hover action for dropdowns
            let dropdown_hover = $(".dropdown-hover");
            dropdown_hover.on('mouseover', function() {
                let menu = $(this).find('.dropdown-menu'),
                    toggle = $(this).find('.dropdown-toggle');
                menu.addClass('show');
                toggle.addClass('show').attr('aria-expanded', true);
            });
            dropdown_hover.on('mouseout', function() {
                let menu = $(this).find('.dropdown-menu'),
                    toggle = $(this).find('.dropdown-toggle');
                menu.removeClass('show');
                toggle.removeClass('show').attr('aria-expanded', false);
            });
    
        });
    </script>
    @yield('javascript')
</body>

</html>
