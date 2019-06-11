import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

@Injectable()
export class UsersService {

    constructor() {
    }

    store(item): AxiosPromise<any> {
        return httpAuthClient.post(`api/v1/users/store`, item);
    }

    update(id, item): AxiosPromise<any> {
        return httpAuthClient.put(`api/v1/users/${id}/update`, item);
    }

    getOne(id): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/users/${id}`);
    }

    getAll(page = 1): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/users`, {
            params: {
                'page': page.toString()
            }
        });
    }

    getAllByKeyword(keyword: string, page = 1): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/users/search/${keyword}`, {
            params: {
                'page': page.toString()
            }
        });
    }

    delete(id: number): AxiosPromise<any> {
        return httpAuthClient.delete(`api/v1/users/${id}/delete`);
    }

    deleteMultiple(ids: number[]): Promise<any> {
        return httpAuthClient.delete(`api/v1/users/delete/multiple`, {
            params: {
                'ids': JSON.stringify(ids)
            }
        });
    }

    addRole(userId, roleId): AxiosPromise<any> {
        return httpAuthClient.post(`api/v1/users/${userId}/add-role/${roleId}`);
    }

    deleteRole(userId, roleId): AxiosPromise<any> {
        return httpAuthClient.delete(`api/v1/users/${userId}/delete-role/${roleId}`);
    }

}
