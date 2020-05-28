<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <meta content="「match」は、エンジニア案件専用のアプリです。エンジニア案件に特化してるので、依頼する方と応募する方、双方が簡単に使用できます。案件の依頼に必要な項目は、たった3つ、手数料はかかりません。さまざまな強みを持ったエンジニアが多数在籍しています。" name="description">
    <meta name="keywords" content="エンジニア,クラウドソーシング,簡単,エンジニア案件">

    <meta property="og:title" content="エンジニア案件専用アプリ - match" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ env('APP_URL') }}" />
    <meta property="og:image" content="{{ env('APP_URL') }}/storage/pixta_48826839_M.jpg" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:description" content="「match」は、エンジニア案件専用のアプリです。エンジニア案件に特化してるので、依頼する方と応募する方、双方が簡単に使用できます。案件の依頼に必要な項目は、たった3つ、手数料はかかりません。さまざまな強みを持ったエンジニアが多数在籍しています。" />

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />

    <link rel="shortcut icon" href="{{{ asset('img/match.ico') }}}">
</head>
<body>
<div id="app"></div>
</body>
</html>
