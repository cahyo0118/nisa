@extends('admin')

@section('content')
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Samples</a>
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
            <div class="col-xl-12">

                <!-- Content -->
                <div class="row">
                    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                        <div class="card card-profile shadow">
                            <div class="row justify-content-center">
                                <div class="col-lg-3 order-lg-2">
                                    <div class="card-profile-image">
                                        <a class="pointer">
                                            {{-- <img [src]="SERVER_URL+user?.photo" class="rounded-circle" *ngIf="user?.photo !== null"> --}}
                                            <img src="../assets/img/theme/team-4-800x800.jpg" class="rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <br>
                            <br>

                            <div class="card-body pt-0 pt-md-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="text-center">

                                            <input id="photoInput" type="file" accept="image/*" style="display: none;">

                                            <button class="btn btn-icon btn-3 btn-default" onclick="$('#photoInput').click()">
                                                <span class="btn-inner--icon"><i class="ni ni-image"></i></span>
                                                <span class="btn-inner--text">Change Photo</span>
                                            </button>

                                        </div>

                                    </div>
                                </div>

                                <div class="text-center">
                                    <h3>
                                        {{ Auth::user()->name }}
                                        <span class="font-weight-light"></span>
                                    </h3>

                                    <hr class="my-4" />

                                    <button class="btn btn-icon btn-3 btn-default" data-toggle="modal" data-target="#modal-form">
                                        <span class="btn-inner--icon"><i class="ni ni-key-25"></i></span>
                                        <span class="btn-inner--text">Update Password</span>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content">

                                                <div class="modal-body p-0">


                                                    <div class="card bg-secondary shadow border-0">
                                                        <div class="card-body px-lg-5 py-lg-5">
                                                            <div class="text-center text-muted mb-4">
                                                                <small>Update your password</small>
                                                            </div>

                                                            <form action="{{ route('profile.password.update') }}" method="POST">

                                                                @csrf

                                                                <input type="hidden" name="_method" value="PUT">

                                                                <div class="form-group mb-3 text-left">
                                                                    <label class="form-control-label">Current Password</label>
                                                                    <div class="input-group input-group-alternative">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                                        </div>
                                                                        <input name="current_password" class="form-control"
                                                                            placeholder="Type your current password"
                                                                            type="password" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-left">
                                                                    <label class="form-control-label">New Password</label>
                                                                    <div class="input-group input-group-alternative">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                                        </div>
                                                                        <input name="new_password" class="form-control"
                                                                            placeholder="Type your new password" type="password"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <button class="btn btn-icon btn-3 btn-default" type="submit"
                                                                        [disabled]="!changePasswordForm.valid">
                                                                        <span class="btn-inner--icon"><i class="ni ni-key-25"></i></span>
                                                                        <span class="btn-inner--text">Update Password</span>
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

                        @include('partials.alert')

                        <div class="card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">My account</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.update') }}" method="POST">

                                    @csrf

                                    <input type="hidden" name="_method" value="PUT">

                                    <h6 class="heading-small text-muted mb-4">User information</h6>
                                    <div class="pl-lg-4">

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-first-name">Full name</label>
                                                    <input type="text" id="input-first-name" name="name"
                                                        class="form-control form-control-alternative" placeholder="First name"
                                                        value="{{ Auth::user()->name ? Auth::user()->name : '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">Email address</label>
                                                    <input type="email" id="input-email" name="email" class="form-control form-control-alternative"
                                                        value="{{ Auth::user()->email ? Auth::user()->email : '' }}"
                                                        placeholder="jesse@example.com">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4" />
                                    <!-- Address -->
                                    <h6 class="heading-small text-muted mb-4">Contact information</h6>
                                    <div class="pl-lg-4">

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-organization">Organization</label>
                                                    <input type="text" id="input-organization" class="form-control form-control-alternative"
                                                        name="organization" placeholder="Organization" value="{{ !empty(Auth::user()->info->organization) ? Auth::user()->info->organization : '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-address">Address</label>
                                                    <input id="input-address" class="form-control form-control-alternative"
                                                        name="address" placeholder="Home Address" value="{{ !empty(Auth::user()->info->address) ? Auth::user()->info->address : '' }}"
                                                        type="text">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr class="my-4" />
                                    <!-- Description -->
                                    <h6 class="heading-small text-muted mb-4">About me</h6>
                                    <div class="pl-lg-4">
                                        <div class="form-group">
                                            <label>About Me</label>
                                            <textarea rows="4" class="form-control form-control-alternative"
                                                name="about" placeholder="A few words about you ...">{{ !empty(Auth::user()->info->about) ? Auth::user()->info->about : '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="pl-lg-4">
                                        <button type="submit" class="btn btn-icon btn-3 btn-primary">
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
</div>
@endsection

@section('script')
<script>
    $(function () {
        $('#photoInput').change(function(event) {

            var photo = event.target.files[0];
            var formData = new FormData();
            formData.append("photo", photo);

            $.ajax({
                type: "POST",
                url: '/me/photo/update',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('success');
                },
                error: function(error) {
                    console.log('error');
                }
            });

            console.log(event.target.files[0]);
        })
    });

</script>
@endsection
