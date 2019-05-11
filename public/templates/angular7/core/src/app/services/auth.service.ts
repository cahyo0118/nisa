import {Injectable} from '@angular/core';

import {Environment} from '../utils/environment';
import {User} from '../models/user';
import {AxiosPromise} from 'axios';
import {httpAuthClient, httpClient} from '../app.component';

@Injectable()
export class NativeAuthService {
  constructor() {
  }

  loginWithSocialCredentials(data): AxiosPromise<any> {
    return httpClient.post(
      'api/v1/login/social/secret',
      data
    );
  }

  loginSocial(data): AxiosPromise<any> {
    return httpClient.post(
      'api/v1/login/social',
      data
    );
  }

  checkAuth(): AxiosPromise<any> {
    return httpClient.get('api/v1/me/auth');
  }

  login(user: User): AxiosPromise<any> {
    return httpClient.post('oauth/token', {
      grant_type: 'password',
      client_id: Environment.CLIENT_ID,
      client_secret: Environment.CLIENT_SECRET,
      username: user.email,
      password: user.password,
      scope: ''
    });
  }

  refreshToken(): AxiosPromise<any> {
    return httpAuthClient.post('oauth/token', {
      grant_type: 'refresh_token',
      refresh_token: `${JSON.parse(localStorage.getItem('credentials')).refresh_token}`,
      client_id: Environment.CLIENT_ID,
      client_secret: Environment.CLIENT_SECRET,
      scope: ''
    });
  }

  logout(): AxiosPromise<any> {
    return httpAuthClient.post(`api/v1/me/logout`, {});
  }

  register(user: User): AxiosPromise<any> {
    return httpClient.post(`api/v1/register`, user);
  }
}
