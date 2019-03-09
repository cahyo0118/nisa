{!! Form::select("relation_foreign_key[$random]", $fields, !empty($item) ? $item->relation_foreign_key : null, ["id" => "relationForeign$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
{{--{{ $item->relation_foreign_key }}--}}
