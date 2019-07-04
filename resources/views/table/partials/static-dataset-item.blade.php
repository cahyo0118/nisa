<tr id="static_dataset_{!! $static_dataset->id !!}">
    <td>
        {{ $static_dataset->value }}
    </td>
    <td>
        {{ $static_dataset->label }}
    </td>

    <td class="row w-100 justify-content-end">

        @include('table.modals.edit-static-dataset-modal')

        <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                data-target="#editStaticDatasetModal{{ $static_dataset->id }}">
            <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
            <span class="btn-inner--text">Edit</span>
        </button>

        <button
            type="button"
            class="btn btn-icon btn-danger btn-sm"
            onclick="onDeleteDataset({!! $static_dataset->id !!})">
            <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
            <span class="btn-inner--text">Delete</span>
        </button>
    </td>
</tr>
