{{ csrf_field() }}

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
                    {!! Form::text('name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
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
                    {!! Form::text('display_name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

    </div>

    <button class="btn btn-icon btn-3 btn-primary" type="submit">
        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

        <span class="btn-inner--text">Send</span>

    </button>
</div>

<hr>

<h6 class="heading-small text-muted mb-4">Database Settings (Optional)</h6>

<div class="pl-lg-4">

    <div class="row">

        <input type="hidden">

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Database Connection</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-database"></i></span>
                    </div>
                    {!! Form::text('db_connection', !empty($item) ? $item->db_connection : 'mysql', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Database Host</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-database"></i></span>
                    </div>
                    {!! Form::text('db_host', !empty($item) ? $item->db_host : '127.0.0.1', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Database Port</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-database"></i></span>
                    </div>
                    {!! Form::text('db_port', !empty($item) ? $item->db_port : '3306', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Database Name</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-database"></i></span>
                    </div>
                    {!! Form::text('db_name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Database Username</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-database"></i></span>
                    </div>
                    {!! Form::text('db_username', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Database Password</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-database"></i></span>
                    </div>
                    {!! Form::text('db_password', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

    </div>

    <button class="btn btn-icon btn-3 btn-primary" type="submit">
        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

        <span class="btn-inner--text">Send</span>

    </button>
</div>

<hr>

<h6 class="heading-small text-muted mb-4">Mail Settings (Optional)</h6>

<div class="pl-lg-4">

    <div class="row">

        <input type="hidden">

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Mail Driver</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    {!! Form::text('mail_driver', !empty($item) ? $item->mail_encryption : 'smtp', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Mail Host</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    {!! Form::text('mail_host', !empty($item) ? $item->mail_host: 'smtp.google.com', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Mail Port</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    {!! Form::text('mail_port', !empty($item) ? $item->mail_encryption : '587', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Mail Username</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    {!! Form::text('mail_username', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Mail Password</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    {!! Form::text('mail_password', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label">Mail Encryption</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    {!! Form::text('mail_encryption', !empty($item) ? $item->mail_encryption : 'tls', ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

    </div>

    <button class="btn btn-icon btn-3 btn-primary" type="submit">
        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

        <span class="btn-inner--text">Send</span>

    </button>

</div>

<hr>

<h6 class="heading-small text-muted mb-4">Page Settings (Optional)</h6>

<div class="pl-lg-4">

    <div class="row">

        <input type="hidden">

        <div class="col-lg-4">
            <div class="form-group">
                <label class="form-control-label">Item Per Page</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                    </div>
                    {!! Form::number('item_per_page', !empty($item) ? $item->item_per_page : 15, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                </div>
            </div>
        </div>

    </div>

    <button class="btn btn-icon btn-3 btn-primary" type="submit">
        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

        <span class="btn-inner--text">Send</span>

    </button>
</div>

@if(!empty($item))
    <hr>

    <h6 class="heading-small text-muted mb-4">Global Variables</h6>

    <div class="pl-lg-4">

        <div class="row">

            <div class="col-lg-12">

                @foreach (App\GenerateOption::all() as $option)

                    <h3>
                        <i class="fas fa-{{ $option->icon }}"></i>
                        {{ $option->display_name }}
                    </h3>

                    <div class="table-responsive">

                        @if(count(DB::table('global_variables')->where('generate_option_id', $option->id)->get()) < 1)
                            <h5 class="text-center">
                                Global Variable not found
                            </h5>
                        @else
                            <table class="table align-items-center" data-toggle="dataTable"
                                   data-form="deleteForm">
                                <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Value</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="project_variables{{ $item->id }}">
                                @foreach(DB::table('global_variables')->where('generate_option_id', $option->id)->get() as $global_variable)

                                    @php
                                        $variable = $item->variables()->where('variable_id', $global_variable->id)->first()
                                    @endphp

                                    <tr id="global_variable{!! $global_variable->id !!}">
                                        <td>
                                            <h5>
                                                {{ $global_variable->name }}
                                            </h5>
                                            {{--                                        {!! Form::text('name', $global_variable->name, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}--}}
                                        </td>
                                        <td class="w-100">
                                            {!! Form::text('value', !empty($variable->pivot->value) ? $variable->pivot->value : $global_variable->value, ['id' => "variable{$global_variable->id}ValueInput", 'class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                        </td>

                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-icon btn-primary btn-sm"
                                                onclick="onFillVariable({!! $item->id !!}, {!! $global_variable->id !!})">
                                                <span class="btn-inner--icon"><i class="fas fa-save"></i></span>
                                                <span class="btn-inner--text">Save</span>
                                            </button>
                                        </td>

                                    </tr>
                                    {{--@include('generate-options.partials.global-variable-item')--}}
                                @endforeach

                                </tbody>
                            </table>
                        @endif
                    </div>

                    <hr>

                @endforeach

            </div>

        </div>

        <button class="btn btn-icon btn-3 btn-primary" type="submit">
            <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

            <span class="btn-inner--text">Send</span>

        </button>
    </div>

@endif
