package com.orraa.demo.model.util;//package com.orraa.demo.model.util;

public class QueryFilter {

    public int id;
    public String order;
    public String sort;
    public String list;
    public String name;
    public int limit;
    public int offset;
    public int fk_join;

    public QueryFilter() {
    }

    public QueryFilter(int id, String order, String sort, String list, String name, int limit, int offset, int fk_join) {
        this.id = id;
        this.order = order;
        this.sort = sort;
        this.list = list;
        this.name = name;
        this.limit = limit;
        this.offset = offset;
        this.fk_join = fk_join;
    }
}
