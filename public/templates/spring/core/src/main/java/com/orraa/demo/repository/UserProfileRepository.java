package com.orraa.demo.repository;

import com.orraa.demo.model.Category;
import com.orraa.demo.model.User;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface UserProfileRepository extends CrudRepository<User, Integer> {

    User findById(Long id);

    @Query("SELECT c FROM Category c WHERE c.content LIKE %?1%")
    List<Category> findByContent(String content);

}