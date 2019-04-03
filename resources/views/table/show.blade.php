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

            <div class="col-xl-12">

                @include('partials.modal-delete')

                @include('partials.alert')

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        {{ csrf_field() }}

                        <h6 class="heading-small text-muted mb-4">General</h6>

                        <div class="row">

                            <input type="hidden">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <br>
                                    {!! $item->name !!}
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Display Name</label>
                                    <br>
                                    {!! $item->display_name !!}
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Project</label>
                                    <br>
                                    {!! $item->project->display_name !!}
                                </div>
                            </div>

                            <div class="col-lg-12" style="margin-bottom: 30px;">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <h3 class="mb-0">Fields List</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush" data-toggle="dataTable"
                                               data-form="deleteForm">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Length</th>
                                                <th scope="col">Default</th>
                                                {{--<th scope="col"></th>--}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($item->fields as $field)
                                                <tr>
                                                    <th scope="row">
                                                        {{ $field->name }}
                                                    </th>
                                                    <td>
                                                        {{ $field->type }}
                                                    </td>
                                                    <td>
                                                        {{ $field->length }}
                                                    </td>
                                                    <td>
                                                        {{ $field->default }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>


                        </div>

                        {!! Form::model($item, ['method' => 'delete', 'route' => ['tables.destroy', $item->project_id, $item->id], 'id' => 'form-delete', 'class' =>'form-inline justify-content-end']) !!}
                        <a href="{{ route('tables.edit', [$item->project_id, $item->id]) }}" class="btn btn-icon btn-default">
                            <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            <span class="btn-inner--text">Edit</span>
                        </a>

                        {!! Form::hidden('id', $item->id) !!}
                        {!! Form::submit('delete', ['class' => 'btn btn-danger text-white confirm-delete', 'name' => 'delete_modal']) !!}
                        {!! Form::close() !!}


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
            $('.confirm-delete').click(function (e) {
                e.preventDefault();
                var $form = $('#form-delete');
                $('#confirm').modal({backdrop: 'static', keyboard: false})
                    .on('click', '#delete-btn', function () {
                        console.log('DELETE');
                        $form.submit();
                    });
            });
        });
    </script>
@endsection
