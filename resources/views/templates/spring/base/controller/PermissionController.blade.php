@php
$table = $project->tables()->where('name', 'permissions')->first();
@endphp
package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.Permission;
import com.orraa.demo.repository.PermissionRepository;
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
@RequestMapping("/api/v1/permissions")
@PreAuthorize("isFullyAuthenticated()")
public class PermissionController {

    @Autowired
    private PermissionRepository permissionRepository;

    private ObjectMapper oMapper = new ObjectMapper();

    @Autowired
    private CheckPermission checkPermission;

    @Value("${item-per-page}")
    private int itemPerPage;

    @RequestMapping(method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getAll(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            @RequestParam(name = "all", required = false) boolean all,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("permissions_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        if (all) {
            List<Permission> permissions = permissionRepository.findAll();

            List<HashMap<String, Object>> data = new ArrayList<>();
            permissions.forEach(permission -> {
                HashMap<String, Object> o = oMapper.convertValue(permission, HashMap.class);
                data.add(o);
            });

            return new ApiResponseHelper<>(data);

        } else {
            List<Permission> permissions = permissionRepository.findAll(PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

            List<HashMap<String, Object>> data = new ArrayList<>();
            permissions.forEach(permission -> {
                HashMap<String, Object> o = oMapper.convertValue(permission, HashMap.class);
                data.add(o);
            });

            return new ApiResponseHelper<>(
                    oMapper.convertValue(new PageableHelper<>(
                            data,
                            (page != null ? (page) : 1),
                            itemPerPage,
                            permissionRepository.countAll().intValue()
                    ), HashMap.class)
            );
        }

    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getOne(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("permissions_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Permission permission = permissionRepository.findById(id);

        if (permission == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        HashMap<String, Object> data = oMapper.convertValue(permission, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/search/{keyword}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getPermissionByKeyword(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("keyword") String keyword,
            @RequestParam(name = "page", required = false) Integer page,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("permissions_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<Permission> permissions = permissionRepository.findByKeyword(keyword, PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();

        permissions.forEach(permission -> {
            HashMap<String, Object> o = oMapper.convertValue(permission, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(data, page, itemPerPage, 1), HashMap.class)
        );
    }

    @RequestMapping(name = "/store", method = RequestMethod.POST)
    public ApiResponseHelper<{!! "?" !!}> store(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody Permission permission,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("permissions_create", currentUser.getId())) {
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
        permission.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        permission.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(permission.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
@elseif($field->type == "varchar")
        permission.set{!! ucfirst(camel_case($field->name)) !!}(permission.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        permission.set{!! ucfirst(camel_case($field->name)) !!}(permission.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach
        permission = permissionRepository.save(permission);

        HashMap<String, Object> data = oMapper.convertValue(permission, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/update", method = RequestMethod.PUT)
    public ApiResponseHelper<HashMap<String, Object>> update(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody Permission permission,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("permissions_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Permission currentPermission = permissionRepository.findById(id);

        if (permission == null) {
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
        currentPermission.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        currentPermission.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(permission.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
@elseif($field->type == "varchar")
        currentPermission.set{!! ucfirst(camel_case($field->name)) !!}(permission.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        currentPermission.set{!! ucfirst(camel_case($field->name)) !!}(permission.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach
        currentPermission = permissionRepository.save(currentPermission);

        HashMap<String, Object> data = oMapper.convertValue(currentPermission, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete", method = RequestMethod.DELETE)
    public ApiResponseHelper<{!! "?" !!}> delete(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("permissions_delete", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Permission permission = permissionRepository.findById(id);

        if (permission == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        permissionRepository.delete(permission);

        return new ApiResponseHelper<>(
                null,
                true,
                "Awesome, successfully delete Permission !"
        );
    }

    @RequestMapping(path = "/delete/multiple", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteMultiple() {
    }
}
