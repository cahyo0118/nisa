{!! Form::select("relation_display[$random]", $fields, !empty($item) ? $item->relation_display : null, ["id" => "relationDisplay$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
