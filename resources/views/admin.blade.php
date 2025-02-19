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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
{{--    <link href="{{ asset('/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">--}}
<!-- Argon CSS -->
    <link type="text/css" href="{{ asset('/assets/css/argon.css?v=1.0.0') }}" rel="stylesheet">

    <link type="text/css" href="{{ asset('/css/bootstrap-timepicker.css') }}" rel="stylesheet">

    <style>
        select[readonly] {
            pointer-events: none;
        }

        .modal {
            overflow-y: auto;
        }

        .sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            padding-top: 0px;
            font-size: 20px;
            z-index: 999 !important;
        }

        /*Margins*/
        .margin-h-5 {
            margin-right: 5px;
            margin-left: 5px;
        }

        .margin-v-5 {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /*Modals*/
        .modal-lg {
            max-width: 80% !important;
        }
    </style>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>

<script>
    $('.timepicker').timepicker();
</script>

<script type="text/javascript">
    // Configure ajax for laravel 5 (CSRF TOKEN)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    $(function () {
        // Remove selectable ability in readonly select input
        $("select[readonly]").css("pointer-events", "none");
    });
</script>
<script>

    function onInputTypeChange(random, value, projectId = 0) {

        if (value === "select") {
            // change field type to integer(default value for relation)
            $(`#fieldType${random}`).val("integer");
            $(`#fieldLength${random}`).val(11);

            // createRelation(random, projectId);

        } else {
            // deleteRelation(random);
        }

    }

    function saveFieldsChanges(tableId, projectId = 0) {

        var formData = new FormData();

        // ^ = get value from name array with custom index
        $("input[name^='name']").each(function (index) {
            console.log($(this).attr('name'));
            console.log($(this).val());
            formData.append($(this).attr('name'), $(this).val());
        });

        $.ajax({
            url: `/projects/${projectId}/tables/${tableId}/fields/sync`,
            type: 'PUT',
            data: formData,
            success: function (data) {
                $('#table_fields').replaceWith(`<div id="table_fields" class="row">${data.view}</div>`);
                swal("Sync Success!", "Fields successfully sync with cloud data!", "success");
            },
            error: function (error) {
                swal("Sync Failed!", "Fields failed to be sync with cloud data!", "error");
            },
            cache: false,
            contentType: false,
            processData: false
        });

    }

    function createRelation(random, projectId = 0, id = 0) {

        // Add new relation into field

        $.ajax({
            url: `/projects/${projectId}/fields/${id}/add-new-relation/${random}`,
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

    function addNewField(projectId = 0) {

        // Add new field into table

        $.ajax({
            url: `/projects/${projectId}/tables/add-new-field`,
            type: 'POST',
            data: null,
            success: function (data) {
                $('#table_fields').append(data.view);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    /*Relation Section*/

    function addHasManyRelation(projectId) {

        // Add new many to many relation into table

        console.log("SOMETHING", projectId);
        $.ajax({
            url: `/projects/${projectId}/tables/add-new-has-many-relation`,
            type: 'POST',
            data: null,
            success: function (data) {
                $('#table_relations').append(data.view);
                getAllManyFieldsSelectInput(data.random);
                getAllManyDisplayFieldsSelectInput(data.random);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function addNewManyToManyRelation(projectId = 0) {

        // Add new many to many relation into table

        $.ajax({
            url: `/projects/${projectId}/tables/add-new-many-to-many-relation`,
            type: 'POST',
            data: null,
            success: function (data) {
                $('#table_relations').append(data.view);
                getAllFieldsSelectInput(data.random);
                getAllManyLocalFieldsSelectInput(data.random);
                getAllDisplayFieldsSelectInput(data.random);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function deleteManyToManyRelation(random, id = 0) {

        // Delete relation table
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                if (id) {
                    $.ajax({
                        url: `/fields/${id}/relation`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#relationManyToMany${random}`).remove();
                        }
                    });
                } else {
                    $(`#relationManyToMany${random}`).remove();
                }
            }
        });

    }

    function deleteManyRelation(random, id = 0) {

        // Delete relation table
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                if (id) {
                    $.ajax({
                        url: `/relation/many/${id}`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#relationManyToMany${random}`).remove();
                        }
                    });
                } else {
                    $(`#relationManyToMany${random}`).remove();
                }
            }
        });

    }

    function deleteRelation(random, id = 0) {

        // Delete relation table

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
                        url: `/fields/${id}/relation`,
                        type: 'DELETE',
                        success: function (data) {
                            $(`#relationDiv${random}`).replaceWith(`<div id="relationDiv${random}" class="row"></div>`);

                        }
                    });
                }
            });

        } else {
            $(`#relationDiv${random}`).replaceWith(`<div id="relationDiv${random}" class="row"></div>`);
        }

    }

    /* END Relation Section*/

    function deleteField(random, id = 0) {

        // Delete field into table
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                if (id) {

                    // Existing field
                    $.ajax({
                        url: `/fields/${id}`,
                        type: 'DELETE',
                        success: function (data) {
                            $('#card' + random).remove();
                        }
                    });

                } else {

                    // Shadow field
                    $('#card' + random).remove();

                }
            }
        });

    }

    /* Select Data */
    function getAllFields(tableId, projectId = 0) {
        // Get All Fields on the table
        $.ajax({
            url: `/projects/${projectId}/tables/${tableId}/fields`,
            type: 'GET',
            success: function (data) {
                $('#table_fields').append(data.view);

                for (let i = 0; i < data.random.length; i++) {

                    console.log('DASET', data.data[i].dataset_type);
                    if (data.data[i].dataset_type === 'dynamic') {
                        getRelationByFieldId(data.random[i], data.field_ids[i], projectId);
                    } else if (data.data[i].dataset_type === 'static') {
                        getDatasetByFieldId(data.random[i], data.field_ids[i], projectId);
                    }
                }

            },
        });
    }

    function refreshFields(tableId, projectId = 0) {
        // Get All Fields on the table
        $.ajax({
            url: `/projects/${projectId}/tables/${tableId}/fields`,
            type: 'GET',
            success: function (data) {
                $('#table_fields').replaceWith(`<div id="table_fields" class="row">${data.view}</div>`);

                for (let i = 0; i < data.random.length; i++) {
                    if (data.data[i].dataset_type === 'dynamic') {
                        getRelationByFieldId(data.random[i], data.field_ids[i], projectId);
                    } else if (data.data[i].dataset_type === 'static') {
                        getDatasetByFieldId(data.random[i], data.field_ids[i], projectId);
                    }
                }

            },
        });
    }

    function getAllManyRelations(tableId, projectId = 0) {

        // Get All Fields on the table
        $.ajax({
            url: `/projects/${projectId}/tables/${tableId}/relations/many`,
            type: 'GET',
            success: function (data) {
                $('#table_relations').append(data.view);

                for (let i = 0; i < data.random.length; i++) {
                    getAllManyFieldsSelectInput(data.random[i], data.field_ids[i]);
                    getAllManyLocalFieldsSelectInput(data.random[i], data.field_ids[i]);
                    getAllManyDisplayFieldsSelectInput(data.random[i], data.relation_tables[i], data.field_display_ids[i]);
                }

            },
        });
    }

    function getRelationByFieldId(random, fieldId, projectId = 0) {
        // Get All Fields on the table
        $.ajax({
            url: `/projects/${projectId}/fields/${fieldId}/relation/${random}`,
            type: 'GET',
            success: function (data) {
                if (typeof data !== "undefined") {
                    $(`#relationDiv${random}`).replaceWith(`<div id="relationDiv${random}" class="row">${data.view}</div>`);
                    getAllFieldsSelectInput(random, fieldId);
                    getAllDisplayFieldsSelectInput(random, fieldId);
                }
            },
        });
    }

    function getDatasetByFieldId(random, fieldId, projectId = 0) {
        // Get All Fields on the table
        $.ajax({
            url: `/projects/${projectId}/fields/${fieldId}/dataset/${random}`,
            type: 'GET',
            success: function (data) {
                console.log('getDatasetByFieldId', data);
                $(`#relationDiv${random}`).replaceWith(`<div id="relationDiv${random}" class="row">${data.view}</div>`);

                // getAllFieldsSelectInput(random, fieldId);
                // getAllDisplayFieldsSelectInput(random, fieldId);
            },
        });
    }

    function getAllFieldsSelectInput(random, fieldId = 0) {

        var tableId = $(`#relationTable${random}`).val();

        if (fieldId !== 0) {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/fields/${fieldId}`,
                type: 'GET',
                success: function (data) {
                    $(`#relationForeign${random}`).replaceWith(data.view);
                },
            });

        } else {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/fields`,
                type: 'GET',
                success: function (data) {
                    $(`#relationForeign${random}`).replaceWith(data.view);
                },
            });

        }
    }

    function getAllLocalFieldsSelectInput(random, fieldId = 0) {

        var tableId = $(`#relationTable${random}`).val();

        if (fieldId !== 0) {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/fields/${fieldId}`,
                type: 'GET',
                success: function (data) {
                    $(`#relationLocal${random}`).replaceWith(data.view);
                },
            });

        } else {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/fields`,
                type: 'GET',
                success: function (data) {
                    $(`#relationLocal${random}`).replaceWith(data.view);
                },
            });

        }
    }

    function getAllDisplayFieldsSelectInput(random, fieldId = 0) {

        var tableId = $(`#relationTable${random}`).val();

        if (fieldId !== 0) {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/displays/${fieldId}`,
                type: 'GET',
                success: function (data) {
                    $(`#relationDisplay${random}`).replaceWith(data.view);
                },
            });

        } else {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/displays`,
                type: 'GET',
                success: function (data) {
                    $(`#relationDisplay${random}`).replaceWith(data.view);
                },
            });

        }
    }

    /* Has Many and Many to Many */
    function getAllManyFieldsSelectInput(random, fieldId = 0) {

        var tableId = '{{ (!empty($item->id)) ? $item->id : 0 }}';

        if (fieldId !== 0) {

            $.ajax({
                url: `/relation/many/${random}/tables/${tableId}/fields/${fieldId}`,
                type: 'GET',
                success: function (data) {
                    $(`#relationForeign${random}`).replaceWith(data.view);
                },
            });

        } else {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/fields`,
                type: 'GET',
                success: function (data) {
                    $(`#relationForeign${random}`).replaceWith(data.view);
                },
            });

        }
    }

    function getAllManyLocalFieldsSelectInput(random, fieldId = 0) {

        // Local key (local field)
        var tableId = '{{ (!empty($item->id)) ? $item->id : 0 }}';

        if (fieldId !== 0) {

            $.ajax({
                url: `/relation/many/${random}/tables/${tableId}/fields/${fieldId}/local`,
                type: 'GET',
                success: function (data) {
                    $(`#relationLocal${random}`).replaceWith(data.view);
                },
            });

        } else {

            $.ajax({
                url: `/relation/many/${random}/tables/${tableId}/fields/local`,
                type: 'GET',
                success: function (data) {
                    $(`#relationLocal${random}`).replaceWith(data.view);
                },
            });

        }
    }

    function getAllManyDisplayFieldsSelectInput(random, tableId = 0, fieldId = 0) {

        console.log(tableId);

        if (fieldId !== 0) {

            $.ajax({
                url: `/relation/many/${random}/tables/${tableId}/displays/${fieldId}`,
                type: 'GET',
                success: function (data) {
                    $(`#relationDisplay${random}`).replaceWith(data.view);
                },
            });

        } else {

            $.ajax({
                url: `/relation/${random}/tables/${tableId}/displays`,
                type: 'GET',
                success: function (data) {
                    $(`#relationDisplay${random}`).replaceWith(data.view);
                },
            });

        }
    }

    //    Customize Menu
    function onOperatorChange(menuId = 0, fieldId = 0, operator = '') {

        console.log('OPERATOR_VALUE', operator);

        // if (operator === "default_or_relation") {
        //     console.log('ACTION', "CALL LIST RELATION");
        $.ajax({
            url: `/views/menus/${menuId}/fields/${fieldId}/inputs/${operator}`,
            type: 'GET',
            success: function (data) {
                $(`#menu${menuId}value${fieldId}`).replaceWith(data.view);
            },
        });
        // } else {
        //     $.ajax({
        //         url: `/views/menus/${menuId}/fields/${fieldId}/inputs/text`,
        //         type: 'GET',
        //         success: function (data) {
        //             $(`#operator${fieldId}`).replaceWith(data.view);
        //         },
        //     });
        // }

    }

</script>

@yield('script')

</body>

</html>
