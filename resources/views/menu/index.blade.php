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
            <div class="row">
                <div class="col">
                    {!! Form::open(['route' => 'menus.index', 'method' => 'GET', 'class' => 'navbar-search navbar-search-dark form-inline d-md-flex ml-lg-auto justify-content-center']) !!}
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
            {{--<div class="row">--}}
            {{--<div class="col">--}}

            {{--@include('partials.modal-delete')--}}

            {{--@include('partials.alert')--}}

            {{--<div class="card shadow">--}}
            {{--<div class="card-header border-0">--}}
            {{--<h3 class="mb-0">Menus List</h3>--}}
            {{--<a href="{{ route('menus.create') }}" class="btn btn-icon btn-primary">--}}
            {{--<span class="btn-inner--icon"><i class="fas fa-plus"></i></span>--}}
            {{--<span class="btn-inner--text">Create</span>--}}
            {{--</a>--}}
            {{--</div>--}}

            {{--<div class="table-responsive">--}}
            {{--<table class="table align-items-center table-flush" data-toggle="dataTable"--}}
            {{--data-form="deleteForm">--}}
            {{--<thead class="thead-light">--}}
            {{--<tr>--}}
            {{--<th scope="col">Name</th>--}}
            {{--<th scope="col">display name</th>--}}
            {{--<th scope="col"></th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--@foreach($items as $item)--}}
            {{--<tr>--}}
            {{--<th scope="row">--}}
            {{--{{ $item->name }}--}}
            {{--</th>--}}
            {{--<td>--}}
            {{--{{ $item->display_name }}--}}
            {{--</td>--}}

            {{--<td class="text-right">--}}
            {{--{!! Form::model($item, ['method' => 'delete', 'route' => ['menus.destroy', $item->id], 'id' => 'form-delete', 'class' =>'form-inline justify-content-end']) !!}--}}

            {{--<a href="{{ route('menus.subs', $item->id) }}"--}}
            {{--class="btn btn-icon btn-secondary btn-sm">--}}
            {{--<span class="btn-inner--icon"><i class="fas fa-list"></i></span>--}}
            {{--<span class="btn-inner--text">Sub Menus</span>--}}
            {{--</a>--}}

            {{--<a href="{{ route('menus.show', $item->id) }}"--}}
            {{--class="btn btn-icon btn-info btn-sm">--}}
            {{--<span class="btn-inner--icon"><i class="fas fa-eye"></i></span>--}}
            {{--<span class="btn-inner--text">View</span>--}}
            {{--</a>--}}
            {{--<a href="{{ route('menus.edit', $item->id) }}"--}}
            {{--class="btn btn-icon btn-default btn-sm">--}}
            {{--<span class="btn-inner--icon"><i class="fas fa-edit"></i></span>--}}
            {{--<span class="btn-inner--text">Edit</span>--}}
            {{--</a>--}}

            {{--{!! Form::hidden('id', $item->id) !!}--}}
            {{--{!! Form::submit('delete', ['id' => 'confirm-delete', 'class' => 'btn btn-danger btn-sm text-white confirm-delete', 'name' => 'delete_modal']) !!}--}}
            {{--{!! Form::close() !!}--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--@endforeach--}}
            {{--</tbody>--}}
            {{--</table>--}}
            {{--</div>--}}
            {{--<div class="card-footer py-4">--}}
            {{--{!! $items->appends(['keyword' => Request::get('keyword')])->links() !!}--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="row" style="margin-top: 30px;">
                <div class="col">

                    <div class="card shadow container">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Menus List</h3>

                            <a href="{{ route('menus.create') }}" class="btn btn-icon btn-primary">
                                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                <span class="btn-inner--text">Create</span>
                            </a>

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
            getAllMenus(1);

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
                        swal("Success!", data.message, "success");

                        $(`#menuItemCard${menuId}`).replaceWith(data.view);
                    });
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
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }

            });


        }
    </script>
@endsection
