<tr id="global_variable{!! $global_variable->id !!}">
    <th scope="row">
        {{ $global_variable->name }}
    </th>
    <td>
        {{ $global_variable->value }}
    </td>

    <td class="row w-100 justify-content-end">

        {{--@include('generate-options.modals.edit-generate-options-modal')--}}

        @include('generate-options.modals.edit-global-variable-modal')

        <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                data-target="#editGlobalVariable{{ $global_variable->id }}Modal{{ $item->id }}">
            <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
            <span class="btn-inner--text">Edit</span>
        </button>
        <button
            type="button"
            class="btn btn-icon btn-danger btn-sm"
            onclick="deleteVariable({!! $global_variable->id !!})">
            <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
            <span class="btn-inner--text">Delete</span>
        </button>
    </td>
</tr>
