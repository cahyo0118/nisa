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
                    Users
                </h1>
            </div>

            <div class="col-xl-12">
                <!-- Actions -->
                <a class="btn btn-icon btn-3 btn-secondary" [routerLink]="['/users/create']">
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
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Roles</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr *ngFor="let item of items">
                                <td>
                                    @{{ (item?.name.length > 20) ? (item?.name | slice:0:20) + '...' : (item?.name) }}
                                </td>
                                <td>
                                    @{{ item?.email }}
                                </td>
                                <td>
                                    <span class="badge badge-primary margin-h-5" *ngFor="let role of item?.roles">
                                        @{{ role?.name }}
                                        &nbsp;
                                        <span class="text-size-12 text-danger text-center pointer"
                                              (click)="onDeleteRole(item?.id, role?.id)">&times;</span>
                                    </span>
                                    &nbsp;
                                    <button type="button"
                                            data-toggle="modal"
                                            [attr.data-target]="'#' + 'addRolesModal' + item?.id"
                                            class="btn btn-primary btn-sm btn-icon">
                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                    </button>

                                    <div class="modal fade" id="addRolesModal@{{ item?.id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Roles</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table-borderless">
                                                        <tbody>
                                                        <tr *ngFor="let role of rolesItems">
                                                            <td class="w-100">
                                                                <span
                                                                    class="badge badge-primary">@{{ role?.name }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" data-dismiss="modal"
                                                                        aria-label="Close"
                                                                        class="btn btn-primary btn-sm btn-icon"
                                                                        (click)="onAddRole(item?.id, role?.id)">
                                                                    <span class="btn-inner--icon"><i
                                                                            class="fas fa-plus"></i></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-secondary btn-sm btn-icon"
                                            [routerLink]="['/users', item?.id, 'update']">
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
