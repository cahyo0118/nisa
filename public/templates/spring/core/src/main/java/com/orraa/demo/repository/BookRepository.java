package com.orraa.demo.repository;

import com.orraa.demo.model.Book;
import com.orraa.demo.model.Category;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface BookRepository extends JpaRepository<Book, Integer> {

    Book findById(int id);


//    @Query("SELECT A, COUNT(B.id) FROM categories A INNER JOIN books B ON B.category_id=A.id GROUP BY A.id")

    @Query("SELECT b FROM Book b WHERE b.name LIKE %?1%")
    List<Book> findByName(String name);

    @Query("SELECT b FROM Book b WHERE b.category = ?1")
    List<Book> findByCategoryId(Category category);

}
