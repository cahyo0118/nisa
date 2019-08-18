package com.orraa.demo.repository;

import com.orraa.demo.model.Category;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface CategoryRepository extends CrudRepository<Category, Integer> {

    Category findById(int id);

    @Query("SELECT c FROM Category c WHERE c.content LIKE %?1%")
    List<Category> findByContent(String content);

}
