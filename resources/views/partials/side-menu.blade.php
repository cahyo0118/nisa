<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="./index.html">
            <img src="{{ asset('assets/img/brand/blue.png') }}" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
    @include('partials.user-menu-mobile')

    <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="./index.html">
                            <img src="{{ asset('/assets/img/brand/blue.png') }}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                                aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('generate_options.index') }}">
                        <i class="fas fa-grip-vertical text-primary"></i> Generate Options
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.index') }}">
                        <i class="fas fa-project-diagram text-primary"></i> Projects
                    </a>
                </li>
            </ul>

            @if(Route::is('*tables*') || Route::is('*menus*'))
                <hr class="my-3">
                <h6 class="navbar-heading text-muted">Menus List</h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.menus', $id) }}">
                            <i class="fas fa-search text-primary"></i> See All Menus
                        </a>
                    </li>
                </ul>

                <hr class="my-3">
                <h6 class="navbar-heading text-muted">Tables List</h6>

                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.tables', $id) }}">
                            <i class="fas fa-search text-primary"></i> See All Tables
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tables.create', $id) }}">
                            <i class="fas fa-plus text-primary"></i> Add New Table
                        </a>
                    </li>

                    @foreach(\App\Project::where('id', $id)->first()->tables as $table)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tables.edit', [$id, $table->id]) }}">
                                <i class="fas fa-table text-primary"></i> {!! $table->name !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{--<li class="nav-item">--}}
            {{--<a class="nav-link" href="{{ route('tables.index') }}">--}}
            {{--<i class="fas fa-file text-primary"></i> Tables--}}
            {{--</a>--}}
            {{--</li>--}}

            {{--<li class="nav-item">--}}
            {{--<a class="nav-link" href="{{ route('menus.index') }}">--}}
            {{--<i class="fas fa-file text-primary"></i> Menus--}}
            {{--</a>--}}
            {{--</li>--}}

        </div>
    </div>
</nav>
