{!! Form::select("relation_local_key[$random]", $fields, !empty($item->relation_local_key) ? $item->relation_local_key : null, ["id" => "relationLocal$random", "class" => "form-control form-control-alternative", "required" => ""]) !!}
