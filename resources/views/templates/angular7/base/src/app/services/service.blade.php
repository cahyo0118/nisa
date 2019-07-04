import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

{{ '@' }}Injectable()
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service {
    constructor() {
    }

    store(item): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.post(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/store`, item);
    }

    update(id, item): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.put(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/${id}/update`, item);
    }

    getOne(id): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/${id}`, {
            params: {
                with: [
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
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

    getAll(page = 1, order_by = 'created_at', order_type = 'desc', filters = {}): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}`, {
            params: {
                filters: filters,
                order_by: order_by,
                order_type: order_type,
                page: page.toString(),
                with: [
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
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

@if(!empty($menu->table))
@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    get{!! ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet(): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/datasets/{!! kebab_case(str_plural($field->relation->table->name)) !!}`);
    }
@endif
@endif
@endforeach
@endif

    getAllByKeyword(keyword: string, page = 1, order_by = 'created_at', order_type = 'desc', filters = {}): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/search/${keyword}`, {
            params: {
                filters: filters,
                order_by: order_by,
                order_type: order_type,
                page: page.toString(),
                with: [
@if(!empty($menu->table))
@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
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
        return httpAuthClient.delete(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/${id}/delete`);
    }

    deleteMultiple(ids: number[]): AxiosPromise{!! '<any>' !!} {
        return httpAuthClient.delete(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/delete/multiple`, {
            params: {
                ids: JSON.stringify(ids)
            }
        });
    }
}
