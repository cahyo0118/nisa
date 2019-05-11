import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {NativeAuthService} from '../../services/auth.service';
import {NgxSpinnerService} from 'ngx-spinner';
import {Router} from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {

  registerForm: FormGroup;

  constructor(
    private spinner: NgxSpinnerService,
    private service: NativeAuthService,
    private formBuilder: FormBuilder,
    private router: Router
  ) {

    this.registerForm = formBuilder.group({
      name: [
        '',
        [
          Validators.required,
          Validators.maxLength(150),
        ]
      ],

      email: [
        '',
        [
          Validators.required,
          Validators.maxLength(150),
        ]
      ],

      password: [
        '',
        [
          Validators.required,
          Validators.maxLength(50),
        ]
      ],

    });

  }

  ngOnInit() {
    this.service.checkAuth()
      .then(
        response => this.router.navigate(['/dashboard'])
      );
  }

  onSubmit() {
    this.spinner.show();
    this.service.register(this.registerForm.value)
      .then(
        response => {
          this.spinner.hide();
        },
        error => {
          this.spinner.hide();
        }
      );

  }

}
