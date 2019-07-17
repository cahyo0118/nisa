import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NgxSpinnerService } from 'ngx-spinner';
import { StringUtil } from '../../utils/string.util';
import swal from 'sweetalert2';
import { {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service } from '../../services/{!! str_replace('_', '-', str_plural($menu->name)) !!}.service';

{{ '@' }}Component({
    selector: 'app-{!! str_replace('_', '-', str_plural($menu->name)) !!}',
    templateUrl: './{!! str_replace('_', '-', str_plural($menu->name)) !!}.component.html',
    styleUrls: ['./{!! str_replace('_', '-', str_plural($menu->name)) !!}.component.css']
})
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}Component implements OnInit {

    searchForm: FormGroup;
    totalPage;
    currentPage = 1;
    lastPage = 1;
    keyword = '';
    items = [];
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    {!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->table->name)) !!}Data: any;
@endif
@endif
@endforeach
@endif
    searchMode = false;
    orderBy = 'created_at';
    orderType = 'desc';
    showFilterCard = false;
    filters = [];
    filtersForm: FormGroup;

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

        this.filtersForm = formBuilder.group({
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden" || $field->input_type == "text")
@else
            {!! $field->name !!}: [
@if($field->input_type == "checkbox")
@if($field->default > 0)
                true,
@else
                false,
@endif
@else
                '',
@endif
                [
@if($field->notnull)
                    Validators.required,
@endif
@if($field->length > 0)
                    Validators.maxLength({!! $field->length !!}),
@endif
@if($field->input_type == "email")
                    Validators.email,
@endif
                ]
            ],
@endif
@endforeach
@endif
        });

    }

    ngOnInit() {
        this.spinner.show();

        this.getAllData();

        this.getAllDataSets();
    }

    getAllData(page = 1) {
        if (this.searchMode) {
            this.service.getAllByKeyword(this.searchForm.value.keyword, page, this.orderBy, this.orderType, this.filtersForm.value)
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
            this.service.getAll(page, this.orderBy, this.orderType, this.filtersForm.value)
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

    getAllDataSets() {
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
        this.service.get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet()
            .then(
                response => {
                    const data = response.data;
                    this.{!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->table->name)) !!}Data = data.data;
                },
                error => {
                }
            );

@endif
@endif
@endforeach
@endif
    }

    onSearch() {

        this.spinner.show();

        this.searchMode = this.searchForm.value.keyword.length ? true : false;

        if (this.searchMode) {
            this.service.getAllByKeyword(this.searchForm.value.keyword, 1, this.orderBy, this.orderType)
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

    onOrderBy(orderBy, orderType) {
        this.orderBy = orderBy;
        this.orderType = orderType;

        this.getAllData();
    }

    isAllowed(permissionName): boolean {
        return JSON.parse(localStorage.getItem('userinfo')).permissions.find(obj => obj === permissionName);
    }

    onShowFilterCard() {
        if (this.showFilterCard)
            this.showFilterCard = false;
        else
            this.showFilterCard = true;
    }

    onApplyFilters() {
        this.getAllData();
    }

    onResetFilters() {
        this.filtersForm.reset();
        this.onApplyFilters();
    }

}
