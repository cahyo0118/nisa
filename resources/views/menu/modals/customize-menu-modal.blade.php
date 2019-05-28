@php
    $number_operators = [
        "" => "--",
        ">" => ">",
        ">=" => ">=",
        "<" => "<",
        "<=" => "<=",
        "like" => "LIKE",
        "like%" => "LIKE %...%",
        "not_like" => "NOT LIKE",
        "=" => "=",
        "!=" => "!=",
        "single_quotes=" => "= ''",
        "single_quotes!=" => "!= ''",
        "in" => "IN",
        "not_in" => "NOT IN",
        "between" => "BETWEEN",
        "not_between" => "NOT BETWEEN",
        "is_null" => "IS NULL",
        "is_not_null" => "IS NOT NULL",
    ];

    $text_operators = [
        "" => "--",
        "like" => "LIKE",
        "like%" => "LIKE %...%",
        "not_like" => "NOT LIKE",
        "=" => "=",
        "!=" => "!=",
        "single_quotes=" => "= ''",
        "single_quotes!=" => "!= ''",
        "in" => "IN",
        "not_in" => "NOT IN",
        "between" => "BETWEEN",
        "not_between" => "NOT BETWEEN",
        "is_null" => "IS NULL",
        "is_not_null" => "IS NOT NULL",
    ];
@endphp
<div class="modal fade" id="customizeMenuModal{{ $menu->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customize Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'menus.store', "onsubmit" => "onUpdateDatasetMenu($menu->id);return false;", "id" => "customizeMenuForm{$menu->id}"]) !!}
                {{ csrf_field() }}


                <h6 class="heading-small text-muted mb-4">Display Settings</h6>

                <div class="row">

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
                                                @if($icon->name == $menu->icon) selected @endif>
                                            <i class="fas fa-{{ $icon->name }}"></i>
                                            {{ $icon->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12"></div>

                    <div class="col-lg">
                        <div class="form-group">
                            <label class="form-control-label">
                                {!! Form::hidden("allow_list", 0) !!}
                                {!! Form::checkbox("allow_list", 1, $menu->allow_list) !!}
                                List
                            </label>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="form-group">
                            <label class="form-control-label">
                                {!! Form::hidden("allow_create", 0) !!}
                                {!! Form::checkbox("allow_create", 1, $menu->allow_create) !!}
                                Create
                            </label>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="form-group">
                            <label class="form-control-label">
                                {!! Form::hidden("allow_single", 0) !!}
                                {!! Form::checkbox("allow_single", 1, $menu->allow_single) !!}
                                Single
                            </label>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="form-group">
                            <label class="form-control-label">
                                {!! Form::hidden("allow_update", 0) !!}
                                {!! Form::checkbox("allow_update", 1, $menu->allow_update) !!}
                                Update
                            </label>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="form-group">
                            <label class="form-control-label">
                                {!! Form::hidden("allow_delete", 0) !!}
                                {!! Form::checkbox("allow_delete", 1, $menu->allow_delete) !!}
                                Delete
                            </label>
                        </div>
                    </div>

                </div>

                <hr>
                <h6 class="heading-small text-muted mb-4">Data Settings</h6>

                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">Table</label>
                            <div class="input-group input-group-alternative">
                                {!! Form::select('table_id', App\Table::where('project_id', $menu->project_id)->pluck('name', 'id')->prepend('--', ''), !empty($menu->table_id) ? $menu->table_id : null, ['id' => "tableIdInputMenu$menu->id", 'class' => 'form-control form-control-alternative']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button class="btn btn-icon btn-3 btn-primary float-right" type="submit">
                            <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                            <span class="btn-inner--text">Send</span>
                        </button>
                    </div>

                    @if(!empty($menu->table))
                        <div class="col-lg-12">

                            <br>

                            <div class="table-responsive">

                                <table class="table align-items-center" data-toggle="dataTable"
                                       data-form="deleteForm">
                                    <thead>
                                    <tr>
                                        <th scope="col">Field</th>
                                        <th scope="col">Operator</th>
                                        <th scope="col">Value</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($menu->table->fields as $field)

                                        <tr id="customize_field_{!! $field->id !!}">
                                            <td>
                                                <h5>
                                                    {{ $field->name }}
                                                </h5>
                                            </td>
                                            <td class="w-25">
                                                {!! Form::select("operator[{$field->id}]", $number_operators, !empty(QueryHelpers::getCriteria($menu->id, $field->id)) ? QueryHelpers::getCriteria($menu->id, $field->id)->pivot->operator : null, ["class" => "form-control form-control-alternative"]) !!}
                                            </td>

                                            <td>
                                                {!! Form::text("value[{$field->id}]", !empty(QueryHelpers::getCriteria($menu->id, $field->id)) ? QueryHelpers::getCriteria($menu->id, $field->id)->pivot->value : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                            </td>
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    @endif

                    {!! Form::hidden('project_id', $menu->project_id) !!}

                </div>

                @if(!empty($menu->table))
                    <button class="btn btn-icon btn-3 btn-primary float-right" type="submit">
                        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                        <span class="btn-inner--text">Send</span>
                    </button>
                @endif


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
