import {Environment} from './../utils/environment';
import {Component, OnInit} from '@angular/core';
import {NativeAuthService} from '../services/auth.service';
import {ActivatedRoute, Router} from '@angular/router';
import swal from 'sweetalert2';
import {NgxSpinnerService} from 'ngx-spinner';

@Component({
  selector: 'app-account-navbar',
  templateUrl: './account-navbar.component.html',
  styleUrls: ['./account-navbar.component.css']
})
export class AccountNavbarComponent implements OnInit {
  user: any;
  SERVER_URL = Environment.SERVER_URL;

  constructor(
    private authService: NativeAuthService,
    private activeRoute: ActivatedRoute,
    private route: Router,
    private spinner: NgxSpinnerService
  ) {
  }

  ngOnInit() {
    this.user = JSON.parse(localStorage.getItem('userinfo'));
  }

  onLogout() {
    this.spinner.show();
    this.authService.logout()
      .then(
        response => {
          swal({
            title: 'Yay !',
            text: 'Successfully Logged out',
            type: 'success',
            confirmButtonText: 'Confirm'
          });
          localStorage.clear();
          this.route.navigate(['/login']);
          this.spinner.hide();
        },
        error => {
          swal({
            title: 'Oops',
            text: error.response.message,
            type: 'error',
            confirmButtonText: 'Confirm'
          });
          this.spinner.hide();
        }
      );
  }
}
