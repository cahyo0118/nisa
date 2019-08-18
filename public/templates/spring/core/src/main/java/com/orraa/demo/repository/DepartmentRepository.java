package com.orraa.demo.repository;

import com.orraa.demo.model.Category;
import com.orraa.demo.model.Department;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface DepartmentRepository extends CrudRepository<Department, Long> {

    Department findById(long id);

    List<Department> findAll(Pageable pageable);

    @Query(value = "SELECT count(d) FROM Department d")
    Long countAll();

    @Query(value = "SELECT *, (SELECT COUNT(*) FROM departments) FROM departments", nativeQuery = true)
    List<Department> someQuery(Pageable pageable);

    @Query("SELECT d FROM Department d WHERE lower(d.name) LIKE lower(concat('%', :keyword, '%'))")
    List<Department> findByKeyword(@Param("keyword") String keyword, Pageable pageable);

}
