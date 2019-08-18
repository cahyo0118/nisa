package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.exception.ResourceNotFoundException;
import com.orraa.demo.model.User;
import com.orraa.demo.model.UserRole;
import com.orraa.demo.payload.*;
import com.orraa.demo.repository.UserRepository;
import com.orraa.demo.repository.UserRoleRepository;
import com.orraa.demo.security.CurrentUser;
import com.orraa.demo.security.UserPrincipal;
import com.orraa.demo.util.ApiResponseHelper;
import com.orraa.demo.util.CheckPermission;
import com.orraa.demo.util.Helpers;
import com.orraa.demo.util.PageableHelper;
import net.bytebuddy.utility.RandomString;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.data.domain.PageRequest;
import org.springframework.http.HttpStatus;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.*;

@RestController
@RequestMapping("/api/v1/users")
public class UserController {

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private UserRoleRepository userRoleRepository;

    @Autowired
    PasswordEncoder passwordEncoder;

    @Value("${contextPath}")
    private String contextPath;

    @Value("${item-per-page}")
    private int itemPerPage;

    private ObjectMapper oMapper = new ObjectMapper();

    @Autowired
    private CheckPermission checkPermission;

    private static final Logger logger = LoggerFactory.getLogger(UserController.class);

    @RequestMapping(method = RequestMethod.GET)
    public ApiResponseHelper<?> getAll(
            @CurrentUser UserPrincipal currentUser,
            @RequestParam(name = "page", required = false) Integer page,
            @RequestParam(name = "with[]", required = false) String[] with,
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

        List<User> users = userRepository.findAllA(PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

        List<HashMap<String, Object>> data = new ArrayList<>();
        users.forEach(user -> {
            HashMap<String, Object> o = oMapper.convertValue(user, HashMap.class);

            if (with != null) {
                for (int withIndex = 0; withIndex < with.length; withIndex++) {
                    if (with[withIndex].equals("roles")) {

                        o.put("roles", userRoleRepository.findAllRoleByUserId(user.getId()));

                    }
                }
            }

            o.put("roles", userRoleRepository.findAllRoleByUserId(user.getId()));

            data.add(o);
        });

        return new ApiResponseHelper<>(
                oMapper.convertValue(new PageableHelper<>(
                        data,
                        (page != null ? (page) : 1),
                        itemPerPage,
                        userRepository.countAll().intValue()
                ), HashMap.class)
        );
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<?> getOne(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
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

        User user = userRepository.findById(id);

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
    public ApiResponseHelper<?> getUserByKeyword(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("keyword") String keyword,
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

        List<User> users = userRepository.findByKeyword(keyword, PageRequest.of(page != null ? (page - 1) : 0, itemPerPage));

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
    public ApiResponseHelper<?> store(
            @CurrentUser UserPrincipal currentUser,
            @RequestBody User user,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("users_create", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        user.setUsername(RandomString.make(5));
        user.setPassword(passwordEncoder.encode(user.getPassword()));
        user.setUpdated_by(currentUser.getId().intValue());
        user.setActive_flag(true);
        user = userRepository.save(user);

        HashMap<String, Object> data = oMapper.convertValue(user, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/add-role/{role_id}", method = RequestMethod.POST)
    public ApiResponseHelper<?> addRole(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @PathVariable("role_id") long roleId,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("users_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        User currentUserObject = userRepository.findById(id);

        if (currentUserObject == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        UserRole ur = userRoleRepository.findByUserIdAndRoleId(id, roleId);

        if (ur != null) {
            return new ApiResponseHelper<>();
        }

        ur = new UserRole(id, roleId);
        userRoleRepository.save(ur);
        HashMap<String, Object> data = oMapper.convertValue(currentUser, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete-role/{role_id}", method = RequestMethod.DELETE)
    public ApiResponseHelper<?> deleteRole(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @PathVariable("role_id") long roleId,
            HttpServletRequest request,
            HttpServletResponse response
    ) {

        if (!checkPermission.hasPermission("users_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        User currentUserObject = userRepository.findById(id);

        if (currentUserObject == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        UserRole ur = userRoleRepository.findByUserIdAndRoleId(id, roleId);

        if (ur == null) {
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        userRoleRepository.delete(ur);

        HashMap<String, Object> data = oMapper.convertValue(currentUser, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/update", method = RequestMethod.PUT)
    public ApiResponseHelper<?> update(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            @RequestBody User requestBody,
//            @RequestParam("photo") MultipartFile photo,
            HttpServletRequest request,
            HttpServletResponse response
    ) throws IOException {

        System.out.println("--- PHOTO_VALUE " + requestBody.getPhoto());
        if (!checkPermission.hasPermission("users_update", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        User userModel = userRepository.findById(id);

        if (userModel == null) {
            response.setStatus(400);
            return new ApiResponseHelper<HashMap<String, Object>>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        userModel.setName(requestBody.getName());
        userModel.setEmail(requestBody.getEmail());
        userModel.setAddress(requestBody.getAddress());

        if (requestBody.getPhoto() != null) {

            if (!(requestBody.getPhoto()).contains(userModel.getPhoto())) {

            }

            if (userModel.getPhoto() != null) {
                Files.deleteIfExists(Paths.get(contextPath + userModel.getPhoto()));
            }

            File directory = new File(contextPath + "files/users/photos/");

            if (!directory.exists()) {
                directory.mkdirs();
            }

            byte[] bytes = Base64.getMimeDecoder().decode((requestBody.getPhoto().split(",")[1]));

            String filename = new Date().getTime()
                    + "." + RandomString.make(6)
                    + "." + Helpers.extractMimeType(requestBody.getPhoto()).split("/")[1];

            System.out.println("--- PHOTO_FILENAME " + filename);

            Path path = Paths.get(
                    contextPath
                            + "files/users/photos/"
                            + filename
            );

            Files.write(path, bytes);

            userModel.setPhoto("files/users/photos/" + filename);
        } else if (requestBody.getPhoto() == null) {
            Files.deleteIfExists(Paths.get(contextPath + userModel.getPhoto()));
            userModel.setPhoto(null);
        }

        userRepository.save(userModel);

        HashMap<String, Object> data = oMapper.convertValue(userModel, HashMap.class);

        return new ApiResponseHelper<>(data);
    }

    @RequestMapping(path = "/{id}/delete", method = RequestMethod.DELETE)
    public ApiResponseHelper<?> delete(
            @CurrentUser UserPrincipal currentUser,
            @PathVariable("id") long id,
            HttpServletRequest request,
            HttpServletResponse response
    ) {
        if (!checkPermission.hasPermission("users_delete", currentUser.getId())) {
            response.setStatus(403);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Permission Denied !"
            );
        }

        User user = userRepository.findById(id);

        if (user == null) {
            response.setStatus(400);
            return new ApiResponseHelper<>(
                    null,
                    false,
                    "Oops, Data not found !"
            );
        }

        user.setActive_flag(false);
        userRepository.save(user);

        return new ApiResponseHelper<>(
                null,
                true,
                "Awesome, successfully delete User !"
        );
    }

    @RequestMapping(path = "/delete/multiple", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteMultiple() {
    }

    @GetMapping("/user/me")
    public UserSummary getCurrentUser(@CurrentUser UserPrincipal currentUser) {
        UserSummary userSummary = new UserSummary(currentUser.getId(), currentUser.getUsername(), currentUser.getName());
        return userSummary;
    }

    @GetMapping("/user/checkUsernameAvailability")
    public UserIdentityAvailability checkUsernameAvailability(@RequestParam(value = "username") String username) {
        Boolean isAvailable = !userRepository.existsByUsername(username);
        return new UserIdentityAvailability(isAvailable);
    }

    @GetMapping("/user/checkEmailAvailability")
    public UserIdentityAvailability checkEmailAvailability(@RequestParam(value = "email") String email) {
        Boolean isAvailable = !userRepository.existsByEmail(email);
        return new UserIdentityAvailability(isAvailable);
    }

    @GetMapping("/users/{username}")
    public UserProfile getUserProfile(@PathVariable(value = "username") String username) {
        User user = userRepository.findByUsername(username)
                .orElseThrow(() -> new ResourceNotFoundException("User", "username", username));

        UserProfile userProfile = new UserProfile(user.getId(), user.getUsername(), user.getName());
//        UserProfile userProfile = new UserProfile(user.getId(), "", user.getName());

        return userProfile;
    }

}
