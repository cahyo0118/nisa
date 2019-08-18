package com.orraa.demo.controller;

import com.orraa.demo.model.Book;
import com.orraa.demo.repository.BookRepository;
import com.orraa.demo.util.ApiResponseHelper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/books")
@PreAuthorize("isFullyAuthenticated()")
public class BookController {

    @Autowired
    private BookRepository bookRepository;

    @RequestMapping()
    public ApiResponseHelper<List<Book>> getAll() {
        return new ApiResponseHelper<List<Book>>((List<Book>) bookRepository.findAll());
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<Book> getBookById(@PathVariable("id") int id) {
        return new ApiResponseHelper<Book>(bookRepository.findById(id));
    }

    @RequestMapping(path = "/keyword/{id}", method = RequestMethod.GET)
    public ApiResponseHelper<List<Book>> getBookByKeyword(@PathVariable("id") String id) {
        return new ApiResponseHelper<List<Book>>(bookRepository.findByName(id));
    }

    @RequestMapping(method = RequestMethod.POST)
    public ApiResponseHelper<Book> createBook(@RequestBody Book book) {
        return new ApiResponseHelper<Book>(bookRepository.save(book));
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.PUT)
    public ApiResponseHelper<Book> updateBook(@PathVariable("id") int id, @RequestBody Book book) {

        Book c = bookRepository.findById(id);

        if (c != null) {
            c.name = book.name;
        }

        return new ApiResponseHelper<Book>(bookRepository.save(c));
    }

    @RequestMapping(path = "/{id}", method = RequestMethod.DELETE)
    @ResponseStatus(value = HttpStatus.NO_CONTENT)
    public void deleteBook(@PathVariable("id") int id) {
        bookRepository.delete(new Book(id));
    }
}
