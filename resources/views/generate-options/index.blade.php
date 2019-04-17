@extends('admin')

@section('content')
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Generate Options</a>
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

                    @include('partials.modal-delete')

                    @include('partials.alert')

                    <div class="card shadow">

                        <div class="card-header border-0">
                            <h3 class="mb-0">Generate Options</h3>

                            <div class="modal fade" id="addParentMenuModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Options</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{--{!! Form::open(['route' => 'menus.store', "id" => "addParentMenuForm", "onsubmit" => "onSubmitParentMenu($id);return false;"]) !!}--}}

                                            {{--{{ csrf_field() }}--}}

                                            <div class="row">

                                                <input type="hidden">

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Name</label>
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            {!! Form::text('name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Display Name</label>
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            {!! Form::text('display_name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--{!! Form::hidden('parent_menu_id', null) !!}--}}

                                                {{--{!! Form::hidden('project_id', $id) !!}--}}

                                            </div>

                                            <button class="btn btn-icon btn-3 btn-primary" type="submit"
                                                    [disabled]="!voteForm.valid">
                                                <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                                                <span class="btn-inner--text">Send</span>

                                            </button>


{{--                                            {!! Form::close() !!}--}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addParentMenuModal">
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
                                    <tr id="genarate_option{!! $item->id !!}">
                                        <th scope="row">
                                            {{ $item->name }}
                                        </th>
                                        <td>
                                            {{ $item->display_name }}
                                        </td>

                                        <td class="text-right">

                                            <button
                                                type="button"
                                                data-toggle="modal"
                                                data-target="#generateOptionsModal"
                                                class="btn btn-icon btn-dark btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-cogs"></i></span>
                                                <span class="btn-inner--text">Generate</span>
                                            </button>

                                            @include('genarate-options.modals.generate-options-modal')

                                            <button onclick="onGenerateLaravel5({{ $item->id }})" type="button"
                                                    class="btn btn-icon btn-dark btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                                <span class="btn-inner--text">Generate Laravel 5</span>
                                            </button>

                                            <a href="{{ route('generate_options.tables', $item->id) }}"
                                               class="btn btn-icon btn-primary btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-table"></i></span>
                                                <span class="btn-inner--text">Tables</span>
                                            </a>

                                            <a href="{{ route('generate_options.menus', $item->id) }}"
                                               class="btn btn-icon btn-secondary btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-ellipsis-v"></i></span>
                                                <span class="btn-inner--text">Menus</span>
                                            </a>

                                            <a href="{{ route('generate_options.show', $item->id) }}"
                                               class="btn btn-icon btn-info btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-eye"></i></span>
                                                <span class="btn-inner--text">View</span>
                                            </a>
                                            <a href="{{ route('generate_options.edit', $item->id) }}"
                                               class="btn btn-icon btn-default btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                                <span class="btn-inner--text">Edit</span>
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-icon btn-danger btn-sm"
                                                onclick="deleteProject({!! $item->id !!})">
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
        function onGenerateLaravel5(generateOptionId = 0) {

            swal({
                title: "Generate Now?",
                text: "some files might be rewritten!",
                icon: "warning",
                buttons: true,
                dangerMode: false,
            }).then((execute) => {

                if (execute) {

                    $.ajax({
                        url: `/ajax/generate_options/${projectId}/laravel5/generate`,
                        type: 'POST',
                        data: null,
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

        function deleteProject(projectId = 0) {

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {

                if (willDelete) {
                    $.ajax({
                        url: `/ajax/generate_options/${projectId}/delete`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#project${projectId}`).remove();

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
