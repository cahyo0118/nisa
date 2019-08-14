@php
$table = $project->tables()->where('name', 'roles')->first();
@endphp
package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.*;
import com.orraa.demo.model.Role;
import com.orraa.demo.repository.RolePermissionRepository;
import com.orraa.demo.repository.RoleRepository;
import com.orraa.demo.security.CurrentUser;
import com.orraa.demo.security.UserPrincipal;
import com.orraa.demo.util.ApiResponseHelper;
import com.orraa.demo.util.CheckPermission;
import com.orraa.demo.util.PageableHelper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.data.domain.PageRequest;
import org.springframework.http.HttpStatus;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

@RestController
@RequestMapping("/api/v1/roles")
@PreAuthorize("isFullyAuthenticated()")
public class RoleController {

    @Autowired
    private RoleRepository roleRepository;

    @Autowired
    private RolePermissionRepository rolePermissionRepository;

    private ObjectMapper oMapper = new ObjectMapper();

    @Autowired
    private CheckPermission checkPermission;

    @Value("${item-per-page}")
    private int itemPerPage;

    @RequestMapping(method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getAll(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("roles_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<Role> roles = roleRepository.findAll(PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();
        roles.forEach(role -> {
            List<Permission> permissions = new ArrayList<>();
            for (RolePermission rp : rolePermissionRepository.findAllByRoleId(role.getId())) {
                permissions.add(rp.getPermission());
            }

            HashMap<String, Object> o = oMapper.convertValue(role, HashMap.class);
            o.put("permissions", permissions);
            data.add(o);
        });

        return new ApiResponseHelper<HashMap<String, Object>>(
                oMapper.convertValue(new PageableHelper<>(
                        data,
                        (page != null ? (page) : 1),
                        itemPerPage,
                        roleRepository.countAll().intValue()
                ), HashMap.class)
        );
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<HashMap<String, Object>> getOne(
            @PathVariable("id") long id,
            @CurrentUser UserPrincipal currentUser,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("roles_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Role role = roleRepository.findById(id);

        if (role == null) {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        HashMap<String, Object> data = oMapper.convertValue(role, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/search/{keyword}", method = RequestMethod.GET)
    public ApiResponseHelper<HashMap<String, Object>> getRoleByKeyword(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("keyword") String keyword,
            @RequestParam(name = "page", required = false) Integer page,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("roles_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<Role> roles = roleRepository.findByKeyword(keyword, PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();

        roles.forEach(role -> {
            HashMap<String, Object> o = oMapper.convertValue(role, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<HashMap<String, Object>>(
                oMapper.convertValue(new PageableHelper<>(data, page, itemPerPage, 1), HashMap.class)
        );
    }

    @RequestMapping(path = "/store", method = RequestMethod.POST)
    public ApiResponseHelper<HashMap<String, Object>> store(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody Role role,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("roles_create", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }
@foreach($table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        role.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        role.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(role.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
@elseif($field->type == "varchar")
        role.set{!! ucfirst(camel_case($field->name)) !!}(role.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        role.set{!! ucfirst(camel_case($field->name)) !!}(role.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach
        role = roleRepository.save(role);

        HashMap<String, Object> data = oMapper.convertValue(role, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/update", method = RequestMethod.PUT)
    public ApiResponseHelper<HashMap<String, Object>> update(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody Role role,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("roles_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Role currentRole = roleRepository.findById(id);

        if (role == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

@foreach($table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        currentRole.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        currentRole.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(role.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
@elseif($field->type == "varchar")
        currentRole.set{!! ucfirst(camel_case($field->name)) !!}(role.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        currentRole.set{!! ucfirst(camel_case($field->name)) !!}(role.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach
        currentRole = roleRepository.save(currentRole);

        HashMap<String, Object> data = oMapper.convertValue(currentRole, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete", method = RequestMethod.DELETE)
    public ApiResponseHelper<HashMap<String, Object>> delete(
            @PathVariable("id") long id,
            @CurrentUser UserPrincipal currentUser,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("roles_delete", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Role role = roleRepository.findById(id);

        if (role == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        roleRepository.delete(role);

        return new ApiResponseHelper<>(
                null,
                true,
                "Awesome, successfully delete Role !"
        );
    }

    @RequestMapping(path = "/delete/multiple", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteMultiple() {
    }

    @RequestMapping(path = "/{id}/update-permissions", method = RequestMethod.PUT)
    public ApiResponseHelper<{!! "?" !!}> updatePermissions(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody HashMap<String, Object> body,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("roles_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Role currentRole = roleRepository.findById(id);

        if (currentRole == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        rolePermissionRepository.deleteAllByRoleRoleId(currentRole.getId());

        List<Permission> permissions = oMapper.convertValue(body.get("permissions"), List.class);

        for (Object p : permissions) {
            Permission permission = oMapper.convertValue(p, Permission.class);
            System.out.println(permission.getName());
            RolePermission rp = new RolePermission();

            rp.setRole(new Role(currentRole.getId()));
            rp.setPermission(new Permission(permission.getId()));

            rolePermissionRepository.save(rp);
        }

        HashMap<String, Object> data = oMapper.convertValue(currentRole, HashMap.class);

        return new ApiResponseHelper<>(data);
    }
}
