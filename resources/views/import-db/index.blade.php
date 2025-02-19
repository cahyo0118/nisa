@extends('admin')

@section('content')
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Generate
                    Options</a>
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
            <div class="row">
                <div class="col">
                    {!! Form::open(['route' => 'generate_options.index', 'method' => 'GET', 'class' => 'navbar-search navbar-search-dark form-inline d-md-flex ml-lg-auto justify-content-center']) !!}
                    <div class="form-group">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input name="keyword" class="form-control" placeholder="Search" type="text">
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <br>
            <!-- Table -->
            {!! Form::open(['route' => 'projects.store', "id" => "importDBForm", "onsubmit" => "importDB();return false;"]) !!}

            {{ csrf_field() }}

            <div class="row">
                <div class="col-12">

                    @include('partials.alert')

                    <div class="card shadow">

                        <div class="card-header border-0">
                            <h3 class="mb-0">Import DB</h3>

                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Database</label>
                                        <select id="databaseNameInput" name="db_name"
                                                class="form-control form-control-alternative"
                                                onchange="getTables()">
                                            <option value="">--</option>
                                            @foreach($databases as $database)
                                                <option
                                                    value="{{ $database->Database }}">{{ $database->Database }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-2 text-center" style="padding-top: 40px;">
                                    <span class="fas fa-arrow-right fa-2x"></span>
                                </div>

                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Project</label>
                                        <select id="databaseNameInput"
                                                name="project_id"
                                                class="form-control form-control-alternative">
                                            <option value="">--</option>
                                            @foreach($projects as $project)
                                                <option
                                                    value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button
                                        type="button"
                                        class="btn btn-icon btn-primary float-right"
                                        onclick="importDB()">
                                        <span class="btn-inner--icon"><i class="fas fa-file-import"></i></span>
                                        <span class="btn-inner--text">Import</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 sticky">

                    <br>

                    <div class="card shadow">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="w-100 text-sm">
                                        Select All
                                        <input id="selectAllInput" type="checkbox" name="columns[]"
                                               class="float-right"
                                               onchange="selectAll()">
                                    </label>
                                </div>

                                <div class="col-9">
                                    <button
                                        type="button"
                                        class="btn btn-icon btn-primary float-right"
                                        onclick="importDB()">
                                        <span class="btn-inner--icon"><i class="fas fa-file-import"></i></span>
                                        <span class="btn-inner--text">Import</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="tables"></div>
            </div>

            {!! Form::close() !!}


        <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>
@endsection

@section('script')
    <script>
        function getTables(dbName = '') {

            $(`#tables`).replaceWith('<div id="tables" class="w-100 text-center"><br><br><span class="fas fa-spinner fa-spin fa-3x"></span></div>');

            dbName = $('#databaseNameInput').val();

            $.ajax({
                url: `/ajax/import-db/databases/${dbName}/tables`,
                type: 'GET',
                success: function (data) {
                    $(`#tables`).replaceWith(data.view);
                },
                error: function (error) {
                    var data = error.responseJSON;

                    swal("Failed!", data.message, "error");
                }
            });
        }

        function importDB() {

            swal({
                title: "Import Now ?",
                text: "if tables or field already exist, incoming change will be ignored !",
                icon: "warning",
                buttons: true,
                dangerMode: false,
            }).then((execute) => {

                if (execute) {

                    let form = $(`#importDBForm`);
                    let value = form.serializeJSON();
                    let tablesData = [];

                    $(`:checkbox[name='tables[]']:checked`).each((index, value) => {
                        tablesData.push({
                            'table_name': $(value).val(),
                            'fields': []
                        });
                    });


                    for (let i = 0; i < tablesData.length; i++) {
                        let fields = [];
                        $(`:checkbox[name='fields[${tablesData[i].table_name}]']:checked`).each((index, value) => {
                            fields.push($(value).val());
                        });
                        tablesData[i].fields = fields;
                    }

                    $.ajax({
                        url: `/ajax/projects/${value.project_id}/import-db/databases/${value.db_name}/import`,
                        type: 'POST',
                        data: JSON.stringify({
                            'db_name': value.db_name,
                            'project_id': value.project_id,
                            'tables': tablesData
                        }),
                        success: function (data) {
                            swal("Success!", data.message, "success");
                        },
                        error: function (error) {
                            var data = error.responseJSON;

                            swal("Failed!", data.message, "error");
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                }
            });

        }

        function selectAll() {
            $("input[type=checkbox]").prop('checked', $(`#selectAllInput`).prop('checked'));
        }

        function selectFieldAll(tableName = '') {
            $(`input[name='fields[${tableName}]']`).prop('checked', $(`#${tableName}Input`).prop('checked'));
        }
    </script>
@endsection
