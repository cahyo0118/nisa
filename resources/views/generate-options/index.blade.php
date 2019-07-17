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
            <div class="row">
                <div class="col">

                    {{--                    @include('partials.modal-delete')--}}

                    @include('partials.alert')

                    <div class="card shadow">

                        <div class="card-header border-0">
                            <h3 class="mb-0">Generate Options</h3>

                            @include('generate-options.modals.generate-options-modal')

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addNewOptionModal">
                                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                <span class="btn-inner--text">Create</span>
                            </button>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-items-center table-flush" data-toggle="dataTable"
                                   data-form="deleteForm">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">display name</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)

                                    <tr id="generate_option{!! $item->id !!}">
                                        <th scope="row">
                                            {{ $item->name }}
                                        </th>
                                        <td>
                                            {{ $item->display_name }}
                                        </td>

                                        <td class="row w-100 justify-content-end">

                                            @include('generate-options.modals.edit-generate-options-modal')

                                            @include('generate-options.modals.add-global-variable-modal')

                                            @include('generate-options.modals.add-default-value-modal')

                                            {{--<a href="{{ route('generate_options.show', $item->id) }}"--}}
                                            {{--class="btn btn-icon btn-info btn-sm">--}}
                                            {{--<span class="btn-inner--icon"><i class="fas fa-eye"></i></span>--}}
                                            {{--<span class="btn-inner--text">View</span>--}}
                                            {{--</a>--}}
                                            {{--<a href="{{ route('generate_options.edit', $item->id) }}"--}}
                                            {{--class="btn btn-icon btn-default btn-sm">--}}
                                            {{--<span class="btn-inner--icon"><i class="fas fa-edit"></i></span>--}}
                                            {{--<span class="btn-inner--text">Edit</span>--}}
                                            {{--</a>--}}
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                                    data-target="#editGenerateOptionsModal{{ $item->id }}">
                                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                                <span class="btn-inner--text">Edit</span>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-icon btn-danger btn-sm"
                                                onclick="deleteOption({!! $item->id !!})">
                                                <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                                <span class="btn-inner--text">Delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-4">
                            {!! $items->appends(['keyword' => Request::get('keyword')])->links() !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('table[data-form="deleteForm"]').on('click', '.confirm-delete', function (e) {
                e.preventDefault();
                var $form = $('#form-delete');

                $('#confirm').modal({backdrop: 'static', keyboard: false})
                    .on('click', '#delete-btn', function () {
                        console.log('trying');
                        $form.submit();
                    });
            });
        });

    </script>

    <script>
        function onSubmit(generateOptionId = 0) {

            swal({
                title: "Save Options ?",
                text: "Are you sure want to save this data !",
                icon: "warning",
                buttons: true,
                dangerMode: false,
            }).then((execute) => {

                if (execute) {

                    let form = $(`#addNewOptionForm`);
                    let value = form.serializeJSON();

                    $.ajax({
                        url: `/ajax/generate-options/store`,
                        type: 'POST',
                        data: JSON.stringify(value),
                        success: function (data) {
                            $(`#addNewOptionModal`).modal('hide');
                            swal("Success!", data.message, "success");
                            location.reload();
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

        function deleteOption(id = 0) {

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {

                if (willDelete) {
                    $.ajax({
                        url: `/ajax/generate-options/${id}/delete`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#generate_option${id}`).remove();

                            $(`#addNewOptionModal`).modal('hide');
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

        function onSubmitVariable(projectId = 0, variableId = 0) {

            swal({
                title: "Save Global Variable ?",
                text: "Are you sure want to save this data !",
                icon: "warning",
                buttons: true,
                dangerMode: false,
            }).then((execute) => {

                if (execute) {
                    let form = null;
                    let value = null;

                    if (variableId !== 0) {
                        form = $(`#editGlobalVariable${variableId}`);
                        value = form.serializeJSON();
                    } else {
                        form = $(`#addNewVariableForm`);
                        value = form.serializeJSON();
                    }

                    $.ajax({
                        url: `/ajax/generate-options/${projectId}/variables/${variableId}`,
                        type: 'POST',
                        data: JSON.stringify(value),
                        success: function (data) {
                            $(`#addGlobalVariableModal${projectId}`).modal('hide');
                            if (variableId !== 0) {
                                $(`#global_variable${data.data.id}`).replaceWith(data.view);
                            } else {
                                $(`#project_variables${projectId}`).append(data.view);
                            }
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

        function deleteVariable(id = 0) {

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {

                if (willDelete) {
                    $.ajax({
                        url: `/ajax/variables/${id}/delete`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#global_variable${id}`).remove();
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
    </script>
@endsection
