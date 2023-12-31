<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <title>奧客終結者</title>

        <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
        <!-- BOOTSTRAP 5.2.2 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <!-- TV WALL -->
        <link href="{{ URL::asset('css/stylesheet.css') }}" rel="stylesheet">

        <!-- JQUERY 3.6.1 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- BOOTSTRAP 5.2.2 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <!-- FONTAWESOME 6.3.0 -->
        <script src="https://kit.fontawesome.com/95f3ce1edc.js" crossorigin="anonymous"></script>
        <!-- VUE 3 -->
        <script src="https://unpkg.com/vue@3.2.47/dist/vue.global.prod.js"></script>
    </head>
    <body>
        <section id="body-content">