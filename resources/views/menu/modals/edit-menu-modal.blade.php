<div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Sub Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'menus.store', "id" => "updateMenuForm$menu->id", "onsubmit" => "onUpdateMenu($menu->project_id, $menu->id);return false;"]) !!}
                {{ csrf_field() }}

                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">Name</label>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                </div>
                                {!! Form::text('name', (!empty($menu) ? $menu->name : null), ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">Display Name</label>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                </div>
                                {!! Form::text('display_name', (!empty($menu) ? $menu->display_name : null), ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                            </div>
                        </div>
                    </div>

                    {!! Form::hidden('parent_menu_id', $menu->parent_menu_id) !!}

                    {!! Form::hidden('project_id', $menu->project_id) !!}

                </div>

                <button class="btn btn-icon btn-3 btn-primary" type="submit"
                        [disabled]="!voteForm.valid">
                    <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                    <span class="btn-inner--text">Send</span>

                </button>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
