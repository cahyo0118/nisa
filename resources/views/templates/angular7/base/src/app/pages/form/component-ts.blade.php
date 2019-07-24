@php
$relations = [];
if(!empty($menu->table)) {
    foreach($menu->table->fields as $field) {
        if(!empty($field->relation) && $field->relation->relation_type == "belongsto") {
            array_push($relations, $field->relation->table->name);
        }
    }
    $relations = array_unique($relations);
}
@endphp
import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { DatepickerOptions } from 'ng2-datepicker';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { DatePipe, formatDate } from '@angular/common';
import { Environment } from '../../utils/environment';
import { NgxSpinnerService } from 'ngx-spinner';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer } from '@angular/platform-browser';
import { StringUtil } from '../../utils/string.util';
import swal from 'sweetalert2';
import { {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service } from '../../services/{!! str_replace('_', '-', str_plural($menu->name)) !!}.service';

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
import { {!! ucfirst(camel_case(str_plural($relation->table->name))) !!}Service } from '../../services/{!! str_replace('_', '-', str_plural($relation->table->name)) !!}.service';
@endif
@endforeach
@endif

{{ '@' }}Component({
    selector: 'app-{!! str_replace('_', '-', str_plural($menu->name)) !!}-form',
    templateUrl: './{!! str_replace('_', '-', str_plural($menu->name)) !!}-form.component.html',
    styleUrls: ['./{!! str_replace('_', '-', str_plural($menu->name)) !!}-form.component.css']
})
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}FormComponent implements OnInit {

    {!! camel_case(str_singular($menu->name)) !!}Form: FormGroup;
    apiValidationErrors;
    data: any;
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    {!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->relation_name)) !!}Data: any;
@endif
@endif
@endforeach
@endif
    id: number;
    editMode = false;

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

        this.{!! camel_case(str_singular($menu->name)) !!}Form = formBuilder.group({
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
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
            this.editMode = true;
            this.service.getOne(this.id)
                .then(
                    response => {
                        const data = response.data;
                        this.{!! camel_case(str_singular($menu->name)) !!}Form.patchValue(data.data);
                        this.data = data.data;
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $file_field)
@if($file_field->input_type == 'image' || $file_field->input_type == 'file')
                        this.{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $file_field->name }} = this.{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $file_field->name }} !== null ? Environment.SERVER_URL + this.{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $file_field->name }} : null;
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
        }

        this.getAllDataSets();

        this.getAllRelationsData();
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
                    this.{!! !empty($field->relation->relation_name) ? camel_case(str_plural($field->relation->relation_name)) : camel_case(str_plural($field->relation->relation_name)) !!}Data = data.data;
                },
                error => {
                }
            );

@endif
@endif
@endforeach
@endif
    }

    getAllRelationsData(page = 1) {

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
        this.getAllSearch{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data();

        if (this.editMode) {
            this.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data();
        }

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
                        const data = response.data.data;

                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = data.current_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = data.last_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data = data.data;
                        this.spinner.hide();
                    },
                    error => {
                        this.spinner.hide();
                    }
                );
        } else {
            this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Service.getAll(page)
                .then(
                    response => {
                        const data = response.data.data;

                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = data.current_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = data.last_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data = data.data;
                        this.spinner.hide();
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
                    const data = response.data.data;

                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataCounts = data.total;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataEntryFrom = data.from;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}DataEntryTo = data.to;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage = data.current_page;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}LastPage = data.last_page;
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data = data.data;
                    this.spinner.hide();
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
                        const data = response.data.data;

                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}CurrentPage = data.current_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}LastPage = data.last_page;
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}TotalPage = Array(data.last_page).fill(0).map((x, i) => i);
                        this.search{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data = data.data;
                        this.spinner.hide();
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
        if (this.editMode) {
            this.service.add{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}(this.id, item)
                .then(
                    response => {
                        const data = response.data;

                        this.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage);

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

        } else {
            if (!this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data.find(x => x.id === item.id)) {
                this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data.push(item);
            }
        }
    }

    onUnSelect{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->relation_name)) !!}(item) {
        if (this.editMode) {
            this.service.remove{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}(this.id, item)
                .then(
                    response => {
                        const data = response.data;

                        this.getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Data(this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}CurrentPage);

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

        } else {
            this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data.find((childObject, childIndex) => {
                if (childObject.id === item.id) {
                    this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data.splice(childIndex, 1);
                    return childObject;
                }
            });
        }
    }

@endif
@endforeach
@endif
    onSubmit() {

        swal({
            title: 'Save Data',
            text: StringUtil.submit_data_message,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm'
        }).then(result => {
            if (result.value && this.editMode) {

                this.service.update(this.id, this.{!! camel_case(str_singular($menu->name)) !!}Form.value)
                    .then(
                        response => {
                            const data = response.data;
                            swal({
                                title: 'Yay !',
                                text: data.message,
                                type: 'success',
                                confirmButtonText: 'Confirm'
                            });

                            this.route.navigate(['/{!! kebab_case(str_plural($menu->name)) !!}']);

                        },
                        error => {
                            const data = error.response.data;
                            swal({
                                title: 'Oops',
                                text: data.message,
                                type: 'error',
                                confirmButtonText: 'Confirm'
                            });

                            this.apiValidationErrors = data.data;
                        }
                    );

            } else if (result.value) {

                this.service.store(this.{!! camel_case(str_singular($menu->name)) !!}Form.value,
                    [
@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
                        {
                            'relationName': '{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}',
                            'data': this.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data,
                        }
@endif
@endforeach
@endif
                    ])
                    .then(
                        response => {

                            swal({
                                title: 'Yay !',
                                text: response.data.message,
                                type: 'success',
                                confirmButtonText: 'Confirm'
                            });

                            this.route.navigate(['/{!! kebab_case(str_plural($menu->name)) !!}']);
                        },
                        error => {
                            swal({
                                title: 'Oops',
                                text: error.response.data.message,
                                type: 'error',
                                confirmButtonText: 'Confirm'
                            });
                        }
                    );

            }
        });
    }

    onUpdatePicture(event, formControlName) {
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (eventReader: any) => {
                switch (formControlName) {
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $file_field)
@if($file_field->input_type == 'image' || $file_field->input_type == 'file')
                    case '{{ $file_field->name }}':
                        this.{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $file_field->name }} = `${eventReader.target.result}`;
                        break;
@endif
@endforeach
@endif
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    onRemovePicture(formControlName) {
        switch (formControlName) {
@if(!empty($menu->table))
@foreach($menu->table->fields()->orderBy('order')->get() as $file_field)
@if($file_field->input_type == 'image' || $file_field->input_type == 'file')
            case '{{ $file_field->name }}':
                this.{!! camel_case(str_singular($menu->name)) !!}Form.value.{{ $file_field->name }} = null;
                break;
@endif
@endforeach
@endif
        }
    }

    checkData() {
        console.log(this.{!! camel_case(str_singular($menu->name)) !!}Form.value);
        console.log('EmailValidation', this.{!! camel_case(str_singular($menu->name)) !!}Form.controls.name.errors);
    }
}
