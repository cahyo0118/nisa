/*Generate File*/
package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.model.Category;
import com.orraa.demo.repository.BookRepository;
import com.orraa.demo.repository.CategoryRepository;
import com.orraa.demo.service.CategoryService;
import com.orraa.demo.util.ApiResponseHelper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.jdbc.core.namedparam.NamedParameterJdbcTemplate;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import java.util.*;

@RestController
@RequestMapping("/api/categories")
@PreAuthorize("isFullyAuthenticated()")
public class CategoryController {

    @Autowired
    private CategoryRepository categoryRepository;

    @Autowired
    private CategoryService categoryService;

    @Autowired
    private BookRepository bookRepository;

//    @RequestMapping(method = RequestMethod.GET)
//    @PreAuthorize("hasRole('ADMIN')")
//    public ApiResponseHelper<List<Category>> getAll(
//            @RequestParam(value = "with", required = false) String with
//    ) throws Exception {
////        ObjectMapper oMapper = new ObjectMapper();
//        List<Category> categories = (List<Category>) categoryRepository.findAll();
//        return new ApiResponseHelper<List<Category>>(categories);
//    }

    @RequestMapping(method = RequestMethod.GET)
    @PreAuthorize("hasRole('ADMIN')")
    public ApiResponseHelper<List<HashMap<String, Object>>> getAll(
            @RequestParam(value = "with", required = false) String with
    ) throws Exception {
        ObjectMapper oMapper = new ObjectMapper();
        List<Category> categories = (List<Category>) categoryRepository.findAll();
        List<HashMap<String, Object>> data = new ArrayList<>();

        categories.forEach(category -> {
            HashMap<String, Object> o = oMapper.convertValue(category, HashMap.class);

            // Add keys and values (Country, City)
            o.put("England", "London");
            o.put("Germany", "Berlin");
            o.put("Norway", "Oslo");
            o.put("USA", "Washington DC");
            o.put("ss", 3);

            data.add(o);
        });

        return new ApiResponseHelper<List<HashMap<String, Object>>>(data);
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<Category> getCategoryById(@PathVariable("id") int id) {
        return new ApiResponseHelper<Category>(categoryRepository.findById(id));
    }

    @RequestMapping(path = "/keyword/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<List<Category>> getCategoryByKeyword(@PathVariable("id") String id) {
        return new ApiResponseHelper<List<Category>>(categoryRepository.findByContent(id));
    }

    @RequestMapping(method = RequestMethod.POST)
    public ApiResponseHelper<Category> createCategory(@RequestBody Category category) {
        return new ApiResponseHelper<Category>(categoryRepository.save(category));
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.PUT)
    public ApiResponseHelper<Category> updateCategory(@PathVariable("id") int id, @RequestBody Category category) {

        Category c = categoryRepository.findById(id);

        if (c != null) {
            c.content = category.content;
        }

        return new ApiResponseHelper<Category>(categoryRepository.save(c));
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteCategory(@PathVariable("id") int id) {
        categoryRepository.delete(new Category(id));
    }
}
