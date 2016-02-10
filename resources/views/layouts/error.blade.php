<!DOCTYPE html>
<html lang="en">
    <head>
        @include('partials.head')
        <title>HazzardWeb Docs</title>
    </head>
    <body>
        <div class="error-page">
            <div class="container">
                @yield('content')

                <hr>
                <div class="text-center">
                    <a href="./">Go to main documentation page</a>
                </div>
            </div>
        </div>
    </body>
</html>
