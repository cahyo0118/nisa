package com.orraa.demo.model;

import javax.persistence.*;

@Entity
@Table(name = "role_permission", uniqueConstraints = @UniqueConstraint(columnNames = { "role_id", "permission_id" }))
public class RolePermission {

    @Id
    @GeneratedValue(strategy = GenerationType.SEQUENCE)
    @Column(columnDefinition = "serial")
    private Long id;

    @ManyToOne
    @JoinColumn(name = "role_id", nullable = false)
    private Role role = new Role();

    @ManyToOne
    @JoinColumn(name = "permission_id", nullable = false)
    private Permission permission = new Permission();

    public RolePermission() {
    }

    public RolePermission(Long roleId, Long permissionId) {
        this.role = new Role(roleId);
        this.permission = new Permission(permissionId);
    }

    public RolePermission(Role role, Permission permission) {
        this.role = role;
        this.permission = permission;
    }

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Permission getPermission() {
        return permission;
    }

    public void setPermission(Permission permission) {
        this.permission = permission;
    }
}
