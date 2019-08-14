<!-- Sidenav -->
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <app-sidebar></app-sidebar>
</nav>

<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">

            <form [formGroup]="searchForm" (submit)="onSearch()"
                  class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input formControlName="keyword" class="form-control" placeholder="Search" type="text">
                    </div>
                </div>
            </form>

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
@if(!empty($menu->table))
            <div class="col-xl-12">
                <h1 class="text-white">
@if(!empty($menu->icon))
                    <i class="fas fa-{!! $menu->icon !!}"></i>
@endif
                    {{ ucwords(str_replace('_', ' ', $menu->display_name)) }}
                </h1>
            </div>
@if($menu->allow_create)
            <div class="col-xl-12">
                <!-- Actions -->
                <a class="btn btn-icon btn-3 btn-secondary"
                   [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}/create']">
                    <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                    <span class="btn-inner--text">New</span>
                </a>
                <button type="button"
                        [class]="'btn btn-icon btn-3 float-right ' + (showFilterCard ? 'btn-info' : 'btn-secondary')"
                        (click)="onShowFilterCard()">
                    <span class="btn-inner--icon"><i class="fas fa-filter"></i></span>
                    <span class="btn-inner--text">Filter</span>
                </button>
            </div>

@endif
            <br>
            <br>
            <br>
@if($menu->allow_list)

            <div class="col-xl-12" *ngIf="showFilterCard">

                <div class="card">
                    <div class="card-body">

                        <h6 class="heading-small mb-4 w-100">
                            Filters
                            <span class="text-primary pointer float-right" (click)="onResetFilters()">Clear
                            </span>
                        </h6>

                        <form [formGroup]="filtersForm" (submit)="onSearch()">
                            <div class="row row-grid align-items-center">
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden" || $field->input_type == "text" || $field->input_type == "textarea" || $field->input_type == "image" || $field->input_type == "file")
@elseif ($field->input_type == "select")
@if(!empty($field->relation))
@if($field->dataset_type == "dynamic" || $field->relation->relation_type == "belongsto")
@php
$reference = DB::table('menu_load_references')->where('menu_id', $menu->id)->where('field_reference_id', $field->id)->first();
if (!empty($reference)) {
    $field_reference = \App\Field::find($reference->field_reference_id);
}
@endphp

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                        <select class="form-control form-control-alternative"
                                                formControlName="{{ $field->name }}" @if(!empty($field_reference))(change)="on{!! ucfirst(camel_case($field_reference->name)) !!}Change()"@endif>
                                            <option value="">--</option>
                                            <option *ngFor="let {!! str_singular($field->relation->table->name) !!} of {!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->table->name)) !!}Data"
                                                    [value]="{!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_field->name !!}">@{{ {!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!} }}</option>
                                        </select>
                                    </div>
                                </div>
@endif
@elseif($field->dataset_type == "static")

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                        <select class="form-control form-control-alternative"
                                                formControlName="{{ $field->name }}">
                                            <option value="">--</option>
@foreach($field->static_datasets as $dataset)
                                            <option value="{!! $dataset->value !!}">{!! $dataset->label !!}</option>
@endforeach
                                        </select>
                                    </div>

                                </div>
@endif
@elseif ($field->input_type == "textarea")

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                        <textarea class="form-control form-control-alternative"
                                                  rows="4"
                                                  formControlName="{{ $field->name }}"
                                                  placeholder="{{ $field->display_name }}...">
                                        </textarea>
                                    </div>
                                </div>
@elseif ($field->input_type == "checkbox")

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <input type="checkbox" formControlName="{{ $field->name }}">
                                            {{ $field->display_name }}
                                        </label>
                                    </div>
                                </div>
@elseif($field->input_type == "image")

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ $field->display_name }}</label>
                                    </div>

                                    <img src="@{{ {!! camel_case(str_singular($menu->name)) !!}Form.value.{!! $field->name !!} }}"
                                         class="mw-100 margin-v-5"
                                         onError="this.src='../../assets/img/defaults/picture-128px.png'">
                                    <br>
                                    <br>

                                    <input type="hidden"
                                           formControlName="{{ $field->name }}"
                                           [(ngModel)]="{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $field->name }}">

                                    <input #{{ $field->name }}Input
                                           type="file"
                                           accept="image/*"
                                           style="display: none;"
                                           (change)="onUpdatePicture($event, '{{ $field->name }}')">

                                    <button type="button" class="btn btn-icon btn-3 btn-default btn-sm"
                                            (click)="{{ $field->name }}Input.click()">
                                        <span class="btn-inner--icon"><i class="fas fa-image"></i></span>
                                        <span class="btn-inner--text">Change Photo</span>
                                    </button>

                                    <button type="button"
                                            *ngIf="{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $field->name }}"
                                            class="btn btn-icon btn-3 btn-danger btn-sm"
                                            (click)="onRemovePicture('{{ $field->name }}')">
                                        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                        <span class="btn-inner--text">Remove</span>
                                    </button>

                                    <br><br>
                                </div>
