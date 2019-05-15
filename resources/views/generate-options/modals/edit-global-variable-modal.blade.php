<div class="modal fade" id="editGlobalVariable{{ $global_variable->id }}Modal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit variable</h5>
            </div>
            <div class="modal-body">
                <form id="editGlobalVariable{{ $global_variable->id }}" method="post"
                      onsubmit="onSubmitVariable({{ $item->id }}, {{ $global_variable->id }});return false;">

                    {{ csrf_field() }}

                    <div class="row">

                        <input type="hidden">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i
                                                class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('name', !empty($global_variable->name) ? $global_variable->name : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...', 'required' => '']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Value</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i
                                                class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('value', !empty($global_variable->value) ? $global_variable->value : null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...', 'required' => '']) !!}
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
