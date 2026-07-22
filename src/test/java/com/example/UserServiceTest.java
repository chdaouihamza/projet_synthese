package com.example;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNull;
import static org.junit.jupiter.api.Assertions.assertTrue;

class UserServiceTest {

    @Test
    void testAddAndFindUser() {
        UserService service = new UserService();
        service.addUser("Mohammed");
        assertEquals("Mohammed", service.findUser("Mohammed"));
    }

    @Test
    void testFindUserNotFound() {
        UserService service = new UserService();
        assertNull(service.findUser("Unknown"));
    }

    @Test
    void testComputeSomething() {
        UserService service = new UserService();
        assertEquals(20, service.computeSomething(10));
    }

    @Test
    void testValidNames() {
        UserService service = new UserService();
        assertTrue(service.isValidNameA("Test"));
        assertTrue(service.isValidNameB("Test"));
    }
}
