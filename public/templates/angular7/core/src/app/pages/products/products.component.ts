import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {NgxSpinnerService} from 'ngx-spinner';
import {StringUtil} from '../../utils/string.util';
import swal from 'sweetalert2';
import {ProductsService} from '../../services/products.service';

@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css']
})
export class ProductsComponent implements OnInit {

  searchForm: FormGroup;
  totalPage;
  currentPage = 1;
  lastPage = 1;
  keyword = '';
  items = [];
  searchMode = false;

  constructor(
    private service: ProductsService,
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

  }

  ngOnInit() {

    this.spinner.show();

    this.getAllData();
  }

  getAllData(page = 1) {
    if (this.searchMode) {
      this.service.getAllByKeyword(this.searchForm.value.keyword, page)
        .then(
          response => {
            const responseData = response.data;

            this.currentPage = responseData.current_page;
            this.lastPage = responseData.last_page;
            this.totalPage = Array(responseData.last_page).fill(0).map((x, i) => i);
            this.items = responseData.data.data;
            this.spinner.hide();
          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );
    } else {
      this.service.getAll(page)
        .then(
          response => {
            const responseData = response.data;

            this.currentPage = responseData.current_page;
            this.lastPage = responseData.last_page;
            this.totalPage = Array(responseData.last_page).fill(0).map((x, i) => i);
            this.items = responseData.data.data;
            this.spinner.hide();
          },
          error => {
            this.spinner.hide();
          }
        );
    }
  }

  onSearch() {

    this.spinner.show();

    this.searchMode = this.searchForm.value.keyword.length ? true : false;

    if (this.searchMode) {
      this.service.getAllByKeyword(this.searchForm.value.keyword)
        .then(
          response => {
            const responseData = response.data;

            this.currentPage = responseData.current_page;
            this.lastPage = responseData.last_page;
            this.totalPage = Array(responseData.last_page).fill(0).map((x, i) => i);
            this.items = responseData.data.data;
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

          const responseData = response.data;
          this.getAllData(this.currentPage);

          swal({
            title: 'Yay !',
            text: responseData.message,
            type: 'success',
            confirmButtonText: 'Confirm'
          });
        },
        error => {
          const errorData = error.response.data;

          swal({
            title: 'Oops',
            text: errorData.message,
            type: 'error',
            confirmButtonText: 'Confirm'
          });
        }
      );
  }

}
