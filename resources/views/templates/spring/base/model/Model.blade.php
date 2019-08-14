package com.orraa.demo.model;

import com.fasterxml.jackson.annotation.JsonIgnore;
import com.fasterxml.jackson.databind.PropertyNamingStrategy;
import com.fasterxml.jackson.databind.annotation.JsonNaming;
import org.hibernate.annotations.Type;

import javax.persistence.*;
import java.math.*;
import java.util.*;
import java.sql.Timestamp;
import java.util.Date;

@Entity
@Table(name = "{!! $table->name !!}")
@JsonNaming(PropertyNamingStrategy.SnakeCaseStrategy.class)
public class {!! ucfirst(camel_case(str_singular($table->name))) !!} {

@foreach($table->fields as $field)
@if ($field->ai)
    @Id
    @GeneratedValue(strategy = GenerationType.SEQUENCE)
    private Long {!! $field->name !!};

@elseif($field->input_type == "image" || $field->input_type == "file")
    @Lob
    @Type(type = "org.hibernate.type.TextType")
    private String {!! $field->name !!};

@elseif($field->type == "text")
    @Lob
    @Type(type = "org.hibernate.type.TextType")
    private String {!! $field->name !!};

@elseif($field->type == "varchar")
@if($field->length > 0)
    {!! "@" !!}Column(
            length = {!! $field->length !!}@if($field->index == "unique"),
            unique = true @endif

    )
@endif
    private String {!! $field->name !!};

@elseif($field->type == "integer")
    private int {!! $field->name !!};

@elseif($field->type == "float")
    private float {!! $field->name !!};

@elseif($field->type == "double" || $field->type == "decimal")
    private double {!! $field->name !!};

@elseif($field->type == "char")
    private char {!! $field->name !!};

@elseif($field->type == "date")
    private Date {!! $field->name !!};

@elseif($field->type == "datetime" || $field->type == "timestamp" || $field->type == "time")
    private Timestamp {!! $field->name !!} = new Timestamp(new Date().getTime());

@elseif($field->type == "bigint")
    private BigInteger {!! $field->name !!};

@elseif($field->type == "tinyint")
    @Column(name = "{!! $field->name !!}", columnDefinition = "BOOLEAN DEFAULT true")
    private boolean {!! $field->name !!} = true;

@else
@endif
@endforeach
@foreach(\App\Relation::where('local_table_id', $table->id)
            ->where('relation_type', 'hasmany')
            ->orWhere('local_table_id', $table->id)
            ->where('relation_type', 'belongstomany')
            ->get() as $relation)
@if($relation->relation_type == "hasmany")
    @JsonIgnore
    @OneToMany(cascade = {CascadeType.REMOVE}, fetch = FetchType.LAZY)
    private List<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!} = new ArrayList<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}>();

@elseif($relation->relation_type == "belongstomany")
    @JsonIgnore
    @OneToMany(cascade = {CascadeType.REMOVE}, fetch = FetchType.LAZY)
    private List<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!} = new ArrayList<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}>();

@endif
@endforeach
    public {!! ucfirst(camel_case(str_singular($table->name))) !!}() {
    }

    public {!! ucfirst(camel_case(str_singular($table->name))) !!}(Long id) {
        this.id = id;
    }

@foreach($table->fields as $field)
@if ($field->ai)
    public Long get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(Long {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->input_type == "image" || $field->input_type == "file")
    public String get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(String {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "text")
    public String get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(String {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "varchar")
    public String get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(String {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "integer")
    public int get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(int {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "float")
    public float get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(float {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "double" || $field->type == "decimal")
    public double get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(double {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "char")
    public char get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(char {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "date")
    public Date get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(Date {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "datetime" || $field->type == "timestamp" || $field->type == "time")
    public Timestamp get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(Timestamp {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "bigint")
    public BigInteger get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(BigInteger {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@elseif($field->type == "tinyint")
    public boolean get{!! ucfirst(camel_case($field->name)) !!}() {
        return {!! $field->name !!};
    }

    public void set{!! ucfirst(camel_case($field->name)) !!}(boolean {!! $field->name !!}) {
        this.{!! $field->name !!} = {!! $field->name !!};
    }

@else
@endif
@endforeach

@foreach(\App\Relation::where('local_table_id', $table->id)
            ->where('relation_type', 'hasmany')
            ->orWhere('local_table_id', $table->id)
            ->where('relation_type', 'belongstomany')
            ->get() as $relation)
@if($relation->relation_type == "hasmany")
    public List<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> get{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}() {
        return {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!};
    }

    public void set{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(List<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!}) {
        this.{!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!} = {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!};
    }

@elseif($relation->relation_type == "belongstomany")
    public List<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> get{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}() {
        return {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!};
    }

    public void set{!! !empty($relation->relation_name) ? ucfirst(camel_case($relation->relation_name)) : ucfirst(camel_case($relation->table->name)) !!}(List<{!! ucfirst(camel_case(str_singular($table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}> {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!}) {
        this.{!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!} = {!! !empty($relation->relation_name) ? snake_case($relation->relation_name) : $relation->table->name !!};
    }

@endif
@endforeach

}
