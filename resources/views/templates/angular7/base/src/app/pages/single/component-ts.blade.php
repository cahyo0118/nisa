import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { DatepickerOptions } from 'ng2-datepicker';
import { DatePipe, formatDate } from '@angular/common';
import { Environment } from '../../utils/environment';
import { NgxSpinnerService } from 'ngx-spinner';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer } from '@angular/platform-browser';
import { StringUtil } from '../../utils/string.util';
import swal from 'sweetalert2';
import { {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service } from '../../services/{!! str_replace('_', '-', str_plural($menu->name)) !!}.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
import { {!! ucfirst(camel_case(str_plural($relation->table->name))) !!}Service } from '../../services/{!! str_replace('_', '-', str_plural($relation->table->name)) !!}.service';
@endif
@endforeach
@endif

{{ '@' }}Component({
    selector: 'app-{!! str_replace('_', '-', str_plural($menu->name)) !!}-single',
    templateUrl: './{!! str_replace('_', '-', str_plural($menu->name)) !!}-single.component.html',
    styleUrls: ['./{!! str_replace('_', '-', str_plural($menu->name)) !!}-single.component.css']
})
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}SingleComponent implements OnInit {

    data: any;
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    {!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->table->name)) !!}Data: any;
@endif
@endif
@endforeach
@endif
    id: number;
    SERVER_URL = Environment.SERVER_URL;

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchMode = false;
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchForm: FormGroup;

    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataCounts = 0;
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataEntryFrom = 0;
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataEntryTo = 0;
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data: any = [];
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}OrderBy = 'created_at';
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}OrderType = 'desc';
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}TotalPage;
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage = 1;
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}LastPage = 1;
    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Keyword = '';

    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data: any;
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}OrderBy = 'created_at';
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}OrderType = 'desc';
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage;
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = 1;
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = 1;
    search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Keyword = '';
@endif
@endforeach
@endif

    constructor(
        private formBuilder: FormBuilder,
        private service: {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service,
@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
        private {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Service: {!! ucfirst(camel_case(str_plural($relation->table->name))) !!}Service,
@endif
@endforeach
@endif
        private spinner: NgxSpinnerService,
        private activeRoute: ActivatedRoute,
        private route: Router,
        private sanitizer: DomSanitizer,
        private env: Environment
    ) {
        // Get Params from route
        const routeParams = this.activeRoute.snapshot.params;

        this.id = routeParams.id;

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchForm = formBuilder.group({
            keyword: [
                '',
                [
                    Validators.maxLength(50),
                    Validators.required,
                ]
            ],
        });
@endif
@endforeach
@endif

    }

    ngOnInit() {
        if (this.id) {
            this.service.getOne(this.id)
                .then(
                    response => {
                        const data = response.data;
                        this.data = data.data;
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $file_field)
@if($file_field->input_type == 'image' || $file_field->input_type == 'file')
                        this.data.{{ $file_field->name }} = this.data.{{ $file_field->name }} !== null ? Environment.SERVER_URL + this.data.{{ $file_field->name }} : null;
@endif
@endforeach
@endif
                    },
                    error => {
                        swal({
                            title: 'Oops',
                            text: error.error.message,
                            type: 'error',
                            confirmButtonText: 'Confirm'
                        });
                        console.log(error);
                    }
                );

            this.getAllRelationsData();

        }
    }

    getAllRelationsData(page = 1) {

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
        this.getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data();

        this.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data();

@endif
@endforeach
@endif

    }

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
    getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(page = 1) {
        if (this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchMode) {
            this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Service.getAllByKeyword(this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchForm.value.keyword, page)
                .then(
                    response => {
                        this.spinner.hide();
                        const data = response.data.data;

                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = data.current_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = data.last_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data = data.data;
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        } else {
            this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Service.getAll(page)
                .then(
                    response => {
                        this.spinner.hide();
                        const data = response.data.data;

                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = data.current_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = data.last_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data = data.data;
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        }
    }

    getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(page = 1) {
        this.service.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Relation(this.id, page)
            .then(
                response => {
                    this.spinner.hide();
                    const data = response.data.data;

                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataCounts = data.total;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataEntryFrom = data.from;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataEntryTo = data.to;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage = data.current_page;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}LastPage = data.last_page;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data = data.data;
                },
                error => {
                    this.spinner.hide();
                }
            );
    }

    onSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}() {

        this.spinner.show();

        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchMode = this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchForm.value.keyword.length ? true : false;

        console.log('SEARCH_MODE', this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchMode);
        if (this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchMode) {
            this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Service.getAllByKeyword(this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}SearchForm.value.keyword, 1)
                .then(
                    response => {
                        this.spinner.hide();
                        const data = response.data.data;

                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = data.current_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = data.last_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data = data.data;
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        } else {
            this.getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data();
        }
    }

    onSelect{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->relation_name)) !!}(item) {
        this.service.add{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}(this.id, item)
            .then(
                response => {
                    const data = response.data;

                    this.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage);

                    swal({
                        title: 'Yay !',
                        text: data.message,
                        type: 'success',
                        toast: true,
                        position: "bottom-end",
                        timer: 3000,
                        confirmButtonText: 'Confirm'
                    });
                },
                error => {
                    const data = error.response.data;
                    swal({
                        title: 'Oops',
                        text: data.message,
                        type: 'error',
                        toast: true,
                        position: "bottom-end",
                        timer: 3000,
                        confirmButtonText: 'Confirm'
                    });
                }
            );
    }

    onUnSelect{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->relation_name)) !!}(item) {
        this.service.remove{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}(this.id, item)
            .then(
                response => {
                    const data = response.data;

                    this.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage);

                    swal({
                        title: 'Yay !',
                        text: data.message,
                        type: 'success',
                        toast: true,
                        position: "bottom-end",
                        timer: 3000,
                        confirmButtonText: 'Confirm'
                    });
                },
                error => {
                    const data = error.response.data;
                    swal({
                        title: 'Oops',
                        text: data.message,
                        type: 'error',
                        toast: true,
                        position: "bottom-end",
                        timer: 3000,
                        confirmButtonText: 'Confirm'
                    });
                }
            );
    }

@endif
@endforeach
@endif

    isAllowed(permissionName): boolean {
        return JSON.parse(localStorage.getItem('userinfo')).permissions.find(obj => obj === permissionName);
    }

}
