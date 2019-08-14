@php
$relations = [];
if(!empty($menu->table)) {
    foreach($menu->table->fields as $field) {
        if(!empty($field->relation) && $field->relation->relation_type == "belongsto") {
            array_push($relations, $field->relation->table->name);
        }
    }
    $relations = array_unique($relations);
}
@endphp
package com.orraa.demo.controller;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.*;
import com.orraa.demo.repository.*;
import com.orraa.demo.security.CurrentUser;
import com.orraa.demo.security.UserPrincipal;
import com.orraa.demo.util.*;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.data.domain.PageRequest;
import org.springframework.http.HttpStatus;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.*;

import net.bytebuddy.utility.RandomString;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

@RestController
@RequestMapping("/api/v1/{!! str_plural(str_replace('-', '_', $menu->name)) !!}")
@PreAuthorize("isFullyAuthenticated()")
public class {!! ucfirst(camel_case($menu->name)) !!}Controller {

@if(!empty($menu->table))
    @Autowired
    private {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}Repository {!! camel_case(str_singular($menu->table->name)) !!}Repository;
@foreach($relations as $relation)
@if($relation !== $menu->table->name)

    @Autowired
    private {!! ucfirst(camel_case(str_singular($relation))) !!}Repository {!! camel_case(str_singular($relation)) !!}Repository;
@endif
@endforeach

    @Autowired
    PasswordEncoder passwordEncoder;

    @Autowired
    private DB db;

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
            @RequestParam(name = "with[]", required = false) String[] with,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        Map<String, Object> queryParams = new HashMap<String, Object>();
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if(!empty($criteria->relation))
@else
@if(empty($criteria->pivot->operator))
@elseif($criteria->pivot->operator == 'like%')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'like')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'not_like')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '=')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '!=')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'single_quotes=')
        queryParams.put("{!! $criteria->name !!}", "{!! $criteria->pivot->value !!}");
@elseif($criteria->pivot->operator == '!single_quotes=')
        queryParams.put("{!! $criteria->name !!}", "{!! $criteria->pivot->value !!}");
@elseif($criteria->pivot->operator == 'in')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'not_in')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'between')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'not_between')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'is_null')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'is_not_null')
        queryParams.put("{!! $criteria->name !!}", {!! $criteria->pivot->value !!});
@else
        queryParams.put("{!! $criteria->name !!}", "{!! $criteria->pivot->value !!}");
