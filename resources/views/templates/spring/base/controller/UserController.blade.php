@php
$table = $project->tables()->where('name', 'users')->first();
@endphp
package com.orraa.demo.controller;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.*;
import com.orraa.demo.repository.*;
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
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

@RestController
@RequestMapping("/api/v1/users")
@PreAuthorize("isFullyAuthenticated()")
public class UserController {

@if(!empty($table))
    @Autowired
    private {!! ucfirst(camel_case(str_singular($table->name))) !!}Repository {!! camel_case(str_singular($table->name)) !!}Repository;

    @Autowired
    PasswordEncoder passwordEncoder;

    @Value("${contextPath}")
    private String contextPath;

    @Value("${item-per-page}")
    private int itemPerPage;

    private ObjectMapper oMapper = new ObjectMapper();

    @Autowired
    private CheckPermission checkPermission;

    @RequestMapping(method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getAll(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("users_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<{!! ucfirst(camel_case(str_singular($table->name))) !!}> users = {!! camel_case(str_singular($table->name)) !!}Repository.findAll(PageRequest.of(page != null {!! "?" !!} (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();
        users.forEach(user -> {
            HashMap<String, Object> o = oMapper.convertValue(user, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(
                        data,
                        (page != null {!! "?" !!} (page) : 1),
                        itemPerPage,
                        {!! camel_case(str_singular($table->name)) !!}Repository.countAll().intValue()
                ), HashMap.class)
        );
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getOne(
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        {!! ucfirst(camel_case(str_singular($table->name))) !!} user = {!! camel_case(str_singular($table->name)) !!}Repository.findById(id);

        if (user == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        HashMap<String, Object> data = oMapper.convertValue(user, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/search/{keyword}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getUserByKeyword(
            @PathVariable("keyword") String keyword,
            @RequestParam(name = "page", required = false) Integer page
    ) {
        List<{!! ucfirst(camel_case(str_singular($table->name))) !!}> users = {!! camel_case(str_singular($table->name)) !!}Repository.findByKeyword(keyword, PageRequest.of(page != null {!! "?" !!} (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();

        users.forEach(user -> {
            HashMap<String, Object> o = oMapper.convertValue(user, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(data, page, itemPerPage, 1), HashMap.class)
        );
    }

    @RequestMapping(path = "/store", method = RequestMethod.POST)
    public ApiResponseHelper<{!! "?" !!}> store(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody {!! ucfirst(camel_case(str_singular($table->name))) !!} user
    ) {

@foreach($table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        user.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        user.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(user.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
@elseif($field->type == "varchar")
        user.set{!! ucfirst(camel_case($field->name)) !!}(user.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        user.set{!! ucfirst(camel_case($field->name)) !!}(user.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach

        HashMap<String, Object> data = oMapper.convertValue(user, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/update", method = RequestMethod.PUT)
    public ApiResponseHelper<{!! "?" !!}> update(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody {!! ucfirst(camel_case(str_singular($table->name))) !!} user,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        {!! ucfirst(camel_case(str_singular($table->name))) !!} userModel = {!! camel_case(str_singular($table->name)) !!}Repository.findById(id);

        if (userModel == null) {
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
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode(user.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
@elseif($field->type == "varchar")
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(user.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        userModel.set{!! ucfirst(camel_case($field->name)) !!}(user.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach

        HashMap<String, Object> data = oMapper.convertValue(currentUser, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete", method = RequestMethod.DELETE)
    public ApiResponseHelper<{!! "?" !!}> delete(
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        {!! ucfirst(camel_case(str_singular($table->name))) !!} user = {!! camel_case(str_singular($table->name)) !!}Repository.findById(id);

        if (user == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        {{--user.setActive_flag(false);--}}
        {{--{!! camel_case(str_singular($table->name)) !!}Repository.save(user);--}}
        {!! camel_case(str_singular($table->name)) !!}Repository.delete(user);

        return new ApiResponseHelper<>(
                null,
                true,
                "Awesome, successfully delete {!! ucfirst(camel_case(str_singular($table->name))) !!} !"
        );
    }

    @RequestMapping(path = "/delete/multiple", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteMultiple() {
    }

@endif
}
