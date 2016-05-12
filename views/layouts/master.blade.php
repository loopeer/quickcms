<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title>{{ config('quickcms.site_title') }}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @include('backend::layouts.link')
    @yield('style')
</head>
<body class="{{ config('quickcms.admin_body_layout') }}">
@include('backend::layouts.header')
@include('backend::layouts.nav')
<div id="main" role="main">
    @yield('content')
</div>
@include('backend::layouts.script')
@yield('script')
</body>
</html>
