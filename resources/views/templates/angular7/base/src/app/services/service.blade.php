import {Injectable} from '@angular/core';
import {AxiosPromise} from 'axios';
import {httpAuthClient} from '../app.component';

{{ '@' }}Injectable()
export class {!! ucfirst(camel_case(str_plural($menu->name))) !!}Service {
    constructor() {
    }

    store(item, manyRelations: any[] = []): AxiosPromise{!! '<any>' !!} {

        manyRelations.forEach(manyRelation => {
@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
            if (manyRelation.relationName === '{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}') {
                const {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data = [];

                manyRelation.data.forEach(data => {
                    {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data.push(data.id);
                });

                item.{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!} = {!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}Data;
            }
@endif
@endforeach
@endif
        });

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
@foreach($menu->table->fields as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                    '{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}',
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
                    '{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : snake_case(str_singular($field->relation->table->name)) !!}',
@endif
@endif
@endforeach
@endif
                ]
            }
        });
    }

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
    getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}Relation(id, page = 1, order_by = 'created_at', order_type = 'desc', filters = {}): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/${id}/relations/{!! !empty($relation->relation_name) ? kebab_case(str_plural($relation->relation_name)) : kebab_case(str_plural($relation->table->name)) !!}`, {
            params: {
                filters: filters,
                order_by: order_by,
                order_type: order_type,
                page: page.toString(),
            }
        });
    }

    add{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(id, item): AxiosPromise<any> {
        return httpAuthClient.post(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/${id}/relations/{!! !empty($relation->relation_name) ? kebab_case(str_plural($relation->relation_name)) : kebab_case(str_plural($relation->table->name)) !!}/store`, item);
    }

    remove{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(id, item): AxiosPromise<any> {
        return httpAuthClient.delete(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/${id}/relations/{!! !empty($relation->relation_name) ? kebab_case(str_plural($relation->relation_name)) : kebab_case(str_plural($relation->table->name)) !!}/${item.id}/remove`, item);
    }

@endif
@endforeach
@endif

@if(!empty($menu->table))
@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet(): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/datasets/{!! !empty($field->relation->relation_name) ? kebab_case(str_plural($field->relation->relation_name)) : kebab_case(str_plural($field->relation->table->name)) !!}`);
    }

@php
$field_reference = null;
$reference = DB::table('menu_load_references')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
if (!empty($reference)) {
    $field_reference = \App\Field::find($reference->field_reference_id);
}
@endphp
@if(!empty($field_reference))
    get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSetBy{!! ucfirst(camel_case($field_reference->name)) !!}({!! snake_case($field_reference->name) !!}): AxiosPromise<any> {
        return httpAuthClient.get(`api/v1/{!! kebab_case(str_plural($menu->name)) !!}/datasets/{!! !empty($field->relation->relation_name) ? kebab_case(str_plural($field->relation->relation_name)) : kebab_case(str_plural($field->relation->table->name)) !!}/relation/{!! snake_case($field_reference->name) !!}/${ {!! snake_case($field_reference->name) !!} }`);
    }
@endif

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
                    '{!! !empty($field->relation->relation_name) ? str_singular($field->relation->relation_name) : str_singular($field->relation->table->name) !!}',
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
