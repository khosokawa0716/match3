@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('matchをご利用いただきありがとうございます。')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('合同会社ワンストップ腰越')<br>
{{ config('app.name') }}@lang('運営事務局')
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
{{--@lang(--}}
{{--    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".--}}
{{--    'into your web browser: [:actionURL](:actionURL)',--}}
{{--    [--}}
{{--        'actionText' => $actionText,--}}
{{--        'actionURL' => $actionUrl,--}}
{{--    ]--}}
{{--)--}}
{{ $actionText }}ボタンが利用できない場合は、以下のURLをコピー＆ペーストしてブラウザから直接アクセスしてください。
[{{ $actionUrl }}]({!! $actionUrl !!})
@endslot
@endisset
@endcomponent