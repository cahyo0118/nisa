{{--ID--}}
@php
    $random = rand(10000, 99999);
@endphp
<div id="card{{ $random }}" class="card" style="margin-bottom: 30px">

    <div class="card-body">

        <div class="row">

            <input type="hidden">

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("name[$random]", "id", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
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
                        {!! Form::text("display_name[$random]", "ID", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("type[$random]", $types, "integer", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Input Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("input_type[$random]", $input_types, "hidden", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::number("length[$random]", 11, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Index</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [0 => "", "index" => "Index", "unique" => "Unique", "primary" => "Primary"], "primary", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::text("default[$random]", null, ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("notnull[$random]", 0) !!}
                    {!! Form::checkbox("notnull[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Not Null</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("unsigned[$random]", 0) !!}
                    {!! Form::checkbox("unsigned[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Unsigned</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("ai[$random]", 0) !!}
                    {!! Form::checkbox("ai[$random]", 1, true, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Auto Increment</label>
                </div>
            </div>
        </div>

    </div>

</div>

{{--Created at--}}
@php
    $random = rand(10000, 99999);
@endphp
<div id="card{{ $random }}" class="card" style="margin-bottom: 30px">

    <div class="card-body">

        <div class="row">

            <input type="hidden">

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("name[$random]", "created_at", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
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
                        {!! Form::text("display_name[$random]", "Created At", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("type[$random]", $types, "timestamp", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Input Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("input_type[$random]", $input_types, "hidden", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::number("length[$random]", 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Index</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [0 => "", "index" => "Index", "unique" => "Unique", "primary" => "Primary"], 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::text("default[$random]", null, ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("notnull[$random]", 0) !!}
                    {!! Form::checkbox("notnull[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Not Null</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("unsigned[$random]", 0) !!}
                    {!! Form::checkbox("unsigned[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Unsigned</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("ai[$random]", 0) !!}
                    {!! Form::checkbox("ai[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Auto Increment</label>
                </div>
            </div>
        </div>

    </div>

</div>

{{--Updated at--}}
@php
    $random = rand(10000, 99999);
@endphp
<div id="card{{ $random }}" class="card" style="margin-bottom: 30px">

    <div class="card-body">

        <div class="row">

            <input type="hidden">

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("name[$random]", "updated_at", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
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
                        {!! Form::text("display_name[$random]", "Updated At", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("type[$random]", $types, "timestamp", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Input Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("input_type[$random]", $input_types, "hidden", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::number("length[$random]", 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Index</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [0 => "", "index" => "Index", "unique" => "Unique", "primary" => "Primary"], 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::text("default[$random]", null, ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("notnull[$random]", 0) !!}
                    {!! Form::checkbox("notnull[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Not Null</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("unsigned[$random]", 0) !!}
                    {!! Form::checkbox("unsigned[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Unsigned</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("ai[$random]", 0) !!}
                    {!! Form::checkbox("ai[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Auto Increment</label>
                </div>
            </div>
        </div>

    </div>

</div>

{{--Soft Delete--}}
@php
    $random = rand(10000, 99999);
@endphp
<div id="card{{ $random }}" class="card" style="margin-bottom: 30px">

    <div class="card-body">

        <div class="row">

            <input type="hidden">

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("name[$random]", "active_flag", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
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
                        {!! Form::text("display_name[$random]", "Active Flag", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("type[$random]", $types, "tinyint", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Input Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("input_type[$random]", $input_types, "hidden", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::number("length[$random]", 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Index</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [0 => "", "index" => "Index", "unique" => "Unique", "primary" => "Primary"], 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::text("default[$random]", 1, ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("notnull[$random]", 0) !!}
                    {!! Form::checkbox("notnull[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Not Null</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("unsigned[$random]", 0) !!}
                    {!! Form::checkbox("unsigned[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Unsigned</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("ai[$random]", 0) !!}
                    {!! Form::checkbox("ai[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Auto Increment</label>
                </div>
            </div>
        </div>

    </div>

</div>

{{--Update By--}}
@php
    $random = rand(10000, 99999);
@endphp
<div id="card{{ $random }}" class="card" style="margin-bottom: 30px">

    <div class="card-body">

        <div class="row">

            <input type="hidden">

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Name</label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                        </div>
                        {!! Form::text("name[$random]", "updated_by", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
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
                        {!! Form::text("display_name[$random]", "Updated By", ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("type[$random]", $types, "integer", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Input Type</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("input_type[$random]", $input_types, "hidden", ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::number("length[$random]", 11, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-control-label">Index</label>
                    <div class="input-group input-group-alternative">
                        {!! Form::select("index[$random]", [0 => "", "index" => "Index", "unique" => "Unique", "primary" => "Primary"], 0, ["class" => "form-control form-control-alternative", "readonly" => ""]) !!}
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
                        {!! Form::text("default[$random]", null, ["class" => "form-control form-control-alternative", "placeholder" => "", "readonly" => ""]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("notnull[$random]", 0) !!}
                    {!! Form::checkbox("notnull[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Not Null</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("unsigned[$random]", 0) !!}
                    {!! Form::checkbox("unsigned[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Unsigned</label>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    {!! Form::hidden("ai[$random]", 0) !!}
                    {!! Form::checkbox("ai[$random]", 1, false, ["onclick" => "return false"]) !!}
                    <label class="form-control-label">Auto Increment</label>
                </div>
            </div>
        </div>

    </div>

</div>