import {Injectable} from '@angular/core';

import {User} from '../models/user';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

@Injectable()
export class MeService {

  constructor() {
  }

  getCurrentUser(): AxiosPromise<any> {
    return httpAuthClient.get('api/v1/me');
  }

  updateCurrentUser(user: User): AxiosPromise<any> {
    return httpAuthClient.put('api/v1/me/update', user);
  }

  updateCurrentUserPassword(item: any): AxiosPromise<any> {
    return httpAuthClient.put('api/v1/me/update/password', {
      current_password: item.currentPassword,
      new_password: item.newPassword
    });
  }

  updateCurrentUserPhoto(photo: File, config: any): AxiosPromise<any> {
    const formData = new FormData();
    formData.append('photo', photo);

    return httpAuthClient.post('api/v1/me/update/photo', formData, config);
  }
}
