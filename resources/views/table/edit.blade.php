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
                                    onclick="addHasManyRelation({!! !empty($item->project_id) ? $item->project_id : 0 !!})">
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
                <div class="card bg-secondary shadow">

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

@section('script')
    <script>
        $(function () {
            getAllFields({{ $item->id }}, '{{ $project_id }}');
            getAllManyRelations({{ $item->id }}, '{{ $project_id }}');
        });
    </script>

    <script>

        function moveFieldUp(fieldId) {
            $.ajax({
                url: `/ajax/fields/${fieldId}/move-up`,
                type: 'PUT',
                data: null,
                success: function (data) {
                    refreshFields({{ $item->id }}, '{{ $project_id }}');
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

        function moveFieldDown(fieldId) {
            $.ajax({
                url: `/ajax/fields/${fieldId}/move-down`,
                type: 'PUT',
                data: null,
                success: function (data) {
                    refreshFields({{ $item->id }}, '{{ $project_id }}');
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

        function useStaticDataset(random, projectId = 0, id = 0) {
            $.ajax({
                url: `/projects/${projectId}/fields/${id}/add-new-dataset/${random}`,
                type: 'POST',
                data: null,
                success: function (data) {
                    $(`#relationDiv${random}`).replaceWith(`<div id="relationDiv${random}" class="row">${data.view}</div>`);
                    // get fields
                    getAllFieldsSelectInput(random);
                    getAllDisplayFieldsSelectInput(random);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        function onStoreDataset(random, projectId = 0, fieldId = 0) {
            let form = null;
            let value = null;

            form = $(`#addStaticDatasetForm${fieldId}`);
            value = form.serializeJSON();

            $.ajax({
                url: `/ajax/projects/${projectId}/fields/${fieldId}/static-datasets/store`,
                type: 'POST',
                data: JSON.stringify(value),
                success: function (data) {

                    $(`#addStaticDatasetModal${fieldId}`).modal('hide');

                    $(`#static_datasets${fieldId}`).append(data.view);

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

        function onUpdateDataset(id) {
            let form = null;
            let value = null;

            form = $(`#updateStaticDatasetForm${id}`);
            value = form.serializeJSON();

            $.ajax({
                url: `/ajax/static-datasets/${id}/update`,
                type: 'PUT',
                data: JSON.stringify(value),
                success: function (data) {
                    $(`#editStaticDatasetModal${id}`).modal('hide');

                    $(`#editStaticDatasetModal${id}`).on('hidden.bs.modal', function () {
                        $(`#static_dataset_${id}`).replaceWith(data.view);
                    });

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

        function onDeleteDataset(datasetId) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `/ajax/static-datasets/${datasetId}/delete`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#static_dataset_${datasetId}`).remove();
                        }
                    });
                }
            });
        }
    </script>
@endsection
