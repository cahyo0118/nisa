package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.*;
import com.orraa.demo.repository.*;
import com.orraa.demo.util.Helpers;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.MediaType;
import org.springframework.web.multipart.MultipartFile;
import com.orraa.demo.security.CurrentUser;
import com.orraa.demo.security.UserPrincipal;
import com.orraa.demo.util.ApiResponseHelper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.*;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;

import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.List;

@RestController
@RequestMapping("/api/v1")
@PreAuthorize("isFullyAuthenticated()")
public class UserProfileController {

    @Autowired
    private UserProfileRepository userProfileRepository;

    @Autowired
    private RoleRepository roleRepository;

    @Autowired
    private PermissionRepository permissionRepository;

    @Autowired
    private UserRoleRepository userRoleRepository;

    @Autowired
    private RolePermissionRepository rolePermissionRepository;

    @Autowired
    PasswordEncoder passwordEncoder;

    @Value("${contextPath}")
    private String contextPath;

    @Value("${item-per-page}")
    private int itemPerPage;

    private ObjectMapper oMapper = new ObjectMapper();

//    private final StorageService storageService;

    @RequestMapping(value = "/me", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getCurrentUser(
            @CurrentUser UserPrincipal currentUser,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        List<String> permissionList = new ArrayList<String>();

        List<Role> roleList = new ArrayList<>();

        List<UserRole> roles = userRoleRepository.findByUserId(currentUser.getId());

        roles.forEach(role -> {
            roleList.add(role.getRole());
            List<RolePermission> permissions = rolePermissionRepository.findAllByRoleId(role.getRole().getId());
            permissions.forEach(permission -> {
                permissionList.add(permission.getPermission().getName());
            });
        });

        HashMap<String, Object> user = oMapper.convertValue(userProfileRepository.findById(currentUser.getId()), HashMap.class);
        user.put("roles", roleList);
        user.put("permissions", permissionList);
        return new ApiResponseHelper<HashMap<String, Object>>(user);
    }

    @RequestMapping(value = "/me/auth", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> checkAuth(
            @CurrentUser UserPrincipal currentUser,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        HashMap<String, Object> user = oMapper.convertValue(userProfileRepository.findById(currentUser.getId()), HashMap.class);
        return new ApiResponseHelper<HashMap<String, Object>>(user);
    }

    @RequestMapping(value = "/me/update", method = RequestMethod.PUT)
    public ApiResponseHelper<{!! "?" !!}> updateCurrentUser(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody User requestBody,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        User userModel = userProfileRepository.findById(currentUser.getId());

        if (userModel == null) {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

@foreach(\App\Table::with(['fields'])->where('project_id', $project->id)->where('name', 'users')->first()->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(userModel.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->type == "varchar")
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(userModel.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(userModel.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach

        userProfileRepository.save(userModel);

        HashMap<String, Object> user = oMapper.convertValue(userModel, HashMap.class);

        return new ApiResponseHelper<HashMap<String, Object>>(user);
    }

    @RequestMapping(value = "/me/update/password", method = RequestMethod.PUT)
    public ApiResponseHelper<{!! "?" !!}> updateCurrentUserPassword(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody HashMap<String, Object> requestBody,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        User userModel = userProfileRepository.findById(currentUser.getId());

        if (userModel == null) {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        if (passwordEncoder.matches((CharSequence) requestBody.get("current_password"), userModel.getPassword())) {
            userModel.setPassword(passwordEncoder.encode((CharSequence) requestBody.get("new_password")));
            userProfileRepository.save(userModel);
        } else {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Oops, password does not match !"
            );
        }


        HashMap<String, Object> user = oMapper.convertValue(userModel, HashMap.class);

        return new ApiResponseHelper<HashMap<String, Object>>(user);
    }

    @RequestMapping(value = "/me/update/photo", method = RequestMethod.POST)
    public ApiResponseHelper<{!! "?" !!}> updateCurrentUserPhoto(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam("photo") MultipartFile file,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        User userModel = userProfileRepository.findById(currentUser.getId());

        if (userModel == null) {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        try {

            File directory = new File(contextPath + "files/users/photos/");
            if (!directory.exists()) {
                directory.mkdirs();
            }

            byte[] bytes = file.getBytes();

            String filename = new Date().getTime() + "." + file.getOriginalFilename();

            Path path = Paths.get(
                    contextPath
                            + "files/users/photos/"
                            + filename
            );

            System.out.println("--- Filename " + file.getOriginalFilename());
            Files.write(path, bytes);

            userModel.setPhoto("files/users/photos/" + filename);

            userProfileRepository.save(userModel);

            HashMap<String, Object> user = oMapper.convertValue(userModel, HashMap.class);

            return new ApiResponseHelper<HashMap<String, Object>>(user, true, "Berhasil Lho ya");
        } catch (Exception e) {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    e.getMessage()
            );
        }


    }

    @RequestMapping(value = "/users/photos/{filename}", method = RequestMethod.GET, produces = MediaType.IMAGE_JPEG_VALUE)
    @PreAuthorize("permitAll()")
    public @ResponseBody
    byte[] getCurrentUserPhoto(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("filename") String filename,
            HttpServletRequest request,
            HttpServletResponse response
    ) throws IOException {
        InputStream in = getClass().getResourceAsStream(contextPath + "users/photos/" + filename);
        byte[] bFile = Helpers.readBytesFromFile(contextPath + "users/photos/" + filename);

        return bFile;

    }

}
