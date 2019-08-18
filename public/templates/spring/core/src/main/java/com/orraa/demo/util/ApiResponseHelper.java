package com.orraa.demo.util;

public class ApiResponseHelper<T> {
    public boolean success = true;
    public T data;
    public String message = "Successfully executed action";

    public ApiResponseHelper() {
    }

    public ApiResponseHelper(T data) {
        this.data = data;
    }

    public ApiResponseHelper(T data, boolean success, String message) {
        this.success = success;
        this.data = data;
        this.message = message;
    }
}
