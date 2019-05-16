<div class="modal fade" id="generateOptionsModal{!! $item->id !!}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table-borderless">
                    <tbody>

                    @foreach (App\GenerateOption::all() as $option)
                    <tr>
                        <td class="float-left">
                            <span class="fab fas fa-{{ $option->icon }}"></span>
                            {{ $option->display_name }}
                        </td>
                        <td>
                            <label class="form-control-label">
                                {!! Form::hidden("generate_directory_{$option->name}_{$item->id}", 0) !!}
                                {!! Form::checkbox("generate_directory_{$option->name}_{$item->id}", 1, null) !!}
                                Generate project core
                            </label>
                        </td>
                        <td class="w-100 justify-content-end">
                            <button onclick="onGenerate({{ $item->id }}, '{{ $option->name }}')" type="button"
                                    class="btn btn-icon btn-dark btn-sm">
                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                <span class="btn-inner--text">Generate</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
