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
                <h1 class="text-white">@{{ editMode ? 'Update' : 'Add New' }} {{ ucwords(str_replace('_', ' ', $menu->name)) }}</h1>
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
@foreach($menu->table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif ($field->input_type == "select")

@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">{{ $field->display_name }}</label>
                                            <select class="form-control form-control-alternative"
                                                    formControlName="{{ $field->name }}">
                                                <option value="">--</option>
                                                <option *ngFor="let {!! str_singular($field->relation->table->name) !!} of {!! str_plural($field->relation->table->name) !!}Data"
                                                        [value]="{!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_field->name !!}">@{{ {!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!} }}</option>
                                            </select>
                                        </div>

                                        <app-form-error-message
                                            [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                            [errors]="{!! camel_case(str_singular($menu->name)) !!}Form?.controls?.{{ $field->name }}?.errors"
                                            [label]="'{{ $field->display_name }}'"></app-form-error-message>
                                    </div>
@endif
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