@else
@endif
@endforeach
@endif

                                <div class="col-md-12">

                                    <button type="button" class="btn btn-icon btn-primary btn-sm float-right"
                                            (click)="onApplyFilters()">
                                        <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                                        <span class="btn-inner--text">Apply</span>
                                    </button>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <br>
            </div>

            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                            <tr>
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->orderBy('order')->get() as $field_index => $field)
@if(empty($field->relation))
                                <th [ngClass]="{'text-primary': orderBy == '{!! $field->name !!}', 'pointer': true}"
                                    (click)="onOrderBy('{!! $field->name !!}', orderType == 'asc' ? 'desc' : 'asc')">
                                    {!! $field->display_name !!}
                                    <span *ngIf="orderBy == '{!! $field->name !!}' && orderType == 'asc'"
                                          class="fas fa-chevron-up"></span>
                                    <span *ngIf="orderBy == '{!! $field->name !!}' && orderType == 'desc'"
                                          class="fas fa-chevron-down"></span>
                                </th>
@else
                                <th scope="col">{!! $field->display_name !!}</th>
@endif
@endforeach
@endif
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr *ngFor="let item of items">
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->orderBy('order')->get() as $field_index => $field)
                                <td>
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                                    @{{ (item?.{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}?.{!! $field->relation->foreign_key_display_field->name !!}?.length > 20) ? (item?.{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}?.{!! $field->relation->foreign_key_display_field->name !!} | slice:0:20) + '...' : (item?.{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}?.{!! $field->relation->foreign_key_display_field->name !!}) }}
@endif
@elseif($field->input_type == "image")
                                    <img src="@{{ SERVER_URL + item?.{!! $field->name !!} }}"
                                         class="mw-100 margin-v-5"
                                         onError="this.src='../../assets/img/defaults/picture-128px.png'">
@elseif($field->input_type == "select" || $field->input_type == "radio")
@if(!empty($field->relation))
@if($field->dataset_type == "dynamic" || $field->relation->relation_type == "belongsto")
                                    @{{ (item?.{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}?.{!! $field->relation->foreign_key_display_field->name !!}?.length > 20) ? (item?.{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}?.{!! $field->relation->foreign_key_display_field->name !!} | slice:0:20) + '...' : (item?.{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}?.{!! $field->relation->foreign_key_display_field->name !!}) }}
@endif
@elseif($field->dataset_type == "static")
@foreach($field->static_datasets as $dataset)
                                    <span *ngIf="item?.{!! $field->name !!} === '{!! $dataset->value !!}'">
                                        {!! $dataset->label !!}
                                    </span>
@endforeach
@endif

@else
                                    @{{ (item?.{!! $field->name !!}?.length > 20) ? (item?.{!! $field->name !!} | slice:0:20) + '...' : (item?.{!! $field->name !!}) }}
@endif
                                </td>
@endforeach
@endif
                                <td class="row w-100 justify-content-end">
@if($menu->allow_single)
                                    <button type="button"
                                            class="btn btn-info btn-sm btn-icon"
                                            [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}', item?.id, 'single']">
                                        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                                        <span class="btn-inner--text">Detail</span>
                                    </button>
@endif
@if($menu->allow_update)
                                    <button type="button"
                                            class="btn btn-secondary btn-sm btn-icon"
                                            [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}', item?.id, 'update']"
                                            *ngIf="isAllowed('{!! snake_case(str_plural($menu->name)) !!}_update')">
                                        <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                        <span class="btn-inner--text">Edit</span>
                                    </button>
@endif
@if($menu->allow_delete)
                                    <button type="button" class="btn btn-danger btn-sm btn-icon"
                                            (click)="deleteConfirmation(item.id)"
                                            *ngIf="isAllowed('{!! snake_case(str_plural($menu->name)) !!}_delete')">
                                        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                        <span class="btn-inner--text">Delete</span>
                                    </button>
@endif
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item"
                                    [ngClass]="currentPage === 1 ? 'disabled' : ''">
                                    <a class="page-link" (click)="getAllData()">
                                        <i class="fas fa-angle-double-left"></i>
                                        <span class="sr-only">First</span>
                                    </a>
                                </li>
                                <li class="page-item"
                                    [ngClass]="currentPage === 1 ? 'disabled' : ''">
                                    <a class="page-link"
                                       (click)="getAllData(currentPage - 1)">
                                        <i class="fas fa-angle-left"></i>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li *ngFor="let page of totalPage" class="page-item"
                                    [ngClass]="(page + 1 === currentPage) ? 'active' : ''"
                                    [hidden]="page > (currentPage + 3) || page < (currentPage - 3)">
                                    <a class="page-link"
                                       (click)="getAllData(page + 1)">@{{ page + 1 }}</a>
                                </li>
                                <li class="page-item"
                                    [ngClass]="currentPage >= lastPage ? 'disabled' : ''">
                                    <a class="page-link"
                                       (click)="getAllData(currentPage + 1)">
                                        <i class="fas fa-angle-right"></i>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                                <li class="page-item"
                                    [ngClass]="currentPage >= lastPage ? 'disabled' : ''">
                                    <a class="page-link"
                                       (click)="getAllData(lastPage)">
                                        <i class="fas fa-angle-double-right"></i>
                                        <span class="sr-only">Last</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>

            </div>
@endif
@else
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row row-grid align-items-center">
                            <div class="col-md-6 order-lg-2 ml-lg-auto">
                                <div class="position-relative pl-md-5">
                                    <img src="../assets/img/ill/ill-2.svg" class="img-center img-fluid">
                                </div>
                            </div>
                            <div class="col-lg-6 order-lg-1">

                                <div class="pl-4">
                                    <h1>Cutomize Your Page</h1>
                                    <h2 class="text-primary">Be Creative !</h2>
                                    <p class="card-text">Do this for your last job in this project.</p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
@endif

            <div class="col-xl-12">
                <app-footer></app-footer>
            </div>

        </div>
        <!-- End Row -->

    </div>

</div>
