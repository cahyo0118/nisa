import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Environment} from '../../utils/environment';
import {NgxSpinnerService} from 'ngx-spinner';
import {ActivatedRoute, Router} from '@angular/router';
import {StringUtil} from '../../utils/string.util';
import swal from 'sweetalert2';
import {RolesService} from '../../services/roles.service';

@Component({
  selector: 'app-roles-form',
  templateUrl: './roles-form.component.html',
  styleUrls: ['./roles-form.component.css']
})
export class RolesFormComponent implements OnInit {

  @ViewChild('newCandidateModal', {read: ElementRef})
  newCandidateModal: ElementRef;

  roleForm: FormGroup;
  apiValidationErrors;
  data: any;
  id: number;
  editMode = false;

  constructor(
    private formBuilder: FormBuilder,
    private service: RolesService,
    private spinner: NgxSpinnerService,
    private activeRoute: ActivatedRoute,
    private route: Router,
  ) {
    // Get Params from route
    const routeParams = this.activeRoute.snapshot.params;

    this.id = routeParams.id;

    this.roleForm = formBuilder.group({
      name: ['', [Validators.required, Validators.maxLength(100)]],
      description: ['', [Validators.required, Validators.maxLength(100)]],
    });
  }

  ngOnInit() {
    if (this.id) {
      this.editMode = true;
      this.service.getOne(this.id)
        .then(
          response => {
            const data = response.data;
            this.roleForm.patchValue(data.data);
            this.data = data.data;

            this.roleForm.value.photo = this.roleForm.value.photo !== null ? Environment.SERVER_URL + this.roleForm.value.photo : null;
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

        this.service.update(this.id, this.roleForm.value)
          .then(
            response => {
              const data = response.data;
              swal({
                title: 'Yay !',
                text: data.message,
                type: 'success',
                confirmButtonText: 'Confirm'
              });

              this.route.navigate(['/roles']);

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

        this.service.store(this.roleForm.value)
          .then(
            response => {

              swal({
                title: 'Yay !',
                text: response.data.message,
                type: 'success',
                confirmButtonText: 'Confirm'
              });

              this.route.navigate(['/roles']);
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

  checkData() {
    console.log(this.roleForm.value);
    console.log('EmailValidation', this.roleForm.controls.name.errors);
  }
}
