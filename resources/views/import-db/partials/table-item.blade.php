<div id="tables" class="col-12">

    <div class="row">
        @foreach ($tables as $table)
            <div class="col-4">

                <br>

                <div class="card shadow">

                    <div class="card-header border-0 w-100">
                        <h3 class="mb-0">
                            <label class="w-100">
                                {{ $table->TABLE_NAME }}
                                <input id="{{ $table->TABLE_NAME }}Input" type="checkbox" name="tables[]"
                                       class="float-right" onchange="selectFieldAll('{{ $table->TABLE_NAME }}')">
                            </label>
                        </h3>

                    </div>

                    <div class="card-body">
                        @php
                            $fields = DB::select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='{$table->TABLE_NAME}' AND TABLE_SCHEMA='{$db_name}'");
                        @endphp
                        @foreach ($fields as $field)
                            <label class="w-100">
                                {{ $field->COLUMN_NAME }}
                                <input type="checkbox" name="fields[{{ $table->TABLE_NAME }}]" class="float-right">
                            </label>
                            <br>
                        @endforeach
                        {{--                        <p><pre>{{ print_r($field) }}</pre></p>--}}
                        {{--<label><input type="checkbox" name="columns[]">{{ $field->COLUMN_NAME }}</label>--}}
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</div>
