<h6 class="heading-small text-muted mb-4">General</h6>

<div class="pl-lg-4">

    <div class="row">

        <input type="hidden">

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Name</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                    </div>
                    {!! Form::text('table_name', !empty($item) ? $item->name : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Display Name</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                    </div>
                    {!! Form::text('table_display_name', !empty($item) ? $item->display_name : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Project</label>
                <div class="input-group input-group-alternative">
                    {!! Form::select('table_project_id', $projects, !empty($item) ? $item->project_id : $project_id, ['class' => 'form-control form-control-alternative', 'readonly' => '']) !!}
                </div>
            </div>
        </div>

    </div>

    <button class="btn btn-icon btn-3 btn-primary" type="submit">
        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

        <span class="btn-inner--text">Send</span>

    </button>
</div>
