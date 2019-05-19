import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../../app.component';

{{ '@' }}Injectable()
export class {!! ucfirst(camel_case(str_plural($table->name))) !!}TableService {
    constructor() {
    }

    store(item): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.post(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/store`, item);
    }

    update(id, item): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.put(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/${id}/update`, item);
    }

    getOne(id): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/${id}`);
    }

    getAll(page = 1): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($table->name)) !!}`, {
            params: {
                page: page.toString(),
                with: [
@if(!empty($table))
@foreach($table->fields()->where('searchable', true)->get() as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                    '{!! str_singular($field->relation->table->name) !!}',
@endif
@endif
@endforeach
@endif
                ]
            }
        });
    }

    getAllDataSets(): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/datasets`);
    }

    getAllByKeyword(keyword: string, page = 1): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/search/${keyword}`, {
            params: {
                page: page.toString(),
                with: [
@if(!empty($table))
@foreach($table->fields()->where('searchable', true)->get() as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                    '{!! str_singular($field->relation->table->name) !!}',
@endif
@endif
@endforeach
@endif
                ]
            },
        });
    }

    delete(id: number): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.delete(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/${id}/delete`);
    }

    deleteMultiple(ids: number[]): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.delete(`api/v1/{!! kebab_case(str_plural($table->name)) !!}/delete/multiple`, {
            params: {
                ids: JSON.stringify(ids)
            }
        });
    }
}
