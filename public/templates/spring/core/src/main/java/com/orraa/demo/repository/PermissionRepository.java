package com.orraa.demo.repository;

import com.orraa.demo.model.Permission;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface PermissionRepository extends CrudRepository<Permission, Long> {

    Permission findById(long id);

    List<Permission> findAll();

    List<Permission> findAll(Pageable pageable);

    @Query("SELECT p FROM Permission p WHERE p.name = :name")
    Permission findByName(@Param("name") String name);

    @Query(value = "SELECT count(p) FROM Permission p")
    Long countAll();

    @Query("SELECT d FROM Department d WHERE lower(d.name) LIKE lower(concat('%', :keyword, '%'))")
    List<Permission> findByKeyword(@Param("keyword") String keyword, Pageable pageable);

}
