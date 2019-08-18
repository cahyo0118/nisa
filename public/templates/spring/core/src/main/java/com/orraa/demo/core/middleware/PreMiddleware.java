package com.orraa.demo.core.middleware;

import java.lang.annotation.*;

@Target({ ElementType.METHOD, ElementType.TYPE })
@Retention(RetentionPolicy.RUNTIME)
@Inherited
@Documented
public @interface PreMiddleware {

    /**
     * @return the Spring-EL expression to be evaluated before invoking the protected
     * method
     */
    public String value();
}
