<div class="col-lg-12">
    <div id="relation{{ $random }}" class="card" style="margin-bottom: 30px">

        <div class="card-body">

            <h3>
                # Relation
            </h3>
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

            <button class="btn btn-icon btn-3 btn-danger" type="button"
                    onclick="deleteRelation('{{ $random }}', {{ !empty($item) ? $item->id : 0 }})">
                <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>

                <span class="btn-inner--text">Delete Relation</span>

            </button>

        </div>

    </div>

</div>