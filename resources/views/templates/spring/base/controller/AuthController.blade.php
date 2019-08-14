package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.User;
import com.orraa.demo.payload.ApiResponse;
import com.orraa.demo.payload.JwtAuthenticationResponse;
import com.orraa.demo.payload.LoginRequest;
import com.orraa.demo.payload.SignUpRequest;
import com.orraa.demo.repository.RoleRepository;
import com.orraa.demo.repository.UserRepository;
import com.orraa.demo.security.JwtTokenProvider;
import com.orraa.demo.util.ApiResponseHelper;
import net.bytebuddy.utility.RandomString;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;

import javax.validation.Valid;
import java.net.URI;
import java.util.HashMap;

@RestController
public class AuthController {

    @Autowired
    AuthenticationManager authenticationManager;

    @Autowired
    UserRepository userRepository;

    @Autowired
    RoleRepository roleRepository;

    @Autowired
    PasswordEncoder passwordEncoder;

    @Autowired
    JwtTokenProvider tokenProvider;

    private ObjectMapper oMapper = new ObjectMapper();

    @PostMapping("/oauth/token")
    public ResponseEntity<{!! "?" !!}> authenticateUser(@Valid @RequestBody LoginRequest loginRequest) {

        Authentication authentication = authenticationManager.authenticate(
                new UsernamePasswordAuthenticationToken(
                        loginRequest.getUsername(),
                        loginRequest.getPassword()
                )
        );

        SecurityContextHolder.getContext().setAuthentication(authentication);

        String jwt = tokenProvider.generateToken(authentication);
        return ResponseEntity.ok(new JwtAuthenticationResponse(jwt));
    }

    @PostMapping("/signup")
    public ApiResponseHelper<{!! "?" !!}> registerUser(@RequestBody User signUpRequest) {

@foreach(\App\Table::with(['fields'])->where('project_id', $project->id)->where('name', 'users')->first()->fields as $field)
@if ($field->ai)
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        signUpRequest.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(signUpRequest.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->type == "varchar")
        signUpRequest.set{!! ucfirst(camel_case($field->name)) !!}(signUpRequest.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        signUpRequest.set{!! ucfirst(camel_case($field->name)) !!}(signUpRequest.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach
        signUpRequest = userRepository.save(signUpRequest);

        HashMap<String, Object> data = oMapper.convertValue(signUpRequest, HashMap.class);

        return new ApiResponseHelper<>(data);
    }
}
