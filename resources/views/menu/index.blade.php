@extends('admin')

@section('content')
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Menus</a>
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
            {{--<div class="row">--}}
            {{--<div class="col">--}}
            {{--{!! Form::open(['route' => 'menus.index', 'method' => 'GET', 'class' => 'navbar-search navbar-search-dark form-inline d-md-flex ml-lg-auto justify-content-center']) !!}--}}
            {{--<div class="form-group">--}}
            {{--<div class="input-group input-group-alternative">--}}
            {{--<div class="input-group-prepend">--}}
            {{--<span class="input-group-text"><i class="fas fa-search"></i></span>--}}
            {{--</div>--}}
            {{--<input name="keyword" class="form-control" placeholder="Search" type="text">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--{!! Form::close() !!}--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--<br>--}}

            <div class="row" style="margin-top: 30px;">
                <div class="col">

                    <div class="card shadow container">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Menus List</h3>

                            <div class="modal fade" id="addParentMenuModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Menu</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {!! Form::open(['route' => 'menus.store', "id" => "addParentMenuForm", "onsubmit" => "onSubmitParentMenu($project_id);return false;"]) !!}

                                            {{ csrf_field() }}

                                            <div class="row">

                                                <input type="hidden">

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Name</label>
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-pen"></i></span>
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
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-pen"></i></span>
                                                            </div>
                                                            {!! Form::text('display_name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                {!! Form::hidden('parent_menu_id', null) !!}

                                                {!! Form::hidden('project_id', $project_id) !!}

                                            </div>

                                            <button class="btn btn-icon btn-3 btn-primary" type="submit"
                                                    [disabled]="!voteForm.valid">
                                                <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                                                <span class="btn-inner--text">Send</span>

                                            </button>


                                            {!! Form::close() !!}
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

                        <div id="menus_list" class="accordion" style="margin-bottom: 30px;"></div>


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
            getAllMenus({{ $project_id }});

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
        function addCriteria() {

        }

        function getAllMenus(projectId, parentMenuId = 0) {
            $.ajax({
                url: `/projects/${projectId}/menus`,
                type: 'GET',
                success: function (data) {

                    $('#menus_list').append(data.view);

                    for (var i = 0; i < data.menu_ids.length; i++) {
                        getAllSubMenus(projectId, data.menu_ids[i]);
                    }

                },
            });
        }

        function getAllSubMenus(projectId, parentMenuId = 0) {

            if (parentMenuId !== 0) {

                $.ajax({
                    url: `/projects/${projectId}/menus/${parentMenuId}/sub-menus`,
                    type: 'GET',
                    success: function (data) {

                        $(`#menus_list${parentMenuId}`).append(data.view);

                        if (data.success) {
                            for (var i = 0; i < data.menu_ids.length; i++) {
                                getAllSubMenus(projectId, data.menu_ids[i]);
                            }
                        }

                    },
                });

            }
        }

        function onSubmitParentMenu(projectId, menuId = 0) {

            let form = $(`#addParentMenuForm`);
            let value = form.serializeJSON();

            console.log(value);

            $.ajax({
                url: `/ajax/projects/${projectId}/menus/${menuId}`,
                type: 'POST',
                data: JSON.stringify(value),
                success: function (data) {
                    $(`#menus_list`).append(data.view);

                    swal("Success!", data.message, "success");

                    $(`#addParentMenuModal`).modal('hide');
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

        function onSubmitMenu(projectId, menuId = 0) {

            let form = $(`#addMenuForm${menuId}`);
            let value = form.serializeJSON();

            console.log(value);

            $.ajax({
                url: `/ajax/projects/${projectId}/menus/${menuId}`,
                type: 'POST',
                data: JSON.stringify(value),
                success: function (data) {
                    $(`#menus_list${menuId}`).append(data.view);

                    swal("Success!", data.message, "success");

                    $(`#addMenuModal${menuId}`).modal('hide');
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

        function onUpdateMenu(projectId, menuId = 0) {

            let form = $(`#updateMenuForm${menuId}`);
            let value = form.serializeJSON();

            console.log(value);

            $.ajax({
                url: `/ajax/projects/${projectId}/menus/${menuId}`,
                type: 'PUT',
                data: JSON.stringify(value),
                success: function (data) {

                    $(`#editMenuModal${menuId}`).modal("hide");

                    $(`#editMenuModal${menuId}`).on('hidden.bs.modal', function () {
                        $(`#menuItemCard${menuId}`).replaceWith(data.view);
                    });
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

        function onUpdateDatasetMenu(menuId = 0) {

            // let value = $(`#tableIdInputMenu${menuId}`).val();
            let value = $(`#customizeMenuForm${menuId}`).serializeJSON();

            $.ajax({
                url: `/ajax/menus/${menuId}/datasets/${value.table_id ? value.table_id : 0}/update`,
                type: 'PUT',
                data: JSON.stringify(value),
                success: function (data) {

                    $(`#customizeMenuModal${menuId}`).modal("hide");

                    // this will be some ui/ux problem
                    $(`#customizeMenuModal${menuId}`).on('hidden.bs.modal', function () {
                        $(`#menuItemCard${menuId}`).replaceWith(data.view);
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

        function deleteMenu(menuId = 0) {

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {

                if (willDelete) {
                    $.ajax({
                        url: `/ajax/menus/${menuId}/delete`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#menuItemCard${menuId}`).remove();

                            $(`#addMenuModal${menuId}`).remove();

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
