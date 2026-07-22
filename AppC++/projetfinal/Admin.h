#ifndef ADMIN_H
#define ADMIN_H

#include "Database.h"
#include <string>

class Admin {
public:
    Admin(Database& db, const std::string& password = "admin123");
    virtual ~Admin();

    bool login(const std::string& password) const;

    // id_hotel is required by the chambre table (FK)
    void addRoom(int id_hotel, const std::string& type, double prix);
    void removeRoom(int id_chambre);
    void toggleAvailability(int id_chambre, bool available);
    void listAllRooms() const;

private:
    Database& database;
    std::string adminPassword;
};

#endif // ADMIN_H
