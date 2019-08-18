package com.orraa.demo.core.middleware;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.exception.BadRequestException;
import com.orraa.demo.service.CategoryService;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.aspectj.lang.JoinPoint;
import org.aspectj.lang.annotation.After;
import org.aspectj.lang.annotation.Aspect;
import org.aspectj.lang.annotation.Before;
import org.aspectj.lang.reflect.MethodSignature;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.annotation.AnnotationUtils;
import org.springframework.security.access.ConfigAttribute;
import org.springframework.security.access.method.AbstractMethodSecurityMetadataSource;
import org.springframework.security.access.prepost.*;
import org.springframework.stereotype.Component;
import org.springframework.util.ClassUtils;

import java.lang.annotation.Annotation;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;

@Aspect
@Component
public class MiddlewareAspect {

    @Autowired
    PreMiddlewareImpl middleware;

    @Autowired
    private CategoryService categoryService;

    protected final Log logger = LogFactory.getLog(getClass());

    @Before(value = "execution(* *.*(..)) && @annotation(com.orraa.demo.core.middleware.PreMiddleware)")
    public void beforeMiddleware(JoinPoint joinPoint) {

        MethodSignature signature = (MethodSignature) joinPoint.getSignature();
        Method method = signature.getMethod();

        ObjectMapper mapper = new ObjectMapper();


        CategoryService temp = new CategoryService();

        try {
            Method cMethod = categoryService.getClass().getMethod(method.getAnnotation(PreMiddleware.class).value());
            System.out.println("--- method name " + cMethod.getName());
            cMethod.invoke(categoryService);

        } catch (Exception e) {
            e.printStackTrace();
        }

        if (middleware.next() != null)
            System.out.println("EEEEEEEEEXXXXXXXXXXXXXXXXEEEEEEEEECCCCCCCCCUUUUUUUUUUTEEEEEEEEEEE");
        else {
            System.out.println("NNNNNNNNNNNOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOTTTTTTTTTTTTTTTTTT");
            throw new BadRequestException("PreMiddleware not allow");
        }

    }

    @After(value = "execution(* *.*(..)) && @annotation(com.orraa.demo.core.middleware.PostMiddleware)")
    public void afterMiddleware(JoinPoint joinPoint) {

    }

}
