package com.orraa.demo.config;

import com.orraa.demo.interceptor.CategoryInterceptor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.web.servlet.config.annotation.InterceptorRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

@Component
public class InterceptorRegistrar implements WebMvcConfigurer {

    /*Generate*/
    @Autowired
    CategoryInterceptor categoryInterceptor;

    @Override
    public void addInterceptors(InterceptorRegistry registry) {
        /*Generate*/
        registry.addInterceptor(categoryInterceptor).addPathPatterns("/api/categories");
    }
}
