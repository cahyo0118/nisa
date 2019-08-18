package com.orraa.demo.util;

import com.orraa.demo.model.RolePermission;
import com.orraa.demo.model.UserRole;
import com.orraa.demo.repository.RolePermissionRepository;
import com.orraa.demo.repository.UserRoleRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;

@Service
public class CheckPermission {

    @Autowired
    private UserRoleRepository userRoleRepository;

    @Autowired
    private RolePermissionRepository rolePermissionRepository;

    public boolean hasPermission(String permissions, Long currentUserId) {
        boolean isGranted = false;
        List<String> requiredPermissions = Arrays.asList(permissions.split("\\|"));

//        System.out.println("--- CURRENT USER ID == " + currentUserId);

        List<String> currentUserPermissions = new ArrayList<String>();

        List<UserRole> roles = userRoleRepository.findByUserId(currentUserId);

        roles.forEach(role -> {
            List<RolePermission> permissionsData = rolePermissionRepository.findByRoleId(role.getRole().getId());
            permissionsData.forEach(permission -> currentUserPermissions.add(permission.getPermission().getName()));
        });

        for (String permission : requiredPermissions) {
            if (currentUserPermissions.contains(permission)) {
                isGranted = true;
            }
        }

        return isGranted;
    }
}
