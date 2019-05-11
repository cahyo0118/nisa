import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

@Injectable()
export class ProductsService {
  constructor() {
  }

  store(item): AxiosPromise<any> {
    return httpAuthClient.post(`api/v1/products/store`, item);
  }

  update(id, item): AxiosPromise<any> {
    return httpAuthClient.put(`api/v1/products/${id}/update`, item);
  }

  getOne(id): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/products/${id}`);
  }

  getAll(page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/products`, {
      params: {
        page: page.toString()
      }
    });
  }

  getAllByKeyword(keyword: string, page = 1): AxiosPromise<any> {
    return httpAuthClient.get(`api/v1/products/search/${keyword}`, {
      params: {
        page: page.toString()
      }
    });
  }

  delete(id: number): AxiosPromise<any> {
    return httpAuthClient.delete(`api/v1/products/${id}/delete`);
  }

  deleteMultiple(ids: number[]): AxiosPromise<any> {
    return httpAuthClient.delete(`api/v1/products/delete/multiple`, {
      params: {
        ids: JSON.stringify(ids)
      }
    });
  }
}
