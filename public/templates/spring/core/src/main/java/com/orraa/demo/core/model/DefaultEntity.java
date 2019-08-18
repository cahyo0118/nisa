package com.orraa.demo.core.model;

import com.fasterxml.jackson.annotation.JsonIgnore;

import javax.persistence.Transient;
import java.util.ArrayList;

public class DefaultEntity {

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

    @Transient
    @JsonIgnore(value = true)
    public ArrayList<String> with = new ArrayList<>();

}
