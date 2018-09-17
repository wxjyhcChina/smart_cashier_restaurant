@extends('frontend.layouts.app')

@if (!Auth::guard('web')->guest())
    <script type="text/javascript">
        window.location = "./admin/dashboard";//here double curly bracket
    </script>
@else
    <script type="text/javascript">
        window.location = "./login";//here double curly bracket
    </script>
@endif