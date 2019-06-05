@extends('admin')

@section('content')
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Tables</a>
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

            {!! Form::open(['route' => ['tables.store', $project_id], 'id' => 'tableForm']) !!}

            {{ csrf_field() }}

            <div class="col-xl-12">

                @include('partials.alert')

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        @include('table.fields')

                    </div>

                </div>
            </div>

            <div class="col-xl-12 sticky">
                <br>
                <div class="card bg-secondary shadow ">

                    <div class="card-body">

                        <button class="btn btn-icon btn-3 btn-primary btn-sm" type="button"
                                onclick="addNewField({{ $project_id }})">
                            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>

                            <span class="btn-inner--text">Add new field</span>

                        </button>

                        @if(Request::route()->getName() == 'tables.edit')

                            <button class="btn btn-icon btn-3 btn-primary btn-sm" type="button"
                                    onclick="addNewManyToManyRelation({!! !empty($item->project_id) ? $item->project_id : 0 !!})">
                                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>

                                <span class="btn-inner--text">Add many to many relation</span>
                            </button>

                            <button class="btn btn-icon btn-3 btn-primary btn-sm" type="button"
                                    onclick="addHasManyRelation()">
                                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>

                                <span class="btn-inner--text">Add has many relation</span>
                            </button>

                            <button class="btn btn-icon btn-3 btn-info btn-sm float-right" type="submit">
                                <span class="btn-inner--icon"><i class="fas fa-sync"></i></span>

                                <span class="btn-inner--text">Sync changes</span>
                            </button>

                        @elseif(Request::route()->getName() == 'tables.create')
                            <button class="btn btn-icon btn-3 btn-primary btn-sm float-right" type="submit">
                                <span class="btn-inner--icon"><i class="fas fa-paper-plane"></i></span>

                                <span class="btn-inner--text">Send</span>
                            </button>
                        @endif


                    </div>

                </div>

                <br>
            </div>

            <div class="col-xl-12">
                <div class="card bg-secondary shadow ">

                    <div class="card-body">

                        @include('table.fields_forms')

                    </div>

                </div>

                <br>

            </div>

        </div>

    {!! Form::close() !!}

    <!-- Footer -->
        @include('partials.footer')
    </div>
@endsection