@endif
@endif
@endforeach

        List<Map<String, Object>> {!! str_plural(camel_case($menu->name)) !!} = db.select(
                "SELECT * FROM {!! $menu->table->name !!}"
@if(count($menu->field_criterias) > 0)
                        + " WHERE"
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if(!empty($criteria->pivot->operator))
@if(!empty($criteria->relation))
@else
@if(empty($criteria->pivot->operator))
@elseif($criteria->pivot->operator == 'like%')
@if($criteria_index)

@endif
+ " {!! $criteria->name !!} LIKE %:{!! $criteria->name !!}%"
@elseif($criteria->pivot->operator == 'like')
                        + " {!! $criteria->name !!} LIKE :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'not_like')
                        + " {!! $criteria->name !!} NOT LIKE :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == '=')
                        + " {!! $criteria->name !!} = :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == '!=')
                        + " {!! $criteria->name !!} != :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'single_quotes=')
                        + " {!! $criteria->name !!} = :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == '!single_quotes=')
                        + " {!! $criteria->name !!} != :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'in')
                        + " {!! $criteria->name !!} IN :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'not_in')
                        + " {!! $criteria->name !!} NOT IN :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'between')
                        + " {!! $criteria->name !!} BETWEEN :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'not_between')
                        + " {!! $criteria->name !!} NOT BETWEEN :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'is_null')
                        + " {!! $criteria->name !!} IS NULL :{!! $criteria->name !!}"
@elseif($criteria->pivot->operator == 'is_not_null')
                        + " {!! $criteria->name !!} IS NOT NULL :{!! $criteria->name !!}"
@else
                        + " {!! $criteria->name !!} = :{!! $criteria->name !!}"
@endif
@endif
@endif
@endforeach
@endif
                , queryParams
        );

        List<HashMap<String, Object>> data = new ArrayList<>();
        {!! str_plural(camel_case($menu->name)) !!}.forEach({!! camel_case(str_singular($menu->name)) !!} -> {
            HashMap<String, Object> o = new HashMap<>({!! camel_case(str_singular($menu->name)) !!});

            if (with != null) {
                for (String w : with) {

@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                    if (w.equals("{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : camel_case(str_singular($field->relation->table->name)) !!}")) {
                        Map<String, Object> relationQueryParams = new HashMap<String, Object>();
                        relationQueryParams.put("{!! camel_case(str_singular($field->relation->foreign_key_field->name)) !!}", o.get("{!! $field->name !!}"));

                        Map relationResult = db.row("SELECT * FROM {!! $field->relation->table->name !!} WHERE {!! camel_case(str_singular($field->relation->foreign_key_field->name)) !!} = :{!! camel_case(str_singular($field->relation->foreign_key_field->name)) !!}", relationQueryParams);

                        o.put(w, relationResult);
                    }

@else
                    if (w.equals("{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : camel_case(str_singular($field->relation->table->name)) !!}")) {
                        Map<String, Object> relationQueryParams = new HashMap<String, Object>();
                        relationQueryParams.put("{!! camel_case(str_singular($field->relation->foreign_key_field->name)) !!}", o.get("{!! $field->name !!}"));

                        List<Map<String, Object>> relationResults = db.select("SELECT * FROM {!! $field->relation->table->name !!} WHERE {!! camel_case(str_singular($field->relation->foreign_key_field->name)) !!} = :{!! camel_case(str_singular($field->relation->foreign_key_field->name)) !!}", relationQueryParams);

                        o.put(w, relationResults);
                    }

@endif
@endif
@endforeach
                }
            }

            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(
                        data,
                        (page != null {!! "?" !!} (page) : 1),
                        itemPerPage,
                        {!! camel_case(str_singular($menu->table->name)) !!}Repository.countAll().intValue()
                ), HashMap.class)
        );
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> getOne(
            @PathVariable("id") long id,
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "with[]", required = false) String[] with,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        {!! ucfirst(camel_case(str_singular($menu->table->name))) !!} {!! camel_case($menu->name) !!} = {!! camel_case(str_singular($menu->table->name)) !!}Repository.findById(id);

        if ({!! camel_case($menu->name) !!} == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        HashMap<String, Object> data = oMapper.convertValue({!! camel_case($menu->name) !!}, HashMap.class);

        if (with != null) {
            for (int withIndex = 0; withIndex < with.length; withIndex++) {
@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                if (with[withIndex].equals("{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : camel_case(str_singular($field->relation->table->name)) !!}")) {

                    data.put("{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : camel_case(str_singular($field->relation->table->name)) !!}", {!! camel_case(str_singular($field->relation->table->name)) !!}Repository.findById({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));

                }

@endif
@endif
@endforeach
            }
        }

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/search/{keyword}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> get{!! ucfirst(camel_case($menu->name)) !!}ByKeyword(
            @PathVariable("keyword") String keyword,
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            @RequestParam(name = "with[]", required = false) String[] with,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<{!! ucfirst(camel_case(str_singular($menu->table->name))) !!}> {!! str_plural(camel_case($menu->name)) !!} = {!! camel_case(str_singular($menu->table->name)) !!}Repository.findByKeyword(keyword, PageRequest.of(page != null {!! "?" !!} (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();

        {!! str_plural(camel_case($menu->name)) !!}.forEach({!! camel_case(str_singular($menu->name)) !!} -> {
            HashMap<String, Object> o = oMapper.convertValue({!! camel_case(str_singular($menu->name)) !!}, HashMap.class);

            if (with != null) {
                for (int withIndex = 0; withIndex < with.length; withIndex++) {
@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
                    if (with[withIndex].equals("{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : camel_case(str_singular($field->relation->table->name)) !!}")) {

                        o.put("{!! !empty($field->relation->relation_name) ? snake_case($field->relation->relation_name) : camel_case(str_singular($field->relation->table->name)) !!}", {!! camel_case(str_singular($field->relation->table->name)) !!}Repository.findById({!! camel_case(str_singular($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));

                    }

@endif
@endif
@endforeach
                }
            }

            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(data, page, itemPerPage, 1), HashMap.class)
        );
    }

    @RequestMapping(path = "/store", method = RequestMethod.POST)
    public ApiResponseHelper<{!! "?" !!}> store(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody {!! ucfirst(camel_case(str_singular($menu->table->name))) !!} {!! camel_case($menu->name) !!},
            HttpServletRequest request,
            HttpServletResponse response
    ) throws IOException {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_create", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

@foreach($menu->table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        {!! camel_case($menu->name) !!}.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        {!! camel_case($menu->name) !!}.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
        if ({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null) {

            File directory = new File(contextPath + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/photos/");

            if (!directory.exists()) {
                directory.mkdirs();
            }

            byte[] bytes = Base64.getMimeDecoder().decode(({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}().split(",")[1]));

            String filename = new Date().getTime()
                    + "." + RandomString.make(6)
                    + "." + Helpers.extractMimeType({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()).split("/")[1];

            Path path = Paths.get(
                    contextPath
                            + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/photos/"
                            + filename
            );

            Files.write(path, bytes);

            {!! camel_case($menu->name) !!}.set{!! ucfirst(camel_case($field->name)) !!}("files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/photos/" + filename);
        }
@elseif($field->input_type == "file")
        if ({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null) {

            File directory = new File(contextPath + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/documents/");

            if (!directory.exists()) {
                directory.mkdirs();
            }

            byte[] bytes = Base64.getMimeDecoder().decode(({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}().split(",")[1]));

            String filename = new Date().getTime()
                    + "." + RandomString.make(6)
                    + "." + Helpers.extractMimeType({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()).split("/")[1];

            Path path = Paths.get(
                    contextPath
                            + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/documents/"
                            + filename
            );

            Files.write(path, bytes);


            {!! camel_case($menu->name) !!}.set{!! ucfirst(camel_case($field->name)) !!}("files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/documents/" + filename);

        }
@elseif($field->type == "varchar")
        {!! camel_case($menu->name) !!}.set{!! ucfirst(camel_case($field->name)) !!}({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        {!! camel_case($menu->name) !!}.set{!! ucfirst(camel_case($field->name)) !!}({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach
        {!! camel_case(str_singular($menu->table->name)) !!}Repository.save({!! camel_case($menu->name) !!});

        HashMap<String, Object> data = oMapper.convertValue({!! camel_case($menu->name) !!}, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/update", method = RequestMethod.PUT)
    public ApiResponseHelper<{!! "?" !!}> update(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody {!! ucfirst(camel_case(str_singular($menu->table->name))) !!} {!! camel_case($menu->name) !!},
            HttpServletRequest request,
            HttpServletResponse response
    ) throws IOException {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        {!! ucfirst(camel_case(str_singular($menu->table->name))) !!} current{!! ucfirst(camel_case($menu->name)) !!} = {!! camel_case(str_singular($menu->table->name)) !!}Repository.findById(id);

        if (current{!! ucfirst(camel_case($menu->name)) !!} == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

@foreach($menu->table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}(currentUser.getId().intValue());
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}(passwordEncoder.encode({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));
@elseif($field->input_type == "image")
        if ({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null) {

            if (current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null && !current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}().equals("") && {!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}().contains(current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}())) {
            } else {
                File directory = new File(contextPath + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/photos/");

                if (!directory.exists()) {
                    directory.mkdirs();
                }

                byte[] bytes = Base64.getMimeDecoder().decode(({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}().split(",")[1]));

                String filename = new Date().getTime()
                        + "." + RandomString.make(6)
                        + "." + Helpers.extractMimeType({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()).split("/")[1];

                Path path = Paths.get(
                        contextPath
                                + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/photos/"
                                + filename
                );

                Files.write(path, bytes);

                if (current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null && !current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}().equals("")) {
                    Files.deleteIfExists(Paths.get(contextPath + current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));
                }

                current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}("files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/photos/" + filename);
            }
        } else if ({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}() == null) {
            Files.deleteIfExists(Paths.get(contextPath + current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));
            current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}(null);
        }
@elseif($field->input_type == "file")
        if ({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null) {

            if (current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null && !current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}().equals("") && {!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}().contains(current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}())) {
            } else {
                File directory = new File(contextPath + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/documents/");

                if (!directory.exists()) {
                    directory.mkdirs();
                }

                byte[] bytes = Base64.getMimeDecoder().decode(({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}().split(",")[1]));

                String filename = new Date().getTime()
                        + "." + RandomString.make(6)
                        + "." + Helpers.extractMimeType({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}()).split("/")[1];

                Path path = Paths.get(
                        contextPath
                                + "files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/documents/"
                                + filename
                );

                Files.write(path, bytes);

                if (current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}() != null && !current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}().equals("")) {
                    Files.deleteIfExists(Paths.get(contextPath + current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));
                }

                current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}("files/{!! str_plural(str_replace('-', '_', $menu->name)) !!}/documents/" + filename);
            }
        } else if ({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}() == null) {
            Files.deleteIfExists(Paths.get(contextPath + current{!! ucfirst(camel_case($menu->name)) !!}.get{!! ucfirst(camel_case($field->name)) !!}()));
            current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}(null);
        }
@elseif($field->type == "varchar")
        current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}());
@else
        current{!! ucfirst(camel_case($menu->name)) !!}.set{!! ucfirst(camel_case($field->name)) !!}({!! camel_case($menu->name) !!}.get{!! ucfirst(camel_case($field->name)) !!}());
@endif
@endforeach

        {!! camel_case(str_singular($menu->table->name)) !!}Repository.save(current{!! ucfirst(camel_case($menu->name)) !!});
        HashMap<String, Object> data = oMapper.convertValue(current{!! ucfirst(camel_case($menu->name)) !!}, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete", method = RequestMethod.DELETE)
    public ApiResponseHelper<{!! "?" !!}> delete(
            @PathVariable("id") long id,
            @CurrentUser UserPrincipal currentUser,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_delete", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        {!! ucfirst(camel_case(str_singular($menu->table->name))) !!} {!! camel_case($menu->name) !!} = {!! camel_case(str_singular($menu->table->name)) !!}Repository.findById(id);

        if ({!! camel_case($menu->name) !!} == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }
@if(!empty($menu->table->fields()->where('name', 'active_flag')->first()))
        {!! camel_case($menu->name) !!}.setActiveFlag(false);
@endif

        {!! camel_case(str_singular($menu->table->name)) !!}Repository.save({!! camel_case($menu->name) !!});
        // {!! camel_case(str_singular($menu->table->name)) !!}Repository.delete({!! camel_case($menu->name) !!});

        return new ApiResponseHelper<>(
                null,
                true,
                "Awesome, successfully delete {!! ucfirst(camel_case(str_singular($menu->table->name))) !!} !"
        );
    }

    @RequestMapping(path = "/delete/multiple", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteMultiple() {
    }

@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")

    @RequestMapping(path = "/datasets/{!! !empty($field->relation->relation_name) ? kebab_case(str_plural($field->relation->relation_name)) : kebab_case(str_plural($field->relation->table->name)) !!}", method = RequestMethod.GET)
    public ApiResponseHelper<{!! "?" !!}> get{!! ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("{!! str_plural(snake_case($menu->name)) !!}_read", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<{!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!}> {!! str_plural(camel_case($field->relation->table->name)) !!} = {!! camel_case(str_singular($field->relation->table->name)) !!}Repository.findAll();

        List<HashMap<String, Object>> data = new ArrayList<>();
        {!! str_plural(camel_case($field->relation->table->name)) !!}.forEach({!! camel_case(str_singular($field->relation->table->name)) !!} -> {
            HashMap<String, Object> o = oMapper.convertValue({!! camel_case(str_singular($field->relation->table->name)) !!}, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<>(data);
    }

@endif
@endif
@endforeach

@endif

}
