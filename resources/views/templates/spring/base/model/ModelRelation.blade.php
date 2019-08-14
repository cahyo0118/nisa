package com.orraa.demo.model;

import javax.persistence.*;

@if($relation->relation_type == 'belongstomany' || $relation->relation_type == 'hasmany')
@Entity
@Table(name = "{!! str_singular($relation->local_table->name) !!}_{!! str_singular($relation->table->name) !!}")
@endif
public class {!! $relation->relation_type == 'belongstomany' ? '' : 'AddForeign' !!}{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!} {

@if($relation->relation_type == 'belongstomany' || $relation->relation_type == 'hasmany')
    @Id
    @GeneratedValue(strategy = GenerationType.SEQUENCE)
    @Column(columnDefinition = "serial")
    private Long id;

    @ManyToOne
    @JoinColumn(name = "{!! str_singular($relation->local_table->name) !!}_id", nullable = false)
    private {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!} {!! str_singular($relation->local_table->name) !!} = new {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}();

    @ManyToOne
    @JoinColumn(name = "{!! str_singular($relation->table->name) !!}_id", nullable = false)
    private {!! ucfirst(camel_case(str_singular($relation->table->name))) !!} {!! camel_case(str_singular($relation->table->name)) !!} = new {!! ucfirst(camel_case(str_singular($relation->table->name))) !!}();

    public {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}() {
    }

    public {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}(Long {!! camel_case(str_singular($relation->local_table->name)) !!}Id, Long {!! camel_case(str_singular($relation->table->name)) !!}Id) {
        this.{!! camel_case(str_singular($relation->table->name)) !!} = new {!! ucfirst(camel_case(str_singular($relation->table->name))) !!}({!! camel_case(str_singular($relation->table->name)) !!}Id);
        this.{!! camel_case(str_singular($relation->local_table->name)) !!} = new {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}({!! camel_case(str_singular($relation->local_table->name)) !!}Id);
    }

    public {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}({!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!} {!! camel_case(str_singular($relation->local_table->name)) !!}, {!! ucfirst(camel_case(str_singular($relation->table->name))) !!} {!! camel_case(str_singular($relation->table->name)) !!}) {
        this.{!! camel_case(str_singular($relation->local_table->name)) !!} = {!! camel_case(str_singular($relation->local_table->name)) !!};
        this.{!! camel_case(str_singular($relation->table->name)) !!} = {!! camel_case(str_singular($relation->table->name)) !!};
    }

    public {!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!} get{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}() {
        return {!! camel_case(str_singular($relation->local_table->name)) !!};
    }

    public void set{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}({!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!} {!! camel_case(str_singular($relation->local_table->name)) !!}) {
        this.{!! camel_case(str_singular($relation->local_table->name)) !!} = {!! camel_case(str_singular($relation->local_table->name)) !!};
    }

    public {!! ucfirst(camel_case(str_singular($relation->table->name))) !!} get{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}() {
        return {!! camel_case(str_singular($relation->table->name)) !!};
    }

    public void set{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}({!! ucfirst(camel_case(str_singular($relation->table->name))) !!} {!! camel_case(str_singular($relation->table->name)) !!}) {
        this.{!! camel_case(str_singular($relation->table->name)) !!} = {!! camel_case(str_singular($relation->table->name)) !!};
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }
@endif
}
