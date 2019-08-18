package com.orraa.demo.model;

import com.fasterxml.jackson.annotation.*;

import javax.persistence.*;

@Entity
@Table(name = "books")
public class Book {

    @Transient
    @JsonIgnore
    public String edit = "1";

    @Transient
    @JsonIgnore
    public String add = "1";

    @Transient
    @JsonIgnore
    public String delete = "1";

    @Transient
    @JsonIgnore
    public String customQuery = "1";

    @Id
    @GeneratedValue(strategy = GenerationType.SEQUENCE)
    @Column(columnDefinition = "serial")
    public int id;

    @Column(nullable = false)
    public String name;

//    @Column(nullable = false)
//    public int category_id;

    @JsonIgnore
    @ManyToOne(fetch = FetchType.LAZY)
//    @JoinColumn(name = "category_id")
    public Category category;

    public Book() {
    }

    public Book(int id) {
        this.id = id;
    }

    public Book(int id, String name) {
        this.id = id;
        this.name = name;
    }

}