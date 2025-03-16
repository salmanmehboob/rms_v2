<!DOCTYPE html>
<html lang="en">

<head>
    <!-- PAGE TITLE HERE -->
    <title> @yield('title')</title>
    @include ('layouts.header_files')
</head>

<body>

    {{--    @include ('layouts.preloader')--}}
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        @include ('layouts.header_nav')
        @include ('layouts.header')
        @include ('layouts.sidebar')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container">

                @yield('content')
                @include ('layouts.footer')
            </div>
        </div>
    </div>

    <!--**********************************
        Main wrapper end
    ***********************************-->
    @include ('layouts.footer_files')

</body>

</html>