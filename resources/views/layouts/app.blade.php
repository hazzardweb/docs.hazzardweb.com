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

        @include('partials.scripts')
    </body>
</html>
