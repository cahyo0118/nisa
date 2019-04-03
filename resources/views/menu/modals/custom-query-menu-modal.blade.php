<div class="modal fade" id="customQueryMenuModal{{ $menu->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customize Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'menus.store', "id" => "updateMenuForm$menu->id", "onsubmit" => "onUpdateMenu($menu->project_id, $menu->id);return false;"]) !!}
                {{ csrf_field() }}

                <div class="row">

                    <div id="menuTableCriteria{{ $menu->id }}" class="col-lg-12 row">

                        <div class="col-lg-6">

                            <div class="custom-control custom-radio mb-3">
                                <label>
                                    <input name="custom-radio-2" type="radio">
                                    Table
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="custom-control custom-radio mb-3">
                                <label>
                                    <input name="custom-radio-2" type="radio">
                                    Custom Query
                                </label>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Custom Query</label>
                                <div class="input-group input-group-alternative">
                                    {!! Form::textarea('display_name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    {!! Form::hidden('project_id', $menu->project_id) !!}

                </div>

                <button class="btn btn-icon btn-3 btn-primary float-right" type="submit">
                    <span class="btn-inner--icon"><i class="ni ni-send"></i></span>

                    <span class="btn-inner--text">Send</span>
                </button>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
