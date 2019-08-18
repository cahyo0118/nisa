package com.orraa.demo.repository;

import com.orraa.demo.model.Department;
import com.orraa.demo.model.User;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Repository
@Transactional
public interface UserRepository extends JpaRepository<User, Long> {

    Optional<User> findById(Long id);

    Optional<User> findByEmail(String email);

    Optional<User> findByUsernameOrEmail(String username, String email);

    List<User> findByIdIn(List<Long> userIds);

    Optional<User> findByUsername(String username);

    Boolean existsByUsername(String username);

    Boolean existsByEmail(String email);

    User findById(long id);

    @Query(value = "SELECT u FROM User u WHERE u.email = :email")
    User findUserByEmail(@Param("email") String email);

    @Query(value = "SELECT u FROM User u")
    List<User> findAllA(Pageable pageable);

    @Query(value = "SELECT count(u) FROM User u")
    Long countAll();

    @Query(value = "SELECT *, (SELECT COUNT(*) FROM departments) FROM departments", nativeQuery = true)
    List<User> someQuery(Pageable pageable);

    @Query("SELECT u FROM User u WHERE lower(u.name) LIKE lower(concat('%', :keyword, '%'))")
    List<User> findByKeyword(@Param("keyword") String keyword, Pageable pageable);
}
