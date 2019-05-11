@extends('admin')

@section('content')
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Samples</a>
                <!-- User -->
                @include('partials.user-menu')
            </div>
        </nav>

        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">

            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">

            {!! Form::model($item, ['route' => ['tables.update', $item->project_id, $item->id], 'method' => 'patch', 'id' => 'tableForm']) !!}

            {{ csrf_field() }}

            <div class="col-xl-12">

                @include('partials.alert')

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        @include('table.fields')

                    </div>

                </div>

                <br>

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        @include('table.fields_forms')

                    </div>

                </div>

            </div>

            {!! Form::close() !!}

        <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            getAllFields({{ $item->id }});
            getAllManyRelations({{ $item->id }});
        });
    </script>
@endsection
