<div id="menuItemCard{{ $menu->id }}" class="card">
    <div class="card-header" id="heading{{ $random }}">
        <div class="row">
            <div class="col">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse"
                            data-target="#menuItem{{ $menu->id }}" aria-expanded="true"
                            aria-controls="menuItem{{ $menu->id }}">
                        @if(!empty($menu->icon))
                            <span class="fas fa-{!! $menu->icon !!}"></span>
                        @endif
                        {{ $menu->display_name }}

                    </button>
                </h2>
            </div>

            <div class="col text-right">

                @if(empty($menu->parent_menu_id))
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#addMenuModal{{ $menu->id }}">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text">Add Sub Menu</span>
                    </button>
                @endif

                <button type="button" class="btn btn-dark btn-sm" data-toggle="modal"
                        data-target="#customizeMenuModal{{ $menu->id }}">
                    <span class="btn-inner--icon"><i class="fas fa-hand-point-up"></i></span>
                    <span class="btn-inner--text">Customize</span>
                </button>

                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                        data-target="#editMenuModal{{ $menu->id }}">
                    <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                    <span class="btn-inner--text">Edit</span>
                </button>

                <button type="button" class="btn btn-danger btn-sm" onclick="deleteMenu({{ $menu->id }})">
                    <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                    <span class="btn-inner--text">delete</span>
                </button>

            </div>
        </div>

    </div>

    <div id="menuItem{{ $menu->id }}" class="collapse" aria-labelledby="heading{{ $random }}"
         data-parent="#menus_list{{ !empty($parent_menu) ? $parent_menu->id : '' }}">

        <div class="card-body">

            <div id="menus_list{{ $menu->id }}" class="accordion" style="margin-bottom: 30px;"></div>

        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="addMenuModal{{ $menu->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sub Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'menus.store', "id" => "addMenuForm$menu->id", "onsubmit" => "onSubmitMenu($menu->project_id, $menu->id);return false;"]) !!}

                    {{ csrf_field() }}

                    <div class="row">

                        <input type="hidden">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    </div>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
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
                                    {!! Form::text('display_name', null, ['class' => 'form-control form-control-alternative', 'placeholder' => 'Write somethings...']) !!}
                                </div>
                            </div>
                        </div>

                        {!! Form::hidden('parent_menu_id', $menu->id) !!}

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

    {{--@include('menu.modals.custom-query-menu-modal')--}}
    @include('menu.modals.customize-menu-modal')

    @include('menu.modals.edit-menu-modal')

</div>
