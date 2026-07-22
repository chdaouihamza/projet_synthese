#include <iostream>
#include <string>
#include <mysql.h>
#include "Database.h"
#include "Admin.h"


int main() {
    // ---- 1. Connect to MySQL ----
    MYSQL* conn = mysql_init(nullptr);
    if (!conn) {
        std::cerr << "mysql_init() failed\n";
        return 1;
    }

    if (!mysql_real_connect(conn,
            "localhost",
            "root",
            "",
            "agence_voyage",
            3306,
            nullptr, 0)) {
        std::cerr << "Connection failed: " << mysql_error(conn) << "\n";
        mysql_close(conn);
        return 1;
    }
    std::cout << "Connected to MySQL " << mysql_get_server_info(conn) << "\n\n";

    // Build Database & Admin (rooms loaded from chambre table)
    Database db(conn);
    Admin    admin(db, "admin123");

    //  Admin login
    std::string pwd;
    std::cout << "Enter admin password: ";
    std::cin  >> pwd;
    if (!admin.login(pwd)) {
        std::cerr << "Wrong password. Exiting.\n";
        mysql_close(conn);
        return 1;
    }
    std::cout << "Login successful!\n\n";

    //  Menu loop
    int choice = 0;
    do {
        std::cout << "========= Hotel Management =========\n"
                  << " 1. List all rooms (chambre)\n"
                  << " 2. Add a room\n"
                  << " 3. Remove a room\n"
                  << " 4. Toggle room availability\n"
                  << " 5. Reload rooms from DB\n"
                  << " 0. Exit\n"
                  << "====================================\n"
                  << "Choice: ";
        std::cin >> choice;

        switch (choice) {
        case 1:
            admin.listAllRooms();
            break;

        case 2: {
            int    id_hotel;
            std::string type;
            double prix;
            std::cout << "Hotel ID: ";      std::cin >> id_hotel;
            std::cout << "Type (Single/Double/Suite): "; std::cin >> type;
            std::cout << "Prix/nuit (MAD): "; std::cin >> prix;
            admin.addRoom(id_hotel, type, prix);
            break;
        }

        case 3: {
            int id;
            std::cout << "Chambre ID to remove: "; std::cin >> id;
            admin.removeRoom(id);
            break;
        }

        case 4: {
            int  id;
            char flag;
            std::cout << "Chambre ID: "; std::cin >> id;
            std::cout << "Available? (y/n): "; std::cin >> flag;
            admin.toggleAvailability(id, (flag == 'y' || flag == 'Y'));
            break;
        }

        case 5:
            db.loadRoomsFromDB();
            std::cout << "[Info] Rooms reloaded.\n";
            break;

        case 0:
            std::cout << "Goodbye!\n";
            break;

        default:
            std::cout << "Invalid choice.\n";
        }

    } while (choice != 0);

    mysql_close(conn);
    return 0;
}
