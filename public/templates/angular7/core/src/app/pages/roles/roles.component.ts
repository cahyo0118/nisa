import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {NgxSpinnerService} from 'ngx-spinner';
import {StringUtil} from '../../utils/string.util';
import swal from 'sweetalert2';
import {PermissionsService} from '../../services/permissions.service';
import {RolesService} from '../../services/roles.service';

@Component({
    selector: 'app-roles',
    templateUrl: './roles.component.html',
    styleUrls: ['./roles.component.css']
})
export class RolesComponent implements OnInit {

    searchForm: FormGroup;
    totalPage;
    currentPage = 1;
    lastPage = 1;
    keyword = '';
    items;
    searchMode = false;

    // Roles
    permissionsSearchForm: FormGroup;
    permissionsTotalPage;
    permissionsCurrentPage = 1;
    permissionsLastPage = 1;
    permissionsKeyword = '';
    permissionsItems = [];
    permissionsSearchMode = false;

    constructor(
        private service: RolesService,
        private permissionsService: PermissionsService,
        private spinner: NgxSpinnerService,
        private formBuilder: FormBuilder,
    ) {

        this.searchForm = formBuilder.group({
            keyword: [
                '',
                [
                    Validators.maxLength(50),
                    Validators.required,
                ]
            ],
        });

    }

    ngOnInit() {

        this.spinner.show();

        this.getAllData();

        this.getAllPermissionsData();
    }

    getAllData(page = 1) {
        if (this.searchMode) {
            this.service.getAllByKeyword(this.searchForm.value.keyword, page)
                .then(
                    response => {
                        const data = response.data.data;

                        this.currentPage = data.current_page;
                        this.lastPage = data.last_page;
                        this.totalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.items = data.data;
                        this.spinner.hide();
                    },
                    error => {
                        console.log(error);
                        this.spinner.hide();
                    }
                );
        } else {
            this.service.getAll(page)
                .then(
                    response => {
                        const data = response.data.data;
                        this.currentPage = data.current_page;
                        this.lastPage = data.last_page;
                        this.totalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.items = data.data;
                        this.spinner.hide();
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        }
    }

    getAllPermissionsData(page = 1) {
        if (this.searchMode) {
            this.permissionsService.getAllByKeyword(this.permissionsSearchForm.value.keyword, page)
                .then(
                    response => {
                        const data = response.data.data;
                        // this.permissionsCurrentPage = data.current_page;
                        // this.permissionsLastPage = data.last_page;
                        // this.permissionsTotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.permissionsItems = data;
                        this.spinner.hide();
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        } else {
            this.permissionsService.getAllNoPagination(page)
                .then(
                    response => {
                        const data = response.data.data;
                        // this.permissionsCurrentPage = data.current_page;
                        // this.permissionsLastPage = data.last_page;
                        // this.permissionsTotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.permissionsItems = data;
                        this.spinner.hide();
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        }
    }

    onSearch() {

        this.spinner.show();

        this.searchMode = this.searchForm.value.keyword.length ? true : false;

        if (this.searchMode) {
            this.service.getAllByKeyword(this.searchForm.value.keyword)
                .then(
                    response => {
                        console.log(response);
                        this.currentPage = response.data.current_page;
                        this.lastPage = response.data.last_page;
                        this.totalPage = Array(response.data.last_page).fill(0).map((x, i) => i);
                        this.items = response.data.data;
                        this.spinner.hide();
                    },
                    error => {
                        console.log(error);
                        this.spinner.hide();
                    }
                );
        } else {

            this.getAllData();

        }
    }

    deleteConfirmation(id: number) {
        swal({
            title: 'Delete data',
            text: StringUtil.cannot_undo_message,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
        }).then(
            (result) => {
                if (result.value) {
                    this.onDelete(id);
                }
            }
        );
    }

    onDelete(id: number) {
        this.service.delete(id)
            .then(
                response => {
                    const data = response.data;
                    this.getAllData(this.currentPage);

                    swal({
                        title: 'Yay !',
                        text: data.message,
                        type: 'success',
                        confirmButtonText: 'Confirm'
                    });
                },
                error => {

                    const data = error.response.data;

                    swal({
                        title: 'Oops',
                        text: data.message,
                        type: 'error',
                        confirmButtonText: 'Confirm'
                    });
                }
            );
    }

    isPermitted(rolePermissions, permissionName): boolean {
        return rolePermissions.find(obj => obj.name === permissionName);
    }

    onPermitAll(position) {
        if (this.items[position].permissions.length < this.permissionsItems.length) {
            for (let p of this.permissionsItems) {
                this.items[position].permissions.push(p);
            }
        } else {
            this.items[position].permissions = [];
        }
    }

    onCheck(rolePosition, permissionName) {
        this.permissionsItems.find((object) => {

            if (object.name === permissionName) {
                if (typeof this.items[rolePosition].permissions.find((childObject) => childObject.name === permissionName) === 'undefined') {
                    this.items[rolePosition].permissions.push(object);
                } else {
                    this.items[rolePosition].permissions.find((childObject, childIndex) => {
                        if (childObject.name === permissionName) {
                            this.items[rolePosition].permissions.splice(childIndex, 1);
                            return childObject;
                        }
                    });
                }

                return object;
            }
        });
    }

    updateRolePermissions(rolePosition) {
        swal({
            title: 'Save Data',
            text: StringUtil.submit_data_message,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm'
        }).then(result => {
            if (result.value) {
                this.service.updateRolePermissions(this.items[rolePosition].id, this.items[rolePosition].permissions)
                    .then(
                        response => {
                            swal({
                                title: 'Yay !',
                                text: response.data.message,
                                type: 'success',
                                confirmButtonText: 'Confirm'
                            });
                        },
                        error => {
                            const data = error.response.data;
                            console.log('ErrorResponse', data);
                            swal({
                                title: 'Oops',
                                text: data.message,
                                type: 'error',
                                confirmButtonText: 'Confirm'
                            });
                        }
                    );
            }
        });
    }

}
