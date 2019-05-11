import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

@Injectable()
export class PermissionsService {
  constructor() {
  }

  getAll(page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/permissions`, {
      params: {
        page: page.toString()
      }
    });
  }

  getAllNoPagination(page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/permissions`, {
      params: {
        page: page.toString(),
        all: true
      }
    });
  }

  getAllByKeyword(keyword: string, page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/permissions/search/${keyword}`, {
      params: {
        page: page.toString()
      }
    });
  }
}
