<div class="modal fade" id="addNewVariable" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Global Variables</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addNewVariableForm" method="POST" onsubmit="onAddNewVariable('{{ $item->id }}', 0);return false">
                    {{ csrf_field() }}

                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('name', (!empty($item) ? $item->name : null), ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="form-group">
                                <label class="form-control-label">Value</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('value', (!empty($item) ? $item->display_name : null), ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                </div>
                            </div>
                        </div>

                        {!! Form::hidden('project_id', $item->id) !!}

                    </div>

                    <button class="btn btn-icon btn-3 btn-primary float-right" type="submit"
                            [disabled]="!voteForm.valid">
                        <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                        <span class="btn-inner--text">Send</span>

                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
