<div class="modal fade" id="customizeMenuModal{{ $menu->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customize Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'menus.store', "onsubmit" => "onUpdateDatasetMenu($menu->id);return false;"]) !!}
                {{ csrf_field() }}

                <div class="row">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label">Table</label>
                            <div class="input-group input-group-alternative">
                                {!! Form::select('table_id', App\Table::where('project_id', $menu->project_id)->pluck('name', 'id'), !empty($menu) ? $menu->parent_menu_id : null, ['id' => "tableIdInputMenu$menu->id", 'class' => 'form-control form-control-alternative']) !!}
                            </div>
                        </div>

                        {{--<hr>--}}

                    </div>

                    {{--<div id="menuTableCriteria{{ $menu->id }}" class="col-lg-12">--}}

                    {{--<h5>Criteria</h5>--}}

                    {{--<div class="row">--}}

                    {{--<div class="col-md-12">--}}
                    {{--<button class="btn btn-icon btn-3 btn-primary" type="button">--}}
                    {{--<span class="btn-inner--icon"><i class="fas fa-plus"></i></span>--}}

                    {{--<span class="btn-inner--text">Add Criteria</span>--}}
                    {{--</button>--}}
                    {{--</div>--}}

                    {{--<div class="col-lg-12">--}}
                    {{--<div class="form-group">--}}
                    {{--<label class="form-control-label">Table</label>--}}
                    {{--<div class="input-group input-group-alternative">--}}
                    {{--{!! Form::select('parent_menu_id', App\Table::where('project_id', $menu->project_id)->pluck('name', 'id'), !empty($menu) ? $menu->parent_menu_id : null, ['class' => 'form-control form-control-alternative']) !!}--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    {{--<hr>--}}

                    {{--</div>--}}

                    {{--</div>--}}

                    {{--</div>--}}

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
