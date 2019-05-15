import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {NgxSpinnerService} from 'ngx-spinner';
import {PermissionsService} from '../../services/permissions.service';

@Component({
  selector: 'app-permissions',
  templateUrl: './permissions.component.html',
  styleUrls: ['./permissions.component.css']
})
export class PermissionsComponent implements OnInit {

  searchForm: FormGroup;
  totalPage;
  currentPage = 1;
  lastPage = 1;
  keyword = '';
  items = [];
  searchMode = false;

  constructor(
    private service: PermissionsService,
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
            this.items = responseData.data;
            this.spinner.hide();
          },
          error => {
            this.spinner.hide();
          }
        );
    } else {
      this.service.getAll(page)
        .then(
          response => {
            const responseData = response.data;
            const data = responseData.data;

            this.currentPage = data.current_page;
            this.lastPage = data.last_page;
            this.totalPage = Array(data.last_page).fill(0).map((x, i) => i);
            this.items = data.data;
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
            this.items = responseData.data;
            this.spinner.hide();
          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );
    } else {

      this.getAllData();

    }
  }

}
