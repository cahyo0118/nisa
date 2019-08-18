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
    public ResponseEntity<?> authenticateUser(@Valid @RequestBody LoginRequest loginRequest) {

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
    public ApiResponseHelper<?> registerUser(@RequestBody User signUpRequest) {

        System.out.println("-- PASSWORD " + signUpRequest.getPassword());
        System.out.println(oMapper.convertValue(signUpRequest, HashMap.class));
        signUpRequest.setUsername(RandomString.make(5));
        signUpRequest.setPassword(passwordEncoder.encode(signUpRequest.getPassword()));
//        user.setCreated_by(currentUser.getId().intValue());
//        signUpRequest.setUpdated_by(currentUser.getId().intValue());
        signUpRequest.setActive_flag(true);
        signUpRequest = userRepository.save(signUpRequest);

        HashMap<String, Object> data = oMapper.convertValue(signUpRequest, HashMap.class);

        return new ApiResponseHelper<>(data);

//        if (userRepository.existsByUsername(signUpRequest.getUsername())) {
//            return new ResponseEntity(new ApiResponse(false, "Username is already taken!"),
//                    HttpStatus.BAD_REQUEST);
//        }
//
//        if (userRepository.existsByEmail(signUpRequest.getEmail())) {
//            return new ResponseEntity(new ApiResponse(false, "Email Address already in use!"),
//                    HttpStatus.BAD_REQUEST);
//        }
//
//        // Creating user's account
//        User user = new User(signUpRequest.getName(), signUpRequest.getUsername(),
//                signUpRequest.getEmail(), signUpRequest.getPassword());
//
//        user.setPassword(passwordEncoder.encode(user.getPassword()));
//
////        Role userRole = roleRepository.findByName(RoleName.ROLE_USER).orElseThrow(() -> new AppException("User Role not set."));
//
////        user.setRoles(Collections.singleton(userRole));
//
//        User result = userRepository.save(user);
//
//        URI location = ServletUriComponentsBuilder
//                .fromCurrentContextPath().path("/users/{username}")
//                .buildAndExpand(result.getUsername()).toUri();
//
//        return ResponseEntity.created(location).body(new ApiResponse(true, "User registered successfully"));
    }
}
