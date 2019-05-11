import {MeService} from './../services/me.service';
import {Session} from './../models/session';
import {NgxSpinnerService} from 'ngx-spinner';
import {Component, OnInit} from '@angular/core';
import {NativeAuthService} from '../services/auth.service';
import {Router, ActivatedRoute} from '@angular/router';

@Component({
  selector: 'app-social-login-password',
  templateUrl: './social-login-password.component.html',
  styleUrls: ['./social-login-password.component.css']
})
export class SocialLoginPasswordComponent implements OnInit {
  userData;

  constructor(
    private authService: NativeAuthService,
    private meService: MeService,
    private router: Router,
    private route: ActivatedRoute,
    private spinner: NgxSpinnerService,
  ) {
    this.route.queryParams.subscribe(params => {
      this.userData = JSON.parse(params['data']);
      console.log(this.userData);
    });
  }

  ngOnInit() {
  }

  login() {
    this.authService.loginSocial(this.userData)
      .then(
        response => {
          this.spinner.hide();
          this.loginToDashboard();
        },
        error => {
          console.log(error);
          this.spinner.hide();
        }
      );
  }

  loginToDashboard() {
    // Login
    this.spinner.show();
    // this.authService.login(this.userData).subscribe(
    //   response => {
    //     const data = <Session>response;
    //     localStorage.setItem('credentials', JSON.stringify(data));
    //
    //     this.getCurrentUser();
    //
    //     console.log('Response = ' + response);
    //   },
    //   error => {
    //     this.spinner.hide();
    //     console.log('Error = ' + error);
    //   }
    // );
  }

  getCurrentUser() {
    // Get Current User
    this.meService.getCurrentUser()
      .then(
        response => {
          localStorage.setItem('userinfo', JSON.stringify(response.data));
          this.spinner.hide();
          this.router.navigate(['/dashboard']);
        },
        error => {
          this.spinner.hide();
        }
      );
  }
}
