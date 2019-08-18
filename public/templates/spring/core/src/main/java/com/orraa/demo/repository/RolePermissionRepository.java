package com.orraa.demo.repository;

import com.orraa.demo.model.RolePermission;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Repository
public interface RolePermissionRepository extends CrudRepository<RolePermission, Long> {

    @Query(value = "SELECT rp FROM RolePermission rp WHERE rp.role.id = :roleId")
    List<RolePermission> findAllByRoleId(@Param("roleId") Long roleId);

    @Query(value = "SELECT rp FROM RolePermission rp WHERE rp.role.id = :roleId AND rp.permission.id = :permissionId")
    RolePermission findByRoleIdAndPermissionId(@Param("roleId") Long roleId, @Param("permissionId") Long permissionId);

    @Transactional
    @Modifying
    @Query(value = "DELETE FROM RolePermission rp WHERE rp.role.id = :roleId")
    void deleteAllByRoleRoleId(@Param("roleId") Long roleId);

}