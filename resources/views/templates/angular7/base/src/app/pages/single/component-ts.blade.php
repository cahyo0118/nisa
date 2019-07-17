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

    constructor(
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
        }
    }

    isAllowed(permissionName): boolean {
        return JSON.parse(localStorage.getItem('userinfo')).permissions.find(obj => obj === permissionName);
    }

}
