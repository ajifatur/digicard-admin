<!DOCTYPE html>
<html lang="en">
    <head>
        @include('template/admin/_head')
        @include('template/admin/_css')
        @yield('css-extra')
        <title>@yield('title') | {{ get_website_name() }}</title>
    </head>
    <body class="app sidebar-mini">
        @include('template/admin/_header')
        @include('template/admin/_sidebar')
        @yield('content')
        @include('template/admin/_js')
        @yield('js-extra')
    </body>
</html>