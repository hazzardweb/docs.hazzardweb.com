<ul class="nav navbar-nav">
    @if (isset($currentManual))
        @if (count($manuals) > 1)
            <li class="dropdown active">
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

