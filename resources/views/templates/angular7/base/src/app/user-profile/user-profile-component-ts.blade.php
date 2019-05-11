import {Component, OnInit, ViewChild} from '{{ '@' }}angular/core';
import {MeService} from '../services/me.service';
import {NgxSpinnerService} from 'ngx-spinner';
import {FormBuilder, FormGroup, Validators} from '{{ '@' }}angular/forms';
import {SwalComponent} from '{{ '@' }}toverux/ngx-sweetalert2';
import swal from 'sweetalert2';
import {HttpEventType} from '{{ '@' }}angular/common/http';
import {Environment} from '../utils/environment';

{{ '@' }}Component({
    selector: 'app-user-profile',
    templateUrl: './user-profile.component.html',
    styleUrls: ['./user-profile.component.css']
})
export class UserProfileComponent implements OnInit {

    {{ '@' }}ViewChild('errorDialog') private errorDialog: SwalComponent;

    user: any;
    userForm: FormGroup;
    apiValidationErrors;
    changePasswordForm: FormGroup;
    SERVER_URL = Environment.SERVER_URL;
    percentCompleted = 0;

    constructor(
        private spinner: NgxSpinnerService,
        private service: MeService,
        private formBuilder: FormBuilder,
    ) {

        this.changePasswordForm = formBuilder.group({
            currentPassword: [
                '',
                [
                    Validators.required
                ]
            ],

            newPassword: [
                '',
                [
                    Validators.required
                ]
            ],
        });

        this.userForm = formBuilder.group({
@foreach($project->tables()->where('name', 'users')->first()->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@else
            {!! $field->name !!}: [
                '',
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
        });

    }

    ngOnInit() {
        this.spinner.show();
        this.getCurrentUser();
    }

    getCurrentUser() {
        this.service.getCurrentUser()
        .then(
            response => {
                localStorage.setItem('userinfo', JSON.stringify(response.data.data));
                this.user = response.data.data;
                this.userForm.patchValue(this.user);
                this.spinner.hide();
            },
            error => {
                this.spinner.hide();
            }
        );
    }

    onUpdateCurrentUser() {
        this.spinner.show();
        this.service.updateCurrentUser(this.userForm.value)
        .then(
            response => {
                this.spinner.hide();
            },
            error => {
                this.spinner.hide();
            }
        );
    }

    onUpdateCurrentUserPhoto(event) {
        this.spinner.show();
        console.log({!! '<File>' !!}event.target.files[0]);

        const config = {
            onUploadProgress: function (progressEvent) {
                this.percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            }.bind(this)
        };

        this.service.updateCurrentUserPhoto({!! '<File>' !!}event.target.files[0], config)
        .then(
            response => {
                console.log(response);
                if (response.data.type === HttpEventType.UploadProgress) {
                    console.log(response);
                } else if (response.data.type === HttpEventType.Response) {
                    console.log(response.data.body);
                    this.getCurrentUser();
                }
                this.getCurrentUser();
                this.percentCompleted = 0;
                // console.log(event)
                this.spinner.hide();
            },
            error => {
                console.log(error);
                this.spinner.hide();
            }
        );
    }

    onUpdateCurrentUserPassword() {
        this.spinner.show();
        this.service.updateCurrentUserPassword(this.changePasswordForm.value)
        .then(
            response => {
                console.log(response);
                this.spinner.hide();
            },
            error => {
                const data = error.response.data;
                swal({
                    title: 'Oops',
                    text: data.message,
                    type: 'error',
                    confirmButtonText: 'Confirm'
                });

                this.spinner.hide();
            }
        );
    }

}
