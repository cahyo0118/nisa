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

    constructor(
        private formBuilder: FormBuilder,
        private service: {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service,
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

                this.service.store(this.{!! camel_case(str_singular($menu->name)) !!}Form.value)
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
