import {Component, OnInit} from '@angular/core';
import {NativeAuthService} from '../../services/auth.service';
import {NgxSpinnerService} from 'ngx-spinner';
import {
  AuthService,
  GoogleLoginProvider
} from 'angular-6-social-login';

import {User} from '../../models/user';
import {Router} from '@angular/router';
import {Session} from '../../models/session';
import {MeService} from '../../services/me.service';
import swal from 'sweetalert2';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  token: String = '';
  user: User = new User();
  credentials = JSON.parse(localStorage.getItem('credentials'));

  constructor(
    private socialAuthService: AuthService,
    private authService: NativeAuthService,
    private meService: MeService,
    private spinner: NgxSpinnerService,
    private router: Router
  ) {
  }

  ngOnInit() {
    if (typeof this.credentials !== 'undefined' && this.credentials !== null) {
      this.authService
        .checkAuth()
        .then(response => this.router.navigate(['/dashboard']));
    }
  }

  socialSignIn(socialPlatform: string) {
    let socialPlatformProvider;
    if (socialPlatform === 'google') {
      socialPlatformProvider = GoogleLoginProvider.PROVIDER_ID;
    }

    this.socialAuthService.signIn(socialPlatformProvider).then(userData => {
      console.log(socialPlatform + ' sign in data : ', userData);

      this.spinner.show();
      this.authService.loginSocial(userData)
        .then(
          response => {
            const responseData = response.data;
            const data = JSON.parse(atob(responseData.data));

            if (!data.is_user_exist) {
              this.router.navigate(['/register/social', data.email, 'password'], {
                queryParams: {
                  data: JSON.stringify(data)
                }
              });

              this.authService.loginSocial(data)
                .then(
                  socialResponse => {
                    this.spinner.hide();
                  },
                  error => {

                    const errorData = error.response.data;

                    swal({
                      title: 'Oops',
                      text: errorData.message,
                      type: 'error',
                      confirmButtonText: 'Confirm'
                    });

                    this.spinner.hide();

                  }
                );
            } else {

              this.authService.loginWithSocialCredentials(userData)
                .then(
                  socialCredentialResponse => {
                    const responseData = socialCredentialResponse.data;
                    const socialCredentialData = <Session>responseData.data;

                    localStorage.setItem('credentials', JSON.stringify(socialCredentialData));

                    this.getCurrentUser();
                  },
                  error => {
                    const errorData = error.response.data;

                    swal({
                      title: 'Oops',
                      text: errorData.message,
                      type: 'error',
                      confirmButtonText: 'Confirm'
                    });

                    this.spinner.hide();
                  }
                );
            }

            this.spinner.hide();

          },
          error => {
            swal({
              title: 'Oops',
              text: error.error.message,
              type: 'error',
              confirmButtonText: 'Confirm'
            });
            console.log(error);
            this.spinner.hide();
          }
        );

      console.log('KOK GAK BISA ?');
    });
  }

  login() {
    // Login
    this.spinner.show();
    this.authService.login(this.user)
      .then(
        response => {
          const data = <Session>response.data;
          console.log('DataLogin', data);
          localStorage.setItem('credentials', JSON.stringify(data));
          this.getCurrentUser();
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

  getCurrentUser() {
    // Get Current User
    this.meService.getCurrentUser()
      .then(
        response => {
          const data = response.data;
          localStorage.setItem('userinfo', JSON.stringify(data.data));
          this.spinner.hide();
          this.router.navigate(['/dashboard']);

          console.log('it must be okay, but !');
        },
        error => {
          console.log(error.response);
          const data = error.response.data;
          swal({
            title: 'Oops',
            text: data.message,
            type: 'error',
            confirmButtonText: 'Confirm'
          });

          console.log(error);
          this.spinner.hide();
        }
      );
  }
}
