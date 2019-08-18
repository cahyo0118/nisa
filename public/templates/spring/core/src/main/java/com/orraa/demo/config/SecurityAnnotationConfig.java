package com.orraa.demo.config;

import com.orraa.demo.core.middleware.PrePostConfigMiddleware;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.access.expression.method.ExpressionBasedAnnotationAttributeFactory;
import org.springframework.security.access.method.DelegatingMethodSecurityMetadataSource;
import org.springframework.security.access.method.MethodSecurityMetadataSource;
import org.springframework.security.config.annotation.method.configuration.EnableGlobalMethodSecurity;
import org.springframework.security.config.annotation.method.configuration.GlobalMethodSecurityConfiguration;

import java.util.ArrayList;
import java.util.List;

@EnableGlobalMethodSecurity
@Configuration
public class SecurityAnnotationConfig extends GlobalMethodSecurityConfiguration {

    @Bean
    protected MethodSecurityMetadataSource customAnnotMethodSecurityMetadataSource() {
        List<MethodSecurityMetadataSource> sources = new ArrayList<>();
        ExpressionBasedAnnotationAttributeFactory attributeFactory = new ExpressionBasedAnnotationAttributeFactory(
                getExpressionHandler());

        try {
            sources.add(new PrePostConfigMiddleware(attributeFactory));
        } catch (Exception e) {
            System.out.println("--- " + e.getMessage());
        }
        return new DelegatingMethodSecurityMetadataSource(sources);
    }
}
