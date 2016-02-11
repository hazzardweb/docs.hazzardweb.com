@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-default" role="navigation">
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
                        <li><a href="http://codecanyon.net/user/hazzardweb#contact" target="_blank">Support</a></li>
                        <li><a href="http://hazzardweb.com" target="_blank">HazzardWeb</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="col-md-11 col-md-offset-1">
            @foreach ($docs as $id => $doc)
                <h2>&rsaquo; <a href="{{ route('show', $id) }}">{{ $doc['name'] }}</a></h2>
            @endforeach
        </div>
    </div>
@stop
