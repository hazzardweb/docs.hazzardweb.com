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
        @yield('content')

        @include('partials.scripts')
    </body>
</html>
