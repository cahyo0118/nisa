<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" [routerLink]="['/dashboard']" routerLinkActive="text-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
@foreach($project->menus as $menu)
@if((empty($menu->parent_menu_id) && empty(count($menu->sub_menus))))
    <li class="nav-item">
        <a class="nav-link" [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}']" routerLinkActive="text-primary"
           *ngIf="isAllowed('{!! snake_case(str_plural($menu->name)) !!}_read')">
            <i class="fas fa-{!! $menu->icon ? $menu->icon : 'cogs' !!}"></i>
            <span class="w-100">{!! ucfirst(str_replace('_', ' ', $menu->display_name)) !!}</span>
        </a>
    </li>
@elseif(!empty(count($menu->sub_menus)))
    <li class="nav-item">
        <a class="nav-link"
           href="#navbar-{!! kebab_case(str_plural($menu->name)) !!}"
           data-toggle="collapse"
           role="button"
           aria-expanded="true"
           aria-controls="navbar-{!! kebab_case(str_plural($menu->name)) !!}">
            <i class="fas fa-{!! $menu->icon ? $menu->icon : 'cogs' !!}"></i>
            <span class="nav-link-text">{!! ucfirst(str_replace('_', ' ', $menu->display_name)) !!}</span>
        </a>

        <div class="collapse show" id="navbar-{!! kebab_case(str_plural($menu->name)) !!}">
            <ul class="nav nav-sm flex-column">
@foreach($menu->sub_menus as $sub_menu)
                <li class="nav-item">
                    <a class="nav-link" [routerLink]="['/{!! kebab_case(str_plural($sub_menu->name)) !!}']" routerLinkActive="text-primary"
                       *ngIf="isAllowed('{!! snake_case(str_plural($sub_menu->name)) !!}_read')">
                        <i class="fas fa-{!! $sub_menu->icon ? $sub_menu->icon : 'key' !!}"></i>
                        <span class="w-100">{!! ucfirst(str_replace('_', ' ', $sub_menu->display_name)) !!}</span>
                    </a>
                </li>
@endforeach
            </ul>
        </div>
    </li>
@endif
@endforeach

    <li class="nav-item">
        <a class="nav-link"
           href="#navbar-dashboards"
           data-toggle="collapse"
           role="button"
           aria-expanded="true"
           aria-controls="navbar-dashboards">
            <i class="fas fa-cogs"></i>
            <span class="nav-link-text">Settings</span>
        </a>

        <div class="collapse show" id="navbar-dashboards">
            <ul class="nav nav-sm flex-column">

                <li class="nav-item">
                    <a class="nav-link" [routerLink]="['/permissions']" routerLinkActive="text-primary"
                       *ngIf="isAllowed('permissions_read')">
                        <i class="fas fa-key"></i>
                        <span class="w-100">Permissions</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" [routerLink]="['/roles']" routerLinkActive="text-primary"
                       *ngIf="isAllowed('roles_read')">
                        <i class="fas fa-user-tag"></i>
                        <span class="w-100">Roles</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" [routerLink]="['/users']" routerLinkActive="text-primary"
                       *ngIf="isAllowed('users_read')">
                        <i class="fas fa-users"></i>
                        <span class="w-100">Users</span>
                    </a>
                </li>

            </ul>
        </div>
    </li>

{{--    {!! QueryHelpers::recursiveMenu($project->id, "") !!}--}}
</ul>
