
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') | Point of Sale</title>
        <meta name="robots" content="noindex,nofollow">
        <meta name="author" content="Admin">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('includes.style')
    </head>
    <body class="hold-transition sidebar-mini layout-navbar-fixed">
        <div class="wrapper">
            
            @include('includes.navbar')
            
            @include('includes.sidebar')

                @yield('admin_content')

            @include('includes.footer')

        </div>
        @include('includes.script')

    </body>
</html>
