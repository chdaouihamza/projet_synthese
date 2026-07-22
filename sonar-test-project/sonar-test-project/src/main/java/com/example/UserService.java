package com.example;

import java.util.ArrayList;
import java.util.List;

/**
 * This class intentionally contains a few common code smells / bugs
 * so that SonarQube has something concrete to detect during analysis.
 */
public class UserService {

    private List<String> users = new ArrayList<>();

    // Code smell: password should never be hardcoded (Sonar security hotspot)
    private String adminPassword = "admin123";

    public void addUser(String name) {
        users.add(name);
    }

    public String findUser(String name) {
        for (int i = 0; i < users.size(); i++) {
            if (users.get(i).equals(name)) {
                return users.get(i);
            }
        }
        return null;
    }

    // Bug: empty catch block, exception swallowed silently
    public void riskyOperation() {
        try {
            int result = 10 / 0;
        } catch (ArithmeticException e) {
        }
    }

    // Code smell: unused variable
    public int computeSomething(int value) {
        int unusedVariable = 42;
        return value * 2;
    }

    // Duplicated logic on purpose (Sonar duplication detector)
    public boolean isValidNameA(String name) {
        return name != null && !name.isEmpty() && name.length() < 50;
    }

    public boolean isValidNameB(String name) {
        return name != null && !name.isEmpty() && name.length() < 50;
    }

    public String getAdminPassword() {
        return adminPassword;
    }
}
