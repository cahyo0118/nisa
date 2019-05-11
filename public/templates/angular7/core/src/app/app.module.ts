// Modules
import { BrowserModule } from '@angular/platform-browser';
import { NgModule, LOCALE_ID } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SweetAlert2Module } from '@toverux/ngx-sweetalert2';
import { NgDatepickerModule } from 'ng2-datepicker';
import { OwlDateTimeModule, OwlNativeDateTimeModule } from 'ng-pick-datetime';
import { NgxSpinnerModule } from 'ngx-spinner';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AmazingTimePickerModule } from 'amazing-time-picker';
import {
    SocialLoginModule,
    AuthServiceConfig,
    GoogleLoginProvider,
} from 'angular-6-social-login';

// Services
import { AuthGuardService } from './guards/auth-guard.service';
import { NativeAuthService } from './services/auth.service';
import { MeService } from './services/me.service';

import { UsersService } from './services/users.service';
import { PermissionsService } from './services/permissions.service';
import { RolesService } from './services/roles.service';

import { ProductsService } from './services/products.service';
import { BrandsService } from './services/brands.service';

// Components
import { AppComponent } from './app.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { SidenavComponent } from './sidenav/sidenav.component';
import { AccountNavbarComponent } from './account-navbar/account-navbar.component';
import { UserProfileComponent } from './user-profile/user-profile.component';
import { AccountNavbarMobileComponent } from './account-navbar-mobile/account-navbar-mobile.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { FooterComponent } from './footer/footer.component';
import { LoginComponent } from './auth/login/login.component';
import { RegisterComponent } from './auth/register/register.component';
import { LoadingSpinnerComponent } from './ui/loading-spinner/loading-spinner.component';
import { SweetalertComponent } from './ui/sweetalert/sweetalert.component';
import { SocialLoginPasswordComponent } from './social-login-password/social-login-password.component';
import { LandingComponent } from './landing/landing.component';
import { FooterLandingComponent } from './footer-landing/footer-landing.component';
import { ProductsComponent } from './pages/products/products.component';
import { ProductsFormComponent } from './pages/products-form/products-form.component';
import { PermissionsComponent } from './pages/permissions/permissions.component';
import { RolesComponent } from './pages/roles/roles.component';
import { UsersComponent } from './pages/users/users.component';
import { UsersFormComponent } from './pages/users-form/users-form.component';
import { FormErrorMessageComponent } from './utils/form-error-message/form-error-message.component';
import { RolesFormComponent } from './pages/roles-form/roles-form.component';

// Utils
import { Environment } from './utils/environment';

// Locales
import { registerLocaleData } from '@angular/common';
import localeID from '@angular/common/locales/id';

registerLocaleData(localeID);

// Routes
const routes: Routes = [
    // Auth routes
    {path: '', component: LandingComponent},
    {path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuardService]},
    {path: 'permissions', component: PermissionsComponent, canActivate: [AuthGuardService]},
    {path: 'roles', component: RolesComponent, canActivate: [AuthGuardService]},
    {path: 'roles/create', component: RolesFormComponent, canActivate: [AuthGuardService]},
    {path: 'roles/:id/update', component: RolesFormComponent, canActivate: [AuthGuardService]},
    {path: 'users', component: UsersComponent, canActivate: [AuthGuardService]},
    {path: 'users/create', component: UsersFormComponent, canActivate: [AuthGuardService]},
    {path: 'users/:id/update', component: UsersFormComponent, canActivate: [AuthGuardService]},
    {path: 'products', component: ProductsComponent, canActivate: [AuthGuardService]},
    {path: 'products/create', component: ProductsFormComponent, canActivate: [AuthGuardService]},
    {path: 'products/:id/update', component: ProductsFormComponent, canActivate: [AuthGuardService]},
    {path: 'profile', component: UserProfileComponent, canActivate: [AuthGuardService]},
    // Guest routes
    {path: 'login', component: LoginComponent},
    {path: 'register', component: RegisterComponent},
    {path: 'register/social/:email/password', component: SocialLoginPasswordComponent},
];

// Configs
export function getAuthServiceConfigs() {
    const config = new AuthServiceConfig([
        {
            id: GoogleLoginProvider.PROVIDER_ID,
            provider: new GoogleLoginProvider('641265698803-t38604v4db2udg6brgf2dr709kp4fq89.apps.googleusercontent.com')
        }
    ]);
    return config;
}

@NgModule({
    declarations: [
        AppComponent,
        DashboardComponent,
        SidenavComponent,
        AccountNavbarComponent,
        UserProfileComponent,
        AccountNavbarMobileComponent,
        SidebarComponent,
        FooterComponent,
        FormErrorMessageComponent,
        LoginComponent,
        RegisterComponent,
        LoadingSpinnerComponent,
        SweetalertComponent,
        SocialLoginPasswordComponent,
        LandingComponent,
        FooterLandingComponent,
        UsersComponent,
        UsersFormComponent,
        PermissionsComponent,
        RolesComponent,
        RolesFormComponent,
        ProductsComponent,
        ProductsFormComponent,
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        RouterModule.forRoot(routes),
        SweetAlert2Module.forRoot(),
        FormsModule,
        ReactiveFormsModule.withConfig({warnOnNgModelWithFormControl: 'never'}),
        NgxSpinnerModule,
        InfiniteScrollModule,
        SocialLoginModule,
        NgDatepickerModule,
        OwlDateTimeModule,
        OwlNativeDateTimeModule,
        AmazingTimePickerModule
    ],
    providers: [
        NativeAuthService,
        AuthGuardService,
        MeService,
        Environment,
        RolesService,
        PermissionsService,
        UsersService,
        ProductsService,
        BrandsService,
        {
            provide: AuthServiceConfig,
            useFactory: getAuthServiceConfigs
        },
        {provide: LOCALE_ID, useValue: navigator.language}
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
