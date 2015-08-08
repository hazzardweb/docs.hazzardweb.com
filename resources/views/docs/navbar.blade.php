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

                <a href="./" class="navbar-brand">
                    {{ config("docs.manual_names.$currentManual", $currentManual) }}
                </a>
            </div>

            <div id="docs-navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    @if (isset($currentVersion))
                        @if (count($versions) > 1)
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-bookmark"></span>
                                    {{ $currentVersion }}
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach ($versions as $version)
                                        <li>
                                            <a href="{{ url('/'.$currentManual.'/'. $version) }}">{{ $version }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <p class="navbar-text">
                                    <span class="glyphicon glyphicon-bookmark"></span>
                                    {{ $currentVersion }}
                                </p>
                            </li>
                        @endif
                    @endif
                </ul>

                <div class="docs-nav-toc"></div>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="mailto:hazzardweb@gmail.com">Email Support</a></li>
                    <li><a href="{{ config('app.url') }}">HazzardWeb</a></li>
                </ul>
            </div>
        </div>
	</div>
</nav>
