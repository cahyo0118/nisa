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
@RequestMapping("/api/v1/departments")
@PreAuthorize("isFullyAuthenticated()")
public class DepartmentController {

    @Autowired
    private DepartmentRepository departmentRepository;

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
    public ApiResponseHelper<?> getAll(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("schools_pic_lists_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        List<Department> departments = departmentRepository.findAll(PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();
        departments.forEach(department -> {
            HashMap<String, Object> o = oMapper.convertValue(department, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(
                        data,
                        (page != null ? (page) : 1),
                        itemPerPage,
                        departmentRepository.countAll().intValue()
                ), HashMap.class)
        );
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<?> getOne(
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        Department department = departmentRepository.findById(id);

        if (department == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        HashMap<String, Object> data = oMapper.convertValue(department, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/search/{keyword}", method = RequestMethod.GET)
    public ApiResponseHelper<?> getDepartmentByKeyword(
            @PathVariable("keyword") String keyword,
            @RequestParam(name = "page", required = false) Integer page
    ) {
        List<Department> departments = departmentRepository.findByKeyword(keyword, PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();

        departments.forEach(department -> {
            HashMap<String, Object> o = oMapper.convertValue(department, HashMap.class);
            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(data, page, itemPerPage, 1), HashMap.class)
        );
    }

    @RequestMapping(path = "/store", method = RequestMethod.POST)
    public ApiResponseHelper<?> store(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody Department department
    ) {
        department.setCreated_by(currentUser.getId().intValue());
        department.setUpdated_by(currentUser.getId().intValue());
        department.setActive_flag(true);
        department = departmentRepository.save(department);

        HashMap<String, Object> data = oMapper.convertValue(department, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/update", method = RequestMethod.PUT)
    public ApiResponseHelper<?> update(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody Department department,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        Department currentDepartment = departmentRepository.findById(id);

        if (department == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        currentDepartment.setName(department.getName());
        currentDepartment.setUpdated_by(currentUser.getId().intValue());
        currentDepartment.setActive_flag(true);
        currentDepartment = departmentRepository.save(currentDepartment);

        HashMap<String, Object> data = oMapper.convertValue(currentDepartment, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete", method = RequestMethod.DELETE)
    public ApiResponseHelper<?> delete(
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        Department department = departmentRepository.findById(id);

        if (department == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        department.setActive_flag(false);
        departmentRepository.save(department);

        return new ApiResponseHelper<>(
                null,
                true,
                "Awesome, successfully delete Department !"
        );
    }

    @RequestMapping(path = "/delete/multiple", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteMultiple() {
    }

}
