import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { DatepickerOptions } from 'ng2-datepicker';
import { DatePipe, formatDate } from '@angular/common';
import { Environment } from '../../utils/environment';
import { NgxSpinnerService } from 'ngx-spinner';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer } from '@angular/platform-browser';
import { StringUtil } from '../../utils/string.util';
import swal from 'sweetalert2';
import { {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service } from '../../services/{!! kebab_case(str_plural($menu->name)) !!}.service';
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
import { {!! ucfirst(camel_case(str_plural($menu->table->name))) !!}TableService } from '../../services/tables/{!! kebab_case(str_plural($menu->table->name)) !!}-table.service';
@endif
@endif
@endforeach
@endif

{{ '@' }}Component({
    selector: 'app-{!! kebab_case(str_plural($menu->name)) !!}-single',
    templateUrl: './{!! kebab_case(str_plural($menu->name)) !!}-single.component.html',
    styleUrls: ['./{!! kebab_case(str_plural($menu->name)) !!}-single.component.css']
})
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}SingleComponent implements OnInit {

    data: any;
@if(!empty($menu->table))
@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    {!! camel_case(str_plural($field->relation->table->name)) !!}Data: any;
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
@foreach($menu->table->fields as $file_field)
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
