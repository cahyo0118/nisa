package com.orraa.demo.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.orraa.demo.repository.UserProfileRepository;
import com.orraa.demo.util.Helpers;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.MediaType;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.InputStream;

@RestController
@RequestMapping("/files")
//@PreAuthorize("isFullyAuthenticated()")
public class FileController {

    @Autowired
    private UserProfileRepository userProfileRepository;

    @Value("${contextPath}")
    private String contextPath;

    private ObjectMapper oMapper = new ObjectMapper();

    @RequestMapping(value = "/**", method = RequestMethod.GET, produces = MediaType.IMAGE_JPEG_VALUE)
    @PreAuthorize("permitAll()")
    public @ResponseBody
    byte[] getFile(
//            @PathVariable("filePath") String filePath,
            HttpServletRequest request,
            HttpServletResponse response
    ) throws IOException {
        String requestURL = request.getRequestURL().toString();

        String moduleName = requestURL.split("/files/")[1];
//        InputStream in = getClass().getResourceAsStream(contextPath + moduleName);
        System.out.println("-- NON API REQUEST " + moduleName);
        byte[] bFile = Helpers.readBytesFromFile(contextPath + "files/" + moduleName);

        return bFile;

    }

}
