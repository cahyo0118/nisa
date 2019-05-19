<!-- Sidenav -->
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <app-sidebar></app-sidebar>
</nav>

<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">

            <form [formGroup]="searchForm" (submit)="onSearch()"
                  class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input formControlName="keyword" class="form-control" placeholder="Search" type="text">
                    </div>
                </div>
            </form>

            <app-account-navbar></app-account-navbar>

        </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">

            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12">
                <h1 class="text-white">
                    <i class="fas fa-users"></i>
                    {{ ucwords(str_plural(str_replace('_', ' ', $menu->name))) }}
                </h1>
            </div>

            <div class="col-xl-12">
                <!-- Actions -->
                <a class="btn btn-icon btn-3 btn-secondary" [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}/create']">
                    <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                    <span class="btn-inner--text">New</span>
                </a>
            </div>

            <br>
            <br>
            <br>

            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                            <tr>
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
                                <th scope="col">{!! $field->display_name !!}</th>
@endforeach
@endif
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr *ngFor="let item of items">
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
                                <td>
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                                    @{{ (item?.{!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!}?.length > 20) ? (item?.{!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!} | slice:0:20) + '...' : (item?.{!! str_singular($field->relation->table->name) !!}?.{!! $field->relation->foreign_key_display_field->name !!}) }}
@endif
@else
                                    @{{ (item?.{!! $field->name !!}?.length > 20) ? (item?.{!! $field->name !!} | slice:0:20) + '...' : (item?.{!! $field->name !!}) }}
@endif
                                </td>
@endforeach
@endif
                                <td class="row w-100 justify-content-end">
                                    <button type="button"
                                            class="btn btn-secondary btn-sm btn-icon"
                                            [routerLink]="['/{!! kebab_case(str_plural($menu->name)) !!}', item?.id, 'update']">
                                        <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                        <span class="btn-inner--text">Edit</span>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm btn-icon"
                                            (click)="deleteConfirmation(item.id)">
                                        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                        <span class="btn-inner--text">Delete</span>
                                    </button>

                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item" [ngClass]="currentPage === 1 ? 'disabled' : ''">
                                    <a class="page-link" (click)="getAllData(currentPage - 1)">
                                        <i class="fas fa-angle-left"></i>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li *ngFor="let page of totalPage" class="page-item"
                                    [ngClass]="(page + 1 === currentPage) ? 'active' : ''">
                                    <a class="page-link" (click)="getAllData(page + 1)">@{{ page + 1 }}</a>
                                </li>
                                <li class="page-item" [ngClass]="currentPage >= lastPage ? 'disabled' : ''">
                                    <a class="page-link" (click)="getAllData(currentPage + 1)">
                                        <i class="fas fa-angle-right"></i>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>

                <!-- End Content -->

                <app-footer></app-footer>

            </div>

        </div>
        <!-- End Row -->

    </div>

</div>
