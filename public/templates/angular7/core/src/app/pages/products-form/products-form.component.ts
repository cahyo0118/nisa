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
import {ProductsService} from '../../services/products.service';
import {BrandsService} from '../../services/brands.service';

@Component({
  selector: 'app-products-form',
  templateUrl: './products-form.component.html',
  styleUrls: ['./products-form.component.css']
})
export class ProductsFormComponent implements OnInit {

  @ViewChild('newCandidateModal', {read: ElementRef})
  newCandidateModal: ElementRef;

  productForm: FormGroup;
  data: any;
  brandsData;
  id: number;
  editMode = false;

  SERVER_URL = Environment.SERVER_URL;

  percentCompleted = 20;

  constructor(
    private formBuilder: FormBuilder,
    private service: ProductsService,
    private brandsService: BrandsService,
    private spinner: NgxSpinnerService,
    private activeRoute: ActivatedRoute,
    private route: Router,
    private sanitizer: DomSanitizer
  ) {
    // Get Params from route
    const routeParams = this.activeRoute.snapshot.params;

    this.id = routeParams.id;

    this.productForm = formBuilder.group({
      name: ['', [Validators.required, Validators.maxLength(150)]],

      picture: [''],

      brand_id: ['', [Validators.required, Validators.maxLength(150)]],
    });
  }

  ngOnInit() {
    if (this.id) {
      this.editMode = true;
      this.service.getOne(this.id)
        .then(
          response => {
            const responseData = response.data;
            this.productForm.patchValue(responseData.data);
            this.data = responseData.data;
            console.log('Response', responseData);
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

    this.brandsService.getAll()
      .then(
        response => {
          const data = response.data.data;
          console.log(data);
          this.brandsData = data.data;
        },
        error => {

        }
      );
  }

  onSubmit() {

    swal({
      title: 'Save Data',
      text: StringUtil.submit_data_message,
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Confirm'
    }).then(result => {
      if (result.value && this.id) {
        this.service.update(this.id, this.productForm.value)
          .then(
            response => {
              const responseData = response.data;

              swal({
                title: 'Yay !',
                text: responseData.data.message,
                type: 'success',
                confirmButtonText: 'Confirm'
              });
              this.route.navigate(['/votes']);
            },
            error => {
              console.log(error);
              swal({
                title: 'Oops',
                text: error.error.message,
                type: 'error',
                confirmButtonText: 'Confirm'
              });
            }
          );
      } else if (result.value) {
        this.service.store(this.productForm.value)
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

              this.route.navigate(['/votes']);
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
          case 'picture':
            // console.log(eventReader.target);
            this.productForm.value.picture = `${eventReader.target.result}`;
            console.log(this.productForm.value);
            break;
        }
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  }

  checkData() {
    console.log(this.productForm.value);
  }
}
