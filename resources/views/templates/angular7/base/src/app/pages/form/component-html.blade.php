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
                <h1 class="text-white">@{{ editMode ? 'Update' : 'Add New' }} {{ ucwords(str_replace('_', ' ', $menu->display_name)) }}</h1>
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

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        <form [formGroup]="{!! camel_case(str_singular($menu->name)) !!}Form" (submit)="onSubmit()">

                            <h6 class="heading-small text-muted mb-4">General</h6>

                            <div class="pl-lg-4">

                                <div class="row">
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif ($field->input_type == "select")

@if(!empty($field->relation))
@if($field->dataset_type == "dynamic" || $field->relation->relation_type == "belongsto")
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">{{ $field->display_name }}</label>
                                            <select class="form-control form-control-alternative"
                                                    formControlName="{{ $field->name }}">
                                                <option value="">--</option>
                                                <option *ngFor="let {!! !empty($field->relation->relation_name) ? camel_case($field->relation->relation_name) : camel_case($field->relation->table->name) !!} of {!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->table->name)) !!}Data"
                                                        [value]="{!! !empty($field->relation->relation_name) ? camel_case($field->relation->relation_name) : camel_case($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_field->name !!}">@{{ {!! !empty($field->relation->relation_name) ? camel_case($field->relation->relation_name) : camel_case($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!} }}</option>
                                            </select>
                                        </div>

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'"></app-form-error-message>
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

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'"></app-form-error-message>
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

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'"></app-form-error-message>
                                    </div>
@elseif ($field->input_type == "checkbox")

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">
                                                <input type="checkbox" formControlName="{{ $field->name }}">
                                                {{ $field->display_name }}
                                            </label>
                                        </div>

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'"></app-form-error-message>
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

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'"></app-form-error-message>
                                    </div>
@else

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">{{ $field->display_name }}</label>
                                            <input type="{{ $field->input_type }}"
                                                   formControlName="{{ $field->name }}"
                                                   class="form-control form-control-alternative"
                                                   placeholder="{{ $field->display_name }}...">
                                        </div>

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'">
                                        </app-form-error-message>
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
                                                data-target="#select{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Modal">
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
                                                                    (click)="onUnSelect{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(item)">
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

                            <button class="btn btn-icon btn-3 btn-primary" type="submit" [disabled]="!{!! camel_case(str_singular($menu->name)) !!}Form.valid">
                                <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                                <span class="btn-inner--text">Send</span>

                            </button>

                        </form>

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
