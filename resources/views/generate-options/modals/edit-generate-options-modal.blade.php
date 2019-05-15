<div class="modal fade" id="editGenerateOptionsModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit {{ $item->display_name }} Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addNewOptionForm" method="post"
                      onsubmit="onSubmit();return false;">

                    {{ csrf_field() }}

                    <div class="row">

                        <input type="hidden">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i
                                                class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('name', !empty($item->name) ? $item->name : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...', 'required' => '']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Display Name</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i
                                                class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('display_name', !empty($item->display_name) ? $item->display_name : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...', 'required' => '']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Template Directory
                                    Name</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i
                                                class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('template_directory', !empty($item->template_directory) ? $item->template_directory : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...', 'required' => '']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Icons</label>
                                <div class="input-group input-group-alternative">
                                    <select id="tableIdInputMenu"
                                            class="form-control form-control-alternative"
                                            data-show-icon="true"
                                            name="icon"
                                            required>
                                        <option value="">Select Icon</option>
                                        @foreach(DB::table('icons')->get() as $icon)
                                            <option value="{{ $icon->name }}"
                                                    @if(($item->icon == $icon->name) ? true : false) selected @endif>
                                                <i class="fas fa-{{ $icon->name }}"></i>
                                                {{ $icon->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <hr class="my-4"/>
                            <h6 class="heading-small text-muted mb-4">Global Variables</h6>
                        </div>

                        <div class="col-lg-12">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                    data-target="#addGlobalVariableModal{{ $item->id }}">
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                <span class="btn-inner--text">Add new variable</span>
                            </button>
                        </div>

                        <div class="col-lg-12">
                            <br>

                            <div class="table-responsive">

                                <table class="table align-items-center table-flush" data-toggle="dataTable"
                                       data-form="deleteForm">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Value</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="project_variables{{ $item->id }}">
                                    @foreach(DB::table('global_variables')->get() as $global_variable)

                                        @include('generate-options.partials.global-variable-item')

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <br>

                        </div>

                    </div>

                    <button class="btn btn-icon btn-3 btn-primary float-right"
                            type="submit">
                        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                        <span class="btn-inner--text">Send</span>

                    </button>


                </form>
            </div>
        </div>
    </div>
</div>
