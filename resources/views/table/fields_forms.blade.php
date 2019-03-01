<h6 class="heading-small text-muted mb-4">Fields</h6>

<div>
    <div id="table_fields" class="row">

        @if(Request::route()->getName() !== 'tables.edit')
            @include('table.default-field-forms')
        @endif

    </div>
</div>

<button class="btn btn-icon btn-3 btn-primary" type="button" onclick="addNewField()">
    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>

    <span class="btn-inner--text">Add new field</span>

</button>

@if(Request::route()->getName() == 'tables.edit')
    {{--<button class="btn btn-icon btn-3 btn-info" type="button" onclick="saveFieldsChanges({{ !empty($item) ? $item->id : 0 }})">--}}
        {{--<span class="btn-inner--icon"><i class="fas fa-sync"></i></span>--}}

        {{--<span class="btn-inner--text">Sync changes</span>--}}

    {{--</button>--}}
    <button class="btn btn-icon btn-3 btn-info" type="submit">
        <span class="btn-inner--icon"><i class="fas fa-sync"></i></span>

        <span class="btn-inner--text">Sync changes</span>

    </button>
@endif