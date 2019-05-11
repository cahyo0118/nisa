import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

@Injectable()
export class BrandsService {
  constructor() {
  }

  store(item): AxiosPromise<any> {
    return httpAuthClient.post(`api/v1/brands/store`, item);
  }

  update(id, item): AxiosPromise<any> {
    return httpAuthClient.put(`api/v1/brands/${id}/update`, item);
  }

  getOne(id): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/brands/${id}`);
  }

  getAll(page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/brands`, {
      params: {
        page: page.toString()
      }
    });
  }

  getAllByKeyword(keyword: string, page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/brands/search/${keyword}`, {
      params: {
        page: page.toString()
      }
    });
  }

  delete(id: number): AxiosPromise<any> {
    return httpAuthClient.delete(`api/v1/brands/${id}/delete`);
  }

  deleteMultiple(ids: number[]): AxiosPromise<any> {
    return httpAuthClient.delete(`api/v1/products/delete/multiple`, {
      params: {
        ids: JSON.stringify(ids)
      }
    });
  }
}
