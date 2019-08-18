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
        "default" => "Default",
        "relation" => "Relation",
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

    $dataset = [];

    $users_table = $menu->project->tables()->where('name', 'users')->first();

    foreach ($users_table->fields as $users_field) {

        if (!empty($users_field->relation) && $users_field->relation->relation_type == "belongsto") {
            $dataset[$users_field->id] = "same " . $users_field->relation->relation_name;
        }

    }

//    $relations = [];
//    $relations[""] = "--";
//    if (!empty($menu->table)) {
//        foreach($menu->table->fields as $f) {
//            if(!empty($f->relation) && $f->relation->relation_type == "belongsto") {
//                $relations[$f->id] = $f->name;
//            }
//        }
//    }
@endphp
<div class="modal fade" id="datasetSettingsModal{{ $field->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dataset Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'menus.store', "onsubmit" => "onUpdateDatasetMenu($menu->id);return false;", "id" => "customizeMenuForm{$menu->id}"]) !!}
                {{ csrf_field() }}

                <div class="row">

                    <div class="col-12">

                        <br>
                        <h6 class="heading-small text-muted mb-4">Fields</h6>

                        <div class="table-responsive">

                            <table class="table table-light align-items-center" data-toggle="dataTable"
                                   data-form="deleteForm">
                                <thead>
                                <tr>
                                    <th width="10%">Field</th>
                                    <th width="10%">Operator</th>
                                    <th width="10%">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                {!! Form::hidden("dataset_relation_ids[]", $field->relation->id) !!}
                                @foreach($field->relation->table->fields()->orderBy('order')->get() as $f)
                                    {!! Form::hidden("dataset_field_ids[{$field->relation->id}][]", $f->id) !!}
                                    <tr>
                                        <td width="10%">
                                            <h5>
                                                {{ $f->name }}
                                            </h5>
                                        </td>
                                        <td width="25%">
                                            {!! Form::select("dataset_operator[{$f->id}]", $number_operators, !empty(QueryHelpers::getMenuDatasetCriteria($menu->id, $field->relation->id, $f->id)) ? QueryHelpers::getMenuDatasetCriteria($menu->id, $field->relation->id, $f->id)->operator : null, ["class" => "form-control form-control-alternative", "onchange" => "onOperatorChange({$menu->id}, {$f->id}, this.value)"]) !!}
                                        </td>

                                        <td width="25%">
                                            {!! Form::text("dataset_value[{$f->id}]", !empty(QueryHelpers::getMenuDatasetCriteria($menu->id, $field->relation->id, $f->id)) ? QueryHelpers::getMenuDatasetCriteria($menu->id, $field->relation->id, $f->id)->value : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>

                {{--@if(!empty($menu->table))--}}
                <button class="btn btn-icon btn-3 btn-primary float-right" type="submit">
                    <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                    <span class="btn-inner--text">Send</span>
                </button>
                {{--@endif--}}


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
