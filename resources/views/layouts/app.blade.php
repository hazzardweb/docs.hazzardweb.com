<!DOCTYPE html>
<html lang="en">
    <head>
        @include('partials.head')

        @if (isset($currentDoc))
            <title>{{ $title }} - {{ $currentDoc['name'] }} {{ $currentVersion }} - HazzardWeb</title>
        @else
            <title>HazzardWeb Docs</title>
        @endif
    </head>
    <body>
        <div id="pjax-container">
            @yield('content')
        </div>

        <div class="footer">
            Built with <a href="https://laravel.com/" target="_blank">Laravel</a>
            and available on <a href="https://github.com/hazzardweb/docs.hazzardweb.com" target="_blank">GitHub</a>.
            © {{ date('Y') }} Cretu Eusebiu.
        </div>

        @include('partials.scripts')
        @include('partials.ga')
    </body>
</html>
