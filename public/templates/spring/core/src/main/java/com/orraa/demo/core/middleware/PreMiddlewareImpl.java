package com.orraa.demo.core.middleware;

import com.orraa.demo.controller.CategoryController;
import org.springframework.context.annotation.Configuration;
import org.springframework.web.servlet.handler.HandlerInterceptorAdapter;

import java.lang.reflect.Method;

@Configuration
public class PreMiddlewareImpl extends HandlerInterceptorAdapter {

    public String next() {

//        boolean allow = true;
//
//        for (Method m : CategoryController.class.getMethods()) {
//
//            PreMiddleware preMiddleware = m.getAnnotation(PreMiddleware.class);
//
//            if (preMiddleware != null && !preMiddleware.value()) {
//                allow = false;
//            }
//        }

//        return allow;
        return "";
    }

}
