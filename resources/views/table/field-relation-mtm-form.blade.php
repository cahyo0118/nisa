<div id="relationManyToMany{{ $random }}" class="card col-lg-12" style="margin-bottom: 30px">

    <div class="card-body">

        <h3>
            # Relation
        </h3>
        <div class="row">

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Relation Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", ["belongstomany" => "Belongs To Many (Many To Many)"], !empty($item) ? $item->index : null, ["class" => "form-control form-control-alternative", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Table</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", $tables, null, ["id" => "relationTable$random", "class" => "form-control form-control-alternative", "onchange" => "getAllFieldsSelectInput($random);getAllDisplayFieldsSelectInput($random);", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Local Key</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [], !empty($item) ? $item->index : null, ["id" => "relationLocal$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Foreign Key</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [], !empty($item) ? $item->index : null, ["id" => "relationForeign$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Display</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [], !empty($item) ? $item->index : null, ["id" => "relationDisplay$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <button class="btn btn-icon btn-3 btn-danger" type="button"
                onclick="deleteManyToManyRelation('{{ $random }}', {{ !empty($item) ? $item->id : 0 }})">
            <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>

            <span class="btn-inner--text">Delete Relation</span>

        </button>

    </div>

</div>

