@extends('docs.master')

@section('content')
	<div class="container">
		<div class="row">
			@if (! is_null($toc))
				<div id="sidebar" class="col-md-3">
					<nav class="toc">
						{!! $toc !!}
					</nav>
				</div>
			@endif

			<div class="col-md-{{ is_null($toc) ? '12' : '9' }} documentation">
				<p class="pull-right">
					<small>{{ $lastUpdated }}</small>
				</p>

				{!! $content !!}
			</div>
		</div>
	</div>
@stop
