package database;

import java.sql.Connection;
import java.sql.DriverManager;

public class Database {
    private static Connection conn = null;

    private static final String url  = "jdbc:mysql://localhost:3306/agence_voyage";
    private static final String user = System.getenv().getOrDefault("DB_USER", "root");
    private static final String password = System.getenv("DB_PASSWORD");

    public static Connection getConnection() {
        if (conn == null) {
            try {
                Class.forName("com.mysql.cj.jdbc.Driver");
                conn = DriverManager.getConnection(url, user, password);
                System.out.println("connexion a la base de donnees reussie !");
            } catch (Exception e) {
                System.out.println("erreur de connexion : " + e.getMessage());
            }
        }
        return conn;
    }
}
