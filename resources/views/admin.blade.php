<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Argon Dashboard - Free Dashboard for Bootstrap 4</title>
    <!-- Favicon -->
    <link href="{{ asset('/assets/img/brand/favicon.png') }}" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('/assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('/assets/css/argon.css?v=1.0.0') }}" rel="stylesheet">

    <link type="text/css" href="{{ asset('/css/bootstrap-timepicker.css') }}" rel="stylesheet">
</head>

<body>
<!-- Sidenav -->
@include('partials.side-menu')
<!-- Main content -->

@yield('content')

<!-- Argon Scripts -->
<!-- Core -->
<script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

{{-- Datepicker --}}
<script src="{{ asset('/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Optional JS -->
<script src="{{ asset('/assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<!-- Argon JS -->
<script src="{{ asset('/assets/js/argon.js?v=1.0.0') }}"></script>

<script src="{{ asset('/js/bootstrap-timepicker.js') }}"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $('.timepicker').timepicker();
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    function saveFieldsChanges(tableId) {

        // var data = $('#tableForm');

        var formData = new FormData();

        $("input[name^='name']").each(function (index) {
            // var oneValue = $(this).val();
            // var name = $(this).attr('name');

            console.log($(this).attr('name'));
            console.log($(this).val());
            formData.append($(this).attr('name'), $(this).val());
            // console.log(index);
        });

        // formData.append('name', $("input[name='name[]']"));


        console.log(formData.getAll('name'));

        $.ajax({
            url: `/tables/${tableId}/fields/sync`,
            type: 'PUT',
            // headers: {
            //     'Content-Type' : 'application/form-data'
            // },
            data: formData,
            success: function (data) {
                $('#table_fields').replaceWith(`<div id="table_fields" class="row">${data.view}</div>`);
                swal("Sync Success!", "Fields successfully sync with cloud data!", "success");
            },
            error: function (error) {
                swal("Sync Failed!", "Fields failed to be sync with cloud data!", "error");
            },
            // dataType: 'json',
            cache: false,
            contentType: false,
            processData: false
        });

    }

    function getAllFields(tableId) {
        // Get All Fields on the table
        $.ajax({
            url: `/tables/${tableId}/fields`,
            type: 'GET',
            success: function (data) {
                $('#table_fields').append(data.view);
            },
        });
    }

    function addNewField() {

        // Add new field into table

        $.ajax({
            url: '/tables/add-new-field',
            type: 'POST',
            data: null,
            success: function (data) {
                console.log(data.view);
                $('#table_fields').append(data.view);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function deleteField(random, id = 0) {

        console.log(`/fields/${id}/`);
        // Delete field into table

        if (id !== 0) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    $.ajax({
                        url: `/fields/${id}`,
                        type: 'DELETE',
                        success: function (data) {
                            $('#card' + random).remove();
                            // console.log(data.view);
                            // $('#table_fields').append(data.view);
                        }
                    });
                }
            });

        }


    }
</script>

@yield('script')

</body>

</html>
