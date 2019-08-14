package com.orraa.demo.repository;

import com.orraa.demo.model.{!! ucfirst(camel_case(str_singular($table->name))) !!};
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface {!! ucfirst(camel_case(str_singular($table->name))) !!}Repository extends CrudRepository<{!! ucfirst(camel_case(str_singular($table->name))) !!}, Long> {

    {!! ucfirst(camel_case(str_singular($table->name))) !!} findById(long id);

    List<{!! ucfirst(camel_case(str_singular($table->name))) !!}> findAll();

    List<{!! ucfirst(camel_case(str_singular($table->name))) !!}> findAll(Pageable pageable);

    @Query(value = "SELECT count(d) FROM {!! ucfirst(camel_case(str_singular($table->name))) !!} d")
    Long countAll();

    {{--@Query("SELECT d FROM {!! ucfirst(camel_case(str_singular($table->name))) !!} d WHERE lower(d.name) LIKE lower(concat('%', :keyword, '%'))")--}}
    @Query("SELECT d FROM {!! ucfirst(camel_case(str_singular($table->name))) !!} d")
    List<{!! ucfirst(camel_case(str_singular($table->name))) !!}> findByKeyword(@Param("keyword") String keyword, Pageable pageable);

@if($table->name == "permissions")
    @Query("SELECT p FROM Permission p WHERE p.name = :name")
    Permission findByName(@Param("name") String name);

@endif
@if($table->name == "roles")
    @Query("SELECT r FROM Role r WHERE r.name = :name")
    Role findByName(@Param("name") String name);

@endif
@if($table->name == "users")
    @Query(value = "SELECT u FROM User u WHERE u.email = :email")
    User findUserByEmail(@Param("email") String email);

    Optional<User> findById(Long id);

    Optional<User> findByEmail(String email);

    List<User> findByIdIn(List<Long> userIds);

    Boolean existsByEmail(String email);
@endif

}
