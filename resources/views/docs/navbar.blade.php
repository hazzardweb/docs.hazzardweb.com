<nav class="navbar navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a href="/" class="navbar-brand">Documentation</a>
		</div>

		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				@if (isset($currentManual))
					@if (count($manuals) > 1)
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<span class="glyphicon glyphicon-book"></span>
								{{ config("docs.manual_names.$currentManual", $currentManual) }}
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								@foreach ($manuals as $manual)
									<li>
										<a href="{{ url('/'.$manual) }}">{{ config("docs.manual_names.$manual", $manual) }}</a>
									</li>
								@endforeach
							</ul>
						</li>
					@else
						<li>
							<p class="navbar-text">
								<span class="glyphicon glyphicon-book"></span>
								{{ config("docs.manual_names.$currentManual", $currentManual) }}
							</p>
						</li>
					@endif
				@endif
			</ul>

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

			<div class="nav-toc">
				{!! $toc !!}
			</div>

			<ul class="nav navbar-nav navbar-right">
				<li><a href="{{ config('app.url') }}">HazzardWeb</a></li>
			</ul>
		</div>
	</div>
</nav>
