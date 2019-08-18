/*Generate File*/
package com.orraa.demo.interceptor;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.Category;
import org.springframework.stereotype.Component;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.servlet.HandlerInterceptor;
import org.springframework.web.servlet.ModelAndView;
import org.springframework.web.util.ContentCachingRequestWrapper;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@Component
public class CategoryInterceptor implements HandlerInterceptor {

    @Override
    public boolean preHandle(
            HttpServletRequest request, HttpServletResponse response, Object handler) throws Exception {

        HttpServletRequest requestCacheWrapperObject
                = new ContentCachingRequestWrapper(request);
        ObjectMapper mapper = new ObjectMapper();

        System.out.println("-- Namanu " + mapper.writeValueAsString(requestCacheWrapperObject.getParameter("namanu")));
        System.out.println("-- Category " + mapper.writeValueAsString(request.getParameterMap()));
        System.out.println("-- Category Body " + mapper.writeValueAsString(requestCacheWrapperObject.getParameterMap()));
        return true;
    }

    @Override
    public void postHandle(
            HttpServletRequest request, HttpServletResponse response, Object handler,
            ModelAndView modelAndView) throws Exception {
    }

    @Override
    public void afterCompletion(HttpServletRequest request, HttpServletResponse response,
                                Object handler, Exception exception) throws Exception {
    }
}
