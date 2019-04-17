<div id="card{{ $random }}" class="card" style="margin-bottom: 30px">

    <div class="card-body">

        <div class="row">

            {!! Form::hidden("id[$random]", !empty($item) ? $item->id : null) !!}

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("name[$random]", !empty($item) ? $item->name : null, ["class" => "form-control form-control-alternative", "placeholder" => "", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Display Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("display_name[$random]", !empty($item) ? $item->display_name : null, ["class" => "form-control form-control-alternative", "placeholder" => "", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("type[$random]", $types, !empty($item) ? $item->type : null, ["id" => "fieldType$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Input Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("input_type[$random]",
                            $input_types,
                            !empty($item) ? $item->input_type : null,
                            [
                                "id" => "fieldInputType$random",
                                "class" => "form-control form-control-alternative",
                                "required" => "",
                                "onchange" => "onInputTypeChange($random, this.value)"
                            ]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Length</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-sort"></i></span>
                        </div>
                        {!! Form::number("length[$random]", !empty($item) ? $item->length : 0, ["id" => "fieldLength$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Index</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [0 => "", "index" => "Index", "unique" => "Unique", "primary" => "Primary"], !empty($item) ? $item->index : null, ["class" => "form-control form-control-alternative"]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Default</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("default[$random]", !empty($item) ? $item->default : null, ["class" => "form-control form-control-alternative", "placeholder" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("notnull[$random]", 0) !!}
                    {!! Form::checkbox("notnull[$random]", 1, !empty($item) ? $item->notnull : 1) !!}
                    <label class="form-control-label">Not Null</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("unsigned[$random]", 0) !!}
                    {!! Form::checkbox("unsigned[$random]", 1, !empty($item) ? $item->unsigned : null) !!}
                    <label class="form-control-label">Unsigned</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("ai[$random]", 0) !!}
                    {!! Form::checkbox("ai[$random]", 1, !empty($item) ? $item->ai : null) !!}
                    <label class="form-control-label">Auto Increment</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("searchable[$random]", 0) !!}
                    {!! Form::checkbox("searchable[$random]", 1, !empty($item) ? $item->searchable : null) !!}
                    <label class="form-control-label">Searchable</label>
                </div>
            </div>

        </div>

        <div id="relationDiv{{ $random }}" class="row">

        </div>

        <button class="btn btn-icon btn-3 btn-danger btn-sm" type="button"
                onclick="deleteField('{{ $random }}', {{ !empty($item) ? $item->id : 0 }})">
            <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>

            <span class="btn-inner--text">Delete</span>

        </button>

        <button class="btn btn-icon btn-3 btn-info btn-sm float-right" type="button"
                onclick="createRelation('{{ $random }}', {{ !empty($item) ? $item->id : 0 }})">
            <span class="btn-inner--icon"><i class="fas fa-handshake"></i></span>

            <span class="btn-inner--text">Create Relation</span>

        </button>
    </div>

</div>
