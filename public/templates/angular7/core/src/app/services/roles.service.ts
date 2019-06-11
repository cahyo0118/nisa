import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

@Injectable()
export class RolesService {
    constructor() {
    }

    store(item): AxiosPromise<any> {
        return httpAuthClient.post(`api/v1/roles/store`, item);
    }

    update(id, item): AxiosPromise<any> {
        return httpAuthClient.put(`api/v1/roles/${id}/update`, item);
    }

    getOne(id): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/roles/${id}`);
    }

    getAll(page = 1): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/roles`, {
            params: {
                with: ['permissions'],
                page: page.toString()
            }
        });
    }

    getAllByKeyword(keyword: string, page = 1): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/roles/search/${keyword}`, {
            params: {
                page: page.toString()
            }
        });
    }

    delete(id: number): AxiosPromise<any> {
        return httpAuthClient.delete(`api/v1/roles/${id}/delete`);
    }

    deleteMultiple(ids: number[]): Promise<any> {
        return httpAuthClient.delete(`api/v1/roles/delete/multiple`, {
            params: {
                'ids': JSON.stringify(ids)
            }
        });
    }

    updateRolePermissions(roleId, permissions): AxiosPromise<any> {
        return httpAuthClient.put(`api/v1/roles/${roleId}/update-permissions`, {
            'permissions': permissions
        });
    }

}
