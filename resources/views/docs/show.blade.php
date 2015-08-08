@extends('docs.master')

@section('content')
	<div class="container">
		<div class="col-md-11 col-md-offset-1">
            <div class="row">
    			@if ($toc)
    				<div class="col-md-3 docs-sidebar">
    					<nav class="docs-toc" data-current-page="{{ $page }}">
    						{!! $toc !!}
    					</nav>
    				</div>
    			@endif

    			<div class="col-md-{{ $toc ? '9' : '12' }} docs-content">
    				<p class="pull-right">
    					<small>{{ $lastUpdated }}</small>
    				</p>

    				{!! $content !!}
    			</div>
    		</div>
        </div>
	</div>
@stop
