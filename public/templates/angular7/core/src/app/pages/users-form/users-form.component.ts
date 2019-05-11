import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {DatepickerOptions} from 'ng2-datepicker';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {DatePipe, formatDate} from '@angular/common';
import {Environment} from '../../utils/environment';
import {NgxSpinnerService} from 'ngx-spinner';
import {ActivatedRoute, Router} from '@angular/router';
import {DomSanitizer} from '@angular/platform-browser';
import {StringUtil} from '../../utils/string.util';
import swal from 'sweetalert2';
import {UsersService} from '../../services/users.service';

@Component({
  selector: 'app-users-form',
  templateUrl: './users-form.component.html',
  styleUrls: ['./users-form.component.css']
})
export class UsersFormComponent implements OnInit {

  @ViewChild('newCandidateModal', {read: ElementRef})
  newCandidateModal: ElementRef;

  userForm: FormGroup;
  apiValidationErrors;
  data: any;
  id: number;
  editMode = false;

  SERVER_URL = Environment.SERVER_URL;

  constructor(
    private formBuilder: FormBuilder,
    private service: UsersService,
    private spinner: NgxSpinnerService,
    private activeRoute: ActivatedRoute,
    private route: Router,
    private sanitizer: DomSanitizer,
    private env: Environment
  ) {
    // Get Params from route
    const routeParams = this.activeRoute.snapshot.params;

    this.id = routeParams.id;

    this.userForm = formBuilder.group({
      name: ['', [Validators.maxLength(50)]],
      email: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(150), Validators.email]],
      address: ['', [Validators.required, Validators.maxLength(150)]],
      password: ['', [Validators.maxLength(50)]],
      photo: ['', [Validators.required]],
    });
  }

  ngOnInit() {
    if (this.id) {
      this.editMode = true;
      this.service.getOne(this.id)
        .then(
          response => {
            const data = response.data;
            this.userForm.patchValue(data.data);
            this.data = data.data;

            this.userForm.value.photo = this.userForm.value.photo !== null ? Environment.SERVER_URL + this.userForm.value.photo : null;
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

  onSubmit() {

    swal({
      title: 'Save Data',
      text: StringUtil.submit_data_message,
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Confirm'
    }).then(result => {
      if (result.value && this.editMode) {

        this.service.update(this.id, this.userForm.value)
          .then(
            response => {
              const data = response.data;
              swal({
                title: 'Yay !',
                text: data.message,
                type: 'success',
                confirmButtonText: 'Confirm'
              });

              this.route.navigate(['/users']);

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

        this.service.store(this.userForm.value)
          .then(
            response => {
              // if (typeof this.image !== 'undefined') {
              //   this.uploadVoteImage(this.image, response.data.id);
              // }

              swal({
                title: 'Yay !',
                text: response.data.message,
                type: 'success',
                confirmButtonText: 'Confirm'
              });

              this.route.navigate(['/users']);
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
          case 'photo':
            // console.log(eventReader.target);
            this.userForm.value.photo = `${eventReader.target.result}`;
            break;
        }
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  }

  onRemovePicture(formControlName) {
    switch (formControlName) {
      case 'photo':
        this.userForm.value.photo = null;
        break;
    }
  }

  checkData() {
    console.log(this.userForm.value);
    console.log('EmailValidation', this.userForm.controls.name.errors);
  }
}
