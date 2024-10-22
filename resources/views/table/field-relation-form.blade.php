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

@endphp
<div class="col-lg-12">
    <div id="relation{{ $random }}" class="card" style="margin-bottom: 30px">

        <div class="card-body">

            <h3>
                # Relation
                <button class="btn btn-primary btn-sm float-right"
                        type="button"
                        onclick="useStaticDataset('{{ $random }}', '{{ $project_id }}', {{ !empty($item) ? $item->id : 0 }})">
                    Use static dataset
                </button>

                {!! Form::hidden("dataset_type[$random]", "dynamic") !!}
            </h3>

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-control-label">Relation Name</label>
                        <div class="input-group input-group-alternative">
                            {!! Form::text("relation_name[$random]", !empty($item) ? $item->relation_name : null, ["class" => "form-control form-control-alternative"]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label class="form-control-label">Relation Display Name</label>
                        <div class="input-group input-group-alternative">
                            {!! Form::text("relation_display_name[$random]", !empty($item) ? $item->relation_display_name : null, ["class" => "form-control form-control-alternative"]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col">
                    <div class="form-group">
                        <label class="form-control-label">Relation Type</label>
                        <div class="input-group input-group-alternative">
                            {!! Form::select("relation_type[$random]", ["belongsto" => "Belongs To"], !empty($item) ? $item->relation_type : null, ["class" => "form-control form-control-alternative", "required" => ""]) !!}
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label class="form-control-label">Table</label>
                        <div class="input-group input-group-alternative">
                            {!! Form::select("relation_table[$random]", $tables, !empty($item) ? $item->table_id : null, ["id" => "relationTable$random", "class" => "form-control form-control-alternative", "onchange" => "getAllFieldsSelectInput($random);getAllDisplayFieldsSelectInput($random);", "required" => ""]) !!}
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label class="form-control-label">Foreign Key</label>
                        <div class="input-group input-group-alternative">
                            {!! Form::select("relation_foreign_key[$random]", [], !empty($item) ? $item->relation_foreign_key : null, ["id" => "relationForeign$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label class="form-control-label">Display</label>
                        <div class="input-group input-group-alternative">
                            {!! Form::select("relation_display[$random]", [], !empty($item) ? $item->relation_display : null, ["id" => "relationDisplay$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                        </div>
                    </div>
                </div>

            </div>

{{--            @include('table.partials.relation-criterias', ['random' => $random, 'fields' => $item->table->fields])--}}

            <button class="btn btn-icon btn-3 btn-danger btn-sm" type="button"
                    onclick="deleteRelation('{{ $random }}', {{ !empty($item) ? $item->id : 0 }})">
                <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>

                <span class="btn-inner--text">Delete Relation</span>

            </button>

        </div>

    </div>

</div>
