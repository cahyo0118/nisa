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
@foreach($menu->table->fields()->orderBy('order')->get() as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif ($field->input_type == "select")

@if($field->dataset_type == "static")
                                <div class="col-lg-6">
                                    <label class="form-control-label">{{ $field->display_name }}</label>
                                    <p>@{{ data?.{!! $field->name !!} }}</p>
                                </div>
@else
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                        <p>@{{ data?.{!! !empty($field->relation->relation_name) ? str_singular($field->relation->relation_name) : str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!} }}</p>
                                    </div>
                                </div>
@endif
@endif
@endif

@elseif($field->input_type == "image")

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                    </div>

                                    <img src="@{{ data?.{!! $field->name !!} }}"
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

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
                            <div class="row">
                                <div class="col-12">

                                    <hr class="my-4"/>

                                    <h6 class="heading-small text-muted mb-4">{{ !empty($relation->relation_name) ? ucwords(str_replace('_', ' ', str_plural($relation->relation_name))) : ucwords(str_replace('_', ' ', str_plural($relation->table->display_name))) }}</h6>

                                    <button class="btn btn-icon btn-3 btn-primary" type="button" data-toggle="modal"
                                            data-target="#select{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Modal"
                                            *ngIf="isAllowed('{!! snake_case(str_plural($menu->name)) !!}_update')">
                                        <span class="btn-inner--icon"><i class="fas fa-hand-pointer"></i></span>
                                        <span class="btn-inner--text">Select {{ !empty($relation->relation_name) ? ucwords(str_replace('_', ' ', str_plural($relation->relation_name))) : ucwords(str_replace('_', ' ', str_plural($relation->table->display_name))) }}</span>
                                    </button>

                                    <div class="modal fade" id="select{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Modal" tabindex="-1" role="dialog"
                                         aria-labelledby="modal-form"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-sm"
                                             role="document">
                                            <div class="modal-content">

                                                <div class="modal-body p-0">


                                                    <div class="card bg-secondary shadow border-0">

                                                        <div class="card-body px-lg-5 py-lg-5">
                                                            <div class="text-center text-muted mb-4">
                                                                Select {{ !empty($relation->relation_name) ? ucwords(str_replace('_', ' ', str_plural($relation->relation_name))) : ucwords(str_replace('_', ' ', str_plural($relation->table->display_name))) }}
                                                            </div>

                                                            <div class="row">

                                                                <div class="col-lg-12">
                                                                    <form [formGroup]="search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchForm"
                                                                          (submit)="onSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}()">
                                                                        <div class="form-group">
                                                                            <label class="form-control-label">Search {{ !empty($relation->relation_name) ? ucwords(str_replace('_', ' ', str_plural($relation->relation_name))) : ucwords(str_replace('_', ' ', str_plural($relation->table->display_name))) }}</label>
                                                                            <input type="text"
                                                                                   class="form-control form-control-alternative"
                                                                                   formControlName="keyword"
                                                                                   placeholder="Search {{ !empty($relation->relation_name) ? ucwords(str_replace('_', ' ', str_plural($relation->relation_name))) : ucwords(str_replace('_', ' ', str_plural($relation->table->display_name))) }}...">
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="card shadow">
                                                                        <div class="table-responsive">
                                                                            <table class="table align-items-center">
                                                                                <thead class="thead-light">
                                                                                <tr>
@if(!empty($relation->table))
@foreach($relation->table->fields()->where('searchable', true)->orderBy('order')->get() as $field_index => $relation_field)
@if(empty($relation_field->relation))
                                                                                    <th [ngClass]="{'text-primary': orderBy == '{!! $relation_field->name !!}', 'pointer': true}"
                                                                                        (click)="onOrderBy('{!! $relation_field->name !!}', orderType == 'asc' ? 'desc' : 'asc')">
                                                                                        {!! $relation_field->display_name !!}
                                                                                        <span *ngIf="orderBy == '{!! $relation_field->name !!}' && orderType == 'asc'"
                                                                                              class="fas fa-chevron-up"></span>
                                                                                        <span *ngIf="orderBy == '{!! $relation_field->name !!}' && orderType == 'desc'"
                                                                                              class="fas fa-chevron-down"></span>
                                                                                    </th>
@else
                                                                                    <th scope="col">{!! $relation_field->display_name !!}</th>
@endif
@endforeach
@endif
                                                                                    <th scope="col"></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr *ngFor="let item of search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data">
@if(!empty($relation->table))
@foreach($relation->table->fields()->where('searchable', true)->orderBy('order')->get() as $field_index => $relation_field)
                                                                                    <td>
@if(!empty($relation_field->relation))
@if($relation_field->relation->relation_type == "belongsto")
                                                                                        @{{ (item?.{!! str_singular($relation_field->relation->table->name) !!}?.{!! $relation_field->relation->foreign_key_display_field->name !!}?.length > 20) ? (item?.{!! str_singular($relation_field->relation->table->name) !!}?.{!! $relation_field->relation->foreign_key_display_field->name !!} | slice:0:20) + '...' : (item?.{!! str_singular($relation_field->relation->table->name) !!}?.{!! $relation_field->relation->foreign_key_display_field->name !!}) }}
@endif
@else
                                                                                        @{{ (item?.{!! $relation_field->name !!}?.length > 20) ? (item?.{!! $relation_field->name !!} | slice:0:20) + '...' : (item?.{!! $relation_field->name !!}) }}
@endif
                                                                                    </td>
@endforeach
@endif
                                                                                    <td>
                                                                                        <button type="button"
                                                                                                (click)="onSelect{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(item)"
                                                                                                class="btn btn-primary btn-sm btn-icon">
                                                                                            <span
                                                                                                class="btn-inner--icon">
                                                                                                <i
                                                                                                    class="fas fa-hand-pointer"></i>
                                                                                            </span>
                                                                                            <span
                                                                                                class="btn-inner--text">
                                                                                                Select
                                                                                            </span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-12" style="margin-top:15px">
                                                                    <nav aria-label="...">
                                                                        <ul class="pagination justify-content-end mb-0">
                                                                            <li class="page-item"
                                                                                [ngClass]="search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage === 1 ? 'disabled' : ''">
                                                                                <a class="page-link"
                                                                                   (click)="getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data()">
                                                                                    <i class="fas fa-angle-double-left"></i>
                                                                                    <span class="sr-only">First</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="page-item"
                                                                                [ngClass]="search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage === 1 ? 'disabled' : ''">
                                                                                <a class="page-link"
                                                                                   (click)="getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage - 1)">
                                                                                    <i class="fas fa-angle-left"></i>
                                                                                    <span class="sr-only">Previous
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li *ngFor="let page of search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage"
                                                                                class="page-item"
                                                                                [ngClass]="(page + 1 === search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage) ? 'active' : ''"
                                                                                [hidden]="page > (search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage + 3) || page < (search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage - 3)">
                                                                                <a class="page-link"
                                                                                   (click)="getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(page + 1)">@{{ page + 1 }}</a>
                                                                            </li>
                                                                            <li class="page-item"
                                                                                [ngClass]="search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage >= search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage ? 'disabled' : ''">
                                                                                <a class="page-link"
                                                                                   (click)="getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage + 1)">
                                                                                    <i class="fas fa-angle-right"></i>
                                                                                    <span class="sr-only">Next</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="page-item"
                                                                                [ngClass]="search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage >= search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage ? 'disabled' : ''">
                                                                                <a class="page-link"
                                                                                   (click)="getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage)">
                                                                                    <i class="fas fa-angle-double-right"></i>
                                                                                    <span class="sr-only">Last</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </nav>
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12">
                                    <br>

                                    <div class="card shadow">
                                        <div class="table-responsive">
                                            <table class="table align-items-center table-flush">
                                                <thead class="thead-light">
                                                <tr>
@if(!empty($relation->table))
@foreach($relation->table->fields()->where('searchable', true)->orderBy('order')->get() as $field_index => $relation_field)
@if(empty($relation_field->relation))
                                                    <th [ngClass]="{'text-primary': orderBy == '{!! $relation_field->name !!}', 'pointer': true}"
                                                        (click)="onOrderBy('{!! $relation_field->name !!}', orderType == 'asc' ? 'desc' : 'asc')">
                                                        {!! $relation_field->display_name !!}
                                                        <span *ngIf="orderBy == '{!! $relation_field->name !!}' && orderType == 'asc'"
                                                              class="fas fa-chevron-up"></span>
                                                        <span *ngIf="orderBy == '{!! $relation_field->name !!}' && orderType == 'desc'"
                                                              class="fas fa-chevron-down"></span>
                                                    </th>
@else
                                                    <th scope="col">{!! $relation_field->display_name !!}</th>
@endif
@endforeach
@endif
                                                    <th scope="col"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr *ngFor="let item of {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data">
@if(!empty($relation->table))
@foreach($relation->table->fields()->where('searchable', true)->orderBy('order')->get() as $field_index => $relation_field)
                                                    <td>
@if(!empty($relation_field->relation))
@if($relation_field->relation->relation_type == "belongsto")
                                                        @{{ (item?.{!! str_singular($relation_field->relation->table->name) !!}?.{!! $relation_field->relation->foreign_key_display_field->name !!}?.length > 20) ? (item?.{!! str_singular($relation_field->relation->table->name) !!}?.{!! $relation_field->relation->foreign_key_display_field->name !!} | slice:0:20) + '...' : (item?.{!! str_singular($relation_field->relation->table->name) !!}?.{!! $relation_field->relation->foreign_key_display_field->name !!}) }}
@endif
@else
                                                        @{{ (item?.{!! $relation_field->name !!}?.length > 20) ? (item?.{!! $relation_field->name !!} | slice:0:20) + '...' : (item?.{!! $relation_field->name !!}) }}
@endif
                                                    </td>
@endforeach
@endif
                                                    <td>
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm btn-icon"
                                                                (click)="onUnSelect{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(item)"
                                                                *ngIf="isAllowed('{!! snake_case(str_plural($menu->name)) !!}_update')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-trash"></i>
                                                            </span>
                                                            <span class="btn-inner--text">
                                                                Remove
                                                            </span>
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-12" style="margin-top:15px">
                                    <nav aria-label="...">
                                        <ul class="pagination justify-content-end mb-0">
                                            <li class="page-item"
                                                [ngClass]="{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage === 1 ? 'disabled' : ''">
                                                <a class="page-link" (click)="getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data()">
                                                    <i class="fas fa-angle-double-left"></i>
                                                    <span class="sr-only">First</span>
                                                </a>
                                            </li>
                                            <li class="page-item"
                                                [ngClass]="{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage === 1 ? 'disabled' : ''">
                                                <a class="page-link"
                                                   (click)="getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data({!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage - 1)">
                                                    <i class="fas fa-angle-left"></i>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            </li>
                                            <li *ngFor="let page of {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}TotalPage" class="page-item"
                                                [ngClass]="(page + 1 === {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage) ? 'active' : ''"
                                                [hidden]="page > ({!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage + 3) || page < ({!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage - 3)">
                                                <a class="page-link"
                                                   (click)="getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(page + 1)">@{{ page + 1 }}</a>
                                            </li>
                                            <li class="page-item"
                                                [ngClass]="{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage >= {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}LastPage ? 'disabled' : ''">
                                                <a class="page-link"
                                                   (click)="getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data({!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage + 1)">
                                                    <i class="fas fa-angle-right"></i>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <li class="page-item"
                                                [ngClass]="{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage >= {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}LastPage ? 'disabled' : ''">
                                                <a class="page-link"
                                                   (click)="getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data({!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}LastPage)">
                                                    <i class="fas fa-angle-double-right"></i>
                                                    <span class="sr-only">Last</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
@endif
@endforeach
@endif

                        </div>

                        <hr class="my-4"/>

@if($menu->allow_update)
                        <button type="button"
                                class="btn btn-secondary btn-icon"
                                [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}', data?.id, 'update']"
                                *ngIf="isAllowed('{!! snake_case(str_plural($menu->name)) !!}_update')">
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
