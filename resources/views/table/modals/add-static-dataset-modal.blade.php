<div class="modal fade" id="addStaticDatasetModal{{ !empty($item) ? $item->id : 0 }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Dataset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="onStoreDataset({{ $random }}, {{ $project_id }}, {{ !empty($item) ? $item->id : 0 }});return false;" id="addStaticDatasetForm{{ !empty($item) ? $item->id : 0 }}">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Value</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('value', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Label</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('label', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-icon btn-3 btn-primary float-right"
                            type="submit">
                        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                        <span class="btn-inner--text">Send</span>

                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
