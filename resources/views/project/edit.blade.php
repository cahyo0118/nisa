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

            <div class="col-xl-12">

                @include('partials.alert')

                @include('project.modals.global-variables-modal')

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        {!! Form::model($item, ['route' => ['projects.update', $item->id], 'method' => 'patch']) !!}

                            @include('project.fields')

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
        function onAddNewVariable(projectId = 0, variableId = 0) {
            let form = $(`#addNewVariableForm`);
            let value = form.serializeJSON();

            $.ajax({
                url: `/ajax/projects/${projectId}/variables`,
                type: 'POST',
                data: JSON.stringify(value),
                success: function (data) {
                    swal("Success!", data.message, "success");

                    $(`#addNewVariable`).modal('hide');
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
    </script>
@endsection
