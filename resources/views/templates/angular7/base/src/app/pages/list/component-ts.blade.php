import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NgxSpinnerService } from 'ngx-spinner';
import { StringUtil } from '../../utils/string.util';
import swal from 'sweetalert2';
import { {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service } from '../../services/{!! kebab_case(str_plural($menu->name)) !!}.service';

{{ '@' }}Component({
  selector: 'app-{!! kebab_case(str_plural($menu->name)) !!}',
  templateUrl: './{!! kebab_case(str_plural($menu->name)) !!}.component.html',
  styleUrls: ['./{!! kebab_case(str_plural($menu->name)) !!}.component.css']
})
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}Component implements OnInit {

    searchForm: FormGroup;
    totalPage;
    currentPage = 1;
    lastPage = 1;
    keyword = '';
    items = [];
    searchMode = false;

    constructor(
        private service: {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service,
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

    onSearch() {

        this.spinner.show();

        this.searchMode = this.searchForm.value.keyword.length ? true : false;

        if (this.searchMode) {
            this.service.getAllByKeyword(this.searchForm.value.keyword)
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

}
