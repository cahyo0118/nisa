<div id="relationCriterias{!! $random !!}" class="row">
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
    <div class="col-12">
        <div class="table-responsive">

            <table
                class="table align-items-center"
                data-toggle="dataTable"
                data-form="deleteForm">
                <thead>
                <tr>
                    <th width="10%">Field</th>
                    <th width="10%">Operator</th>
                    <th width="10%">Value</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fields as $field)
                    <tr>
                        <td width="10%">
                            <h5>
                                {{ $field->name }}
                            </h5>
                        </td>
                        <td width="25%">
                            {!! Form::select("relation_field_operator[{$field->id}]", $number_operators, null, ["class" => "form-control form-control-alternative"]) !!}
                        </td>

                        <td width="25%">
                            {!! Form::text("relation_field_value[{$field->id}]", null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
