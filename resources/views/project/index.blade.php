@extends('admin')

@section('content')
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Projects</a>
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
                    {!! Form::open(['route' => 'projects.index', 'method' => 'GET', 'class' => 'navbar-search navbar-search-dark form-inline d-md-flex ml-lg-auto justify-content-center']) !!}
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
                            <h3 class="mb-0">Projects List</h3>
                            <a href="{{ route('projects.create') }}" class="btn btn-icon btn-primary">
                                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                <span class="btn-inner--text">Create</span>
                            </a>
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
                                    <tr>
                                        <th scope="row">
                                            {{ $item->name }}
                                        </th>
                                        <td>
                                            {{ $item->display_name }}
                                        </td>

                                        <td class="text-right">
                                            {!! Form::model($item, ['method' => 'delete', 'route' => ['projects.destroy', $item->id], 'id' => 'form-delete', 'class' =>'form-inline justify-content-end']) !!}

                                            {{--<button--}}
                                            {{--type="button"--}}
                                            {{--data-toggle="modal"--}}
                                            {{--data-target="#generateOptionsModal"--}}
                                            {{--class="btn btn-icon btn-dark btn-sm">--}}
                                            {{--<span class="btn-inner--icon"><i class="fas fa-cogs"></i></span>--}}
                                            {{--<span class="btn-inner--text">Generate</span>--}}
                                            {{--</button>--}}

                                            {{--@include('project.modals.generate-options-modal')--}}

                                            <button onclick="onGenerateLaravel5({{ $item->id }})" type="button"
                                                    class="btn btn-icon btn-dark btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                                <span class="btn-inner--text">Generate Laravel 5</span>
                                            </button>

                                            <a href="{{ route('projects.tables', $item->id) }}"
                                               class="btn btn-icon btn-primary btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-table"></i></span>
                                                <span class="btn-inner--text">Tables</span>
                                            </a>

                                            <a href="{{ route('projects.menus', $item->id) }}"
                                               class="btn btn-icon btn-secondary btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-ellipsis-v"></i></span>
                                                <span class="btn-inner--text">Menus</span>
                                            </a>

                                            <a href="{{ route('projects.show', $item->id) }}"
                                               class="btn btn-icon btn-info btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-eye"></i></span>
                                                <span class="btn-inner--text">View</span>
                                            </a>
                                            <a href="{{ route('projects.edit', $item->id) }}"
                                               class="btn btn-icon btn-default btn-sm">
                                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                                <span class="btn-inner--text">Edit</span>
                                            </a>

                                            {!! Form::hidden('id', $item->id) !!}
                                            {!! Form::submit('delete', ['id' => 'confirm-delete', 'class' => 'btn btn-danger btn-sm text-white confirm-delete', 'name' => 'delete_modal']) !!}
                                            {!! Form::close() !!}
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
        function onGenerateLaravel5(projectId = 0) {

            swal({
                title: "Generate Now?",
                text: "some files might be rewritten!",
                icon: "warning",
                buttons: true,
                dangerMode: false,
            }).then((execute) => {

                if (execute) {

                    $.ajax({
                        url: `/ajax/projects/${projectId}/laravel5/generate`,
                        type: 'POST',
                        data: null,
                        success: function (data) {
                            swal("Success!", data.message, "success");
                        },
                        error: function (error) {
                            console.log(error.data);
                            // swal("Failed !", data.data.message, "success");
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
