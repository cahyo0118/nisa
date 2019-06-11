<!-- Sidenav -->
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <app-sidebar></app-sidebar>
</nav>

<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">

            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"></a>

            <app-account-navbar></app-account-navbar>

        </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">

            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12">
                <h1 class="text-white">Detail {{ ucwords(str_replace('_', ' ', $menu->display_name)) }}</h1>
            </div>

            <!-- Actions -->
            <div class="col-xl-12">

                <a class="btn btn-icon btn-3 btn-secondary" [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}']">
                    <span class="btn-inner--icon"><i class="fas fa-chevron-left"></i></span>
                    <span class="btn-inner--text">Back</span>
                </a>

            </div>

            <br>
            <br>
            <br>

            <div class="col-xl-12">

                <div class="card shadow">

                    <div class="card-body">

                        <h6 class="heading-small text-muted mb-4">General</h6>

                        <div class="pl-lg-4">

                            <div class="row">
@if(!empty($menu->table))
@foreach($menu->table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif ($field->input_type == "select")

@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                        <p>@{{ data?.{!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!} }}</p>
                                    </div>
                                </div>
@endif
@endif

@elseif($field->input_type == "image")

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                    </div>

                                    <img src="@{{ data.value.{!! $field->name !!} }}"
                                         class="mw-100 margin-v-5"
                                         onError="this.src='../../assets/img/defaults/picture-128px.png'">
                                </div>
@else

                                <div class="col-lg-6">
                                    <label class="form-control-label">{{ $field->display_name }}</label>
                                    <p>@{{ data?.{!! $field->name !!} }}</p>
                                </div>
@endif
@endforeach
@endif
                            </div>

                        </div>

                        <hr class="my-4"/>

@if($menu->allow_update)
                        <button type="button"
                                class="btn btn-secondary btn-icon"
                                [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}', data?.id, 'update']">
                            <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            <span class="btn-inner--text">Edit</span>
                        </button>
@endif

                    </div>

                </div>
            </div>

            <!-- End Content -->
            <div class="col-xl-12">
                <app-footer></app-footer>
            </div>

        </div>
    </div>

</div>
