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

            <div class="col-xl-12">

                <div class="card bg-secondary shadow">

                    <div class="card-body">

                        <form>

                            <h6 class="heading-small text-muted mb-4">General</h6>

                            <div class="pl-lg-4">

                                <div class="row">

                                    <input type="hidden">

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">File / Image</label>
                                            <br>

                                            <input type="file" accept="image/*" style="display: none;">

                                            <button type="button" class="btn btn-icon btn-3 btn-default"
                                                    (click)="imageInput.click()">
                                                <span class="btn-inner--icon"><i class="ni ni-image"></i></span>
                                                <span class="btn-inner--text">Change Image</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Text</label>
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control form-control-alternative"
                                                       placeholder="Write somethings...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Number</label>
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="fas fa-sort-numeric-down"></i></span>
                                                </div>
                                                <input type="number" class="form-control form-control-alternative"
                                                       placeholder="Write somethings...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Email</label>
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control form-control-alternative"
                                                       placeholder="sample@example.com">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Textarea</label>
                                            <textarea cols="30" rows="10"
                                                      class="form-control form-control-alternative"
                                                      placeholder="Write somethings..."></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Date</label>
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="ni ni-calendar-grid-58"></i></span>
                                                </div>
                                                <input class="form-control datepicker" placeholder="Select date"
                                                       type="text" value="06/20/2018">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Time</label>
                                            <input type="text"
                                                   class="form-control form-control-alternative timepicker">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="custom-control custom-control-alternative custom-checkbox mb-3">
                                            <input class="custom-control-input"
                                                   id="checkbox1" type="checkbox">
                                            <label class="custom-control-label" for="checkbox1">Checkbox</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="custom-control custom-radio mb-3">
                                            <input class="custom-control-input"
                                                   value="private"
                                                   type="radio" checked="">
                                            <label class="custom-control-label">Radiobox</label>
                                        </div>
                                    </div>

                                </div>

                                <button class="btn btn-icon btn-3 btn-primary" type="submit"
                                        [disabled]="!voteForm.valid">
                                    <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                                    <span class="btn-inner--text">Send</span>

                                </button>
                            </div>


                        </form>

                    </div>

                </div>
            </div>

            <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>
@endsection