package com.orraa.demo.repository;

import com.orraa.demo.model.Role;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface RoleRepository extends CrudRepository<Role, Long> {

    Role findById(long id);

    @Query("SELECT r FROM Role r WHERE r.name = :name")
    Role findByName(@Param("name") String name);

    @Query("SELECT r FROM Role r WHERE r.active_flag = true")
    List<Role> findAll(Pageable pageable);

    @Query(value = "SELECT count(r) FROM Role r")
    Long countAll();

    @Query("SELECT d FROM Department d WHERE lower(d.name) LIKE lower(concat('%', :keyword, '%'))")
    List<Role> findByKeyword(@Param("keyword") String keyword, Pageable pageable);
}
