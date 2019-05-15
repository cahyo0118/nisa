<!-- Sidenav -->
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <app-sidebar></app-sidebar>
</nav>

<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block">Dashboard</a>

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

                <!-- Content -->
                <div class="row">
                    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                        <div class="card card-profile shadow">
                            <div class="row justify-content-center">
                                <div class="col-lg-3 order-lg-2">
                                    <div class="card-profile-image">
                                        <a class="pointer">
                                            <img [src]="SERVER_URL+user?.photo" class="rounded-circle"
                                                 *ngIf="user?.photo !== null">
                                            <img src="../assets/img/theme/team-4-800x800.jpg" class="rounded-circle"
                                                 *ngIf="user?.photo === null">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                                <div class="d-flex justify-content-between">
                                    <br>
                                </div>
                            </div>
                            <div class="card-body pt-0 pt-md-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="text-center">

                                            <input #photoInput type="file" accept="image/*" style="display: none;"
                                                   (change)="onUpdateCurrentUserPhoto($event)">

                                            <button class="btn btn-icon btn-3 btn-default" (click)="photoInput.click()">
                                                <span class="btn-inner--icon"><i class="ni ni-image"></i></span>
                                                <span class="btn-inner--text">Change Photo</span>
                                            </button>

                                            <div class="progress-wrapper" *ngIf="percentCompleted > 0">
                                                <div class="progress-info">
                                                    <div class="progress-label">
                                                        <span>Uploading...</span>
                                                    </div>
                                                    <div class="progress-percentage">
                                                        <span>@{{ percentCompleted }}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-default"
                                                         role="progressbar"
                                                         aria-valuenow="60"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100"
                                                         [style.width.%]="percentCompleted">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3>
                                        @{{ user?.name }}<span class="font-weight-light"></span>
                                    </h3>
                                    <div class="h5 font-weight-300">
                                        <i class="ni location_pin mr-2"></i>
                                        @{{ user?.address }}
                                    </div>
                                    <div>
                                        <i class="ni education_hat mr-2"></i>
                                        @{{ user?.organization }}
                                    </div>
                                    <hr class="my-4"/>
                                    <p>@{{ user?.about }}</p>

                                    <button class="btn btn-icon btn-3 btn-default" data-toggle="modal"
                                            data-target="#modal-form">
                                        <span class="btn-inner--icon"><i class="ni ni-key-25"></i></span>
                                        <span class="btn-inner--text">Update Password</span>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog"
                                         aria-labelledby="modal-form"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content">

                                                <div class="modal-body p-0">


                                                    <div class="card bg-secondary shadow border-0">
                                                        <div class="card-body px-lg-5 py-lg-5">
                                                            <div class="text-center text-muted mb-4">
                                                                <small>Update your password</small>
                                                            </div>

                                                            <form [formGroup]="changePasswordForm"
                                                                  (submit)="onUpdateCurrentUserPassword()">
                                                                <div class="form-group mb-3 text-left">
                                                                    <label class="form-control-label">Current
                                                                        Password</label>
                                                                    <div class="input-group input-group-alternative">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i
                                                                                    class="ni ni-lock-circle-open"></i></span>
                                                                        </div>
                                                                        <input formControlName="currentPassword"
                                                                               class="form-control"
                                                                               placeholder="Type your current password"
                                                                               type="password" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-left">
                                                                    <label class="form-control-label">New
                                                                        Password</label>
                                                                    <div class="input-group input-group-alternative">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i
                                                                                    class="ni ni-lock-circle-open"></i></span>
                                                                        </div>
                                                                        <input formControlName="newPassword"
                                                                               class="form-control"
                                                                               placeholder="Type your new password"
                                                                               type="password" required>
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <button class="btn btn-icon btn-3 btn-default"
                                                                            type="submit"
                                                                            [disabled]="!changePasswordForm.valid">
                                                                        <span class="btn-inner--icon"><i
                                                                                class="ni ni-key-25"></i></span>
                                                                        <span
                                                                            class="btn-inner--text">Update Password</span>
                                                                    </button>
                                                                </div>

                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Modal -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 order-xl-1">
                        <div class="card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <h3 class="mb-0">My account</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form [formGroup]="userForm" (submit)="onUpdateCurrentUser()">
                                    <h6 class="heading-small text-muted mb-4">User information</h6>
                                    <div class="pl-lg-4">

                                        <div class="row">
@foreach($project->tables()->where('name', 'users')->first()->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif($field->name == "photo")
@elseif($field->name == "password")
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
@elseif($field->input_type == "image")
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">{{ $field->display_name }}</label>
                                                </div>

                                                <img src="@{{ userForm.value.{!! $field->name !!} }}"
                                                     class="mw-100 margin-v-5"
                                                     onError="this.src='../../assets/img/defaults/picture-128px.png'">
                                                <br>
                                                <br>

                                                <input type="hidden"
                                                       formControlName="{{ $field->name }}"
                                                       [(ngModel)]="userForm.value.{{ $field->name }}">

                                                <input #{{ $field->name }}Input
                                                       type="file"
                                                       accept="image/*"
                                                       style="display: none;"
                                                       (change)="onUpdatePicture($event, '{{ $field->name }}')">

                                                <button type="button" class="btn btn-icon btn-3 btn-default btn-sm" (click)="{{ $field->name }}Input.click()">
                                                    <span class="btn-inner--icon"><i class="fas fa-image"></i></span>
                                                    <span class="btn-inner--text">Change Photo</span>
                                                </button>

                                                <button type="button"
                                                        *ngIf="userForm.value.{{ $field->name }}"
                                                        class="btn btn-icon btn-3 btn-danger btn-sm"
                                                        (click)="onRemovePicture('{{ $field->name }}')">
                                                    <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                                    <span class="btn-inner--text">Remove</span>
                                                </button>

                                                <br><br>

                                                <app-form-error-message
                                                    [apiValidationErrors]="apiValidationErrors?.{{ $field->name }}"
                                                    [errors]="userForm?.controls?.{{ $field->name }}?.errors"
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
                                                    [errors]="userForm?.controls?.{{ $field->name }}?.errors"
                                                    [label]="'{{ $field->display_name }}'">
                                                </app-form-error-message>
                                            </div>
@endif
@endforeach

                                        </div>
                                    </div>

                                    <div class="pl-lg-4">
                                        <button class="btn btn-icon btn-3 btn-primary" [swal]="updateProfileDialog">
                                            <span class="btn-inner--icon"><i class="ni ni-send"></i></span>
                                            <span class="btn-inner--text">Update</span>
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Content -->

                <app-footer></app-footer>

            </div>
        </div>
    </div>

    <!-- Adding -->
    <swal #updateProfileDialog title="Update current user ?" text="This action cannot be undone"
          [showCancelButton]="true"
          [focusCancel]="true" (confirm)="onUpdateCurrentUser()">
    </swal>

    <swal #errorDialog [focusCancel]="true"></swal>

</div>
