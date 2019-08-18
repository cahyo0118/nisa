package com.orraa.demo.service;

import com.orraa.demo.core.service.DefaultService;
import com.orraa.demo.exception.BadRequestException;
import org.springframework.stereotype.Service;
import org.springframework.web.servlet.handler.HandlerInterceptorAdapter;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@Service
public class CategoryService extends HandlerInterceptorAdapter implements DefaultService {

    @Override
    public boolean preHandle(HttpServletRequest request, HttpServletResponse response, Object handler)
            throws Exception {

        return true;
    }

    @Override
    public boolean pre() {
        System.out.println("==================== Before Request ====================");
        if (false)
            throw new BadRequestException("Something when wrong");
        return false;
    }

    @Override
    public void post() {
        System.out.println("==================== After Request ====================");
    }
}
