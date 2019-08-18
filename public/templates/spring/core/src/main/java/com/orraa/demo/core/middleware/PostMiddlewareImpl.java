package com.orraa.demo.core.middleware;

import com.orraa.demo.controller.CategoryController;
import org.springframework.context.annotation.Configuration;
import org.springframework.web.servlet.handler.HandlerInterceptorAdapter;

import java.lang.reflect.Method;

@Configuration
public class PostMiddlewareImpl extends HandlerInterceptorAdapter {

    public boolean next() {

        boolean allow = true;

        for (Method m : CategoryController.class.getMethods()) {

            PostMiddleware preMiddleware = m.getAnnotation(PostMiddleware.class);

            if (preMiddleware != null && !preMiddleware.value()) {
                allow = false;
            }
        }

        return allow;
    }

}
