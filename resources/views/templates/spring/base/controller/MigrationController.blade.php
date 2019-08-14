package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.*;
import com.orraa.demo.repository.*;
import com.orraa.demo.security.CurrentUser;
import com.orraa.demo.security.UserPrincipal;
import com.orraa.demo.util.ApiResponseHelper;
import com.orraa.demo.util.CheckPermission;
import net.bytebuddy.utility.RandomString;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/")
public class MigrationController {

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private RoleRepository roleRepository;

    @Autowired
    private UserRoleRepository userRoleRepository;

    @Autowired
    private PermissionRepository permissionRepository;

    @Autowired
    private RolePermissionRepository rolePermissionRepository;

    @Autowired
    PasswordEncoder passwordEncoder;

    @Value("${contextPath}")
    private String contextPath;

    @Value("${item-per-page}")
    private int itemPerPage;

    private ObjectMapper oMapper = new ObjectMapper();

    @Autowired
    private CheckPermission checkPermission;

    @RequestMapping(path = "/migrate", method = RequestMethod.POST)
    public ApiResponseHelper<{!! "?" !!}> migrate() {
        User user = userRepository.findUserByEmail("admin@mail.com");

        if (user == null) {
            user = new User();

            user.setName("Administrator");
            user.setEmail("admin@mail.com");
            user.setAddress("Kendal");
            user.setPassword(passwordEncoder.encode("123456"));
            user.setActiveFlag(true);
            user.setUpdatedBy(0);
            user = userRepository.save(user);
        }

        Role role = roleRepository.findByName("administrator");

        if (role == null) {
            role = new Role();

            role.setName("administrator");
            role.setDescription("Administrator");
            role = roleRepository.save(role);

        }

        if (userRoleRepository.findByUserIdAndRoleId(user.getId(), role.getId()) == null) {
            userRoleRepository.save(new UserRole(user.getId(), role.getId()));
        }

        createPermissionAttachToRole(role.getId(), "users_create", "Users Create");
        createPermissionAttachToRole(role.getId(), "users_read", "Users Read");
        createPermissionAttachToRole(role.getId(), "users_update", "Users Update");
        createPermissionAttachToRole(role.getId(), "users_delete", "Users Delete");

        createPermissionAttachToRole(role.getId(), "roles_create", "Roles Create");
        createPermissionAttachToRole(role.getId(), "roles_read", "Roles Read");
        createPermissionAttachToRole(role.getId(), "roles_update", "Roles Update");
        createPermissionAttachToRole(role.getId(), "roles_delete", "Roles Delete");

        createPermissionAttachToRole(role.getId(), "permissions_create", "Permissions Create");
        createPermissionAttachToRole(role.getId(), "permissions_read", "Permissions Read");
        createPermissionAttachToRole(role.getId(), "permissions_update", "Permissions Update");
        createPermissionAttachToRole(role.getId(), "permissions_delete", "Permissions Delete");

@foreach($project->menus as $menu)
        createPermissionAttachToRole(role.getId(), "{{ snake_case(str_plural($menu->name)) }}_create", "Create {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}");
        createPermissionAttachToRole(role.getId(), "{{ snake_case(str_plural($menu->name)) }}_read", "Read {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}");
        createPermissionAttachToRole(role.getId(), "{{ snake_case(str_plural($menu->name)) }}_update", "Update {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}");
        createPermissionAttachToRole(role.getId(), "{{ snake_case(str_plural($menu->name)) }}_delete", "Delete {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}");

@endforeach
        return new ApiResponseHelper<>(null);
    }

    public Permission createPermissionAttachToRole(Long roleId, String permissionName, String permissionDescription) {
        Permission permission = permissionRepository.findByName(permissionName);

        if (permission == null) {
            permission = new Permission();

            permission.setName(permissionName);
            permission.setDescription(permissionDescription);

            permission = permissionRepository.save(permission);
        }

        if (rolePermissionRepository.findByRoleIdAndPermissionId(roleId, permission.getId()) == null) {
            rolePermissionRepository.save(new RolePermission(roleId, permission.getId()));
        }

        return permission;
    }
}
