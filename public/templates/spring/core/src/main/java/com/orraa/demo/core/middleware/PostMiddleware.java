package com.orraa.demo.core.middleware;

import java.lang.annotation.*;

@Retention(RetentionPolicy.RUNTIME)
@Target({ ElementType.METHOD, ElementType.TYPE })
@Inherited
@Documented
public @interface PostMiddleware {
    boolean value() default false;
}
