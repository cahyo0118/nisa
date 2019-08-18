package com.orraa.demo.util;

public class PageableHelper<T> {
    public int current_page = 1;
    public T data;
    public String first_page_url;
    public int from = 1;
    public int last_page = 1;
    public String last_page_url;
    public String next_page_url;
    public String path;

    public String per_page;

    public String prev_page_url;

    public int to = 1;

    public int total = 1;

    public PageableHelper() {
    }

    public PageableHelper(T data, int current_page, int per_page, int total) {
        this.data = data;
        this.current_page = current_page;
        this.per_page = String.valueOf(per_page);
        this.total = total;

        this.from = per_page * (current_page - 1) + 1;
        this.to = (per_page * (current_page) < total) ? (per_page * (current_page)) : total;

        this.last_page = (int) Math.ceil(((double) total) / ((double) per_page));
        System.out.println("--- TOTAL = " + total);
        System.out.println("--- PER PAGE = " + per_page);
        System.out.println("--- LAST PAGE = " + this.last_page);
    }
}
