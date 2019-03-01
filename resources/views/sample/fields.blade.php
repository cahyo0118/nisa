{{ csrf_field() }}

<h6 class="heading-small text-muted mb-4">General</h6>

<div class="pl-lg-4">

    <div class="row">

        <input type="hidden">

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">File / Image</label>
                <br>

                {!! Form::file('file', ['accept' => 'image/*', 'style' => 'display: none;']); !!}

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
                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                    </div>
                    {!! Form::text('name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
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
                    {!! Form::text('description', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write numbers...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Password</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i
                                    class="fas fa-key"></i></span>
                    </div>

                    {!! Form::password('password', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write password...']) !!}
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
                    {!! Form::email('email', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'sample@example.com']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-control-label">Textarea</label>
                {!! Form::textarea('textarea', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
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
                    {!! Form::text('date', null, ['class' => 'form-control form-control-alternative datepicker', 'placeholder' => 'Set date']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Time</label>
                {!! Form::text('time', null, ['class' => 'form-control form-control-alternative timepicker', 'placeholder' => 'Set time']) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="custom-control custom-control-alternative custom-checkbox mb-3">
                {!! Form::checkbox('checkbox', null, false, ['id' => 'checkbox', 'class' => 'custom-control-input']) !!}
                <label class="custom-control-label" for="checkbox">Checkbox</label>
            </div>
        </div>

        <div class="col-lg-6">

            <label>{!! Form::radio('radio', 'public', false) !!} Radiobox</label>
            <br>
            <label>{!! Form::radio('radio', 'private', false) !!} Radiobox</label>
            <br>
        </div>

    </div>

    <button class="btn btn-icon btn-3 btn-primary" type="submit"
            [disabled]="!voteForm.valid">
        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

        <span class="btn-inner--text">Send</span>

    </button>
</div>
