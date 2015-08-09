@extends('docs.master')

@section('content')
    <nav class="navbar docs-navbar" role="navigation">
        <div class="container">
            <div class="col-md-11 col-md-offset-1">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#docs-navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="./" class="navbar-brand">Documentation</a>
                </div>
                <div id="docs-navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="mailto:hazzardweb@gmail.com">Email Support</a></li>
                        <li><a href="{{ config('app.url') }}">HazzardWeb</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="col-md-11 col-md-offset-1">
            @foreach ($manuals as $manual)
                <h2>&rsaquo; <a href="{{ route('docs.show', $manual) }}">{{ config("docs.manual_names.$manual", $manual) }}</a></h2>
            @endforeach
        </div>
    </div>
@stop
