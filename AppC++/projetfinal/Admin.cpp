#include "Admin.h"
#include <iostream>
#include <iomanip>

Admin::Admin(Database& db, const std::string& password)
    : database(db), adminPassword(password) {}              //initialisation list . les membres cree directement b les valeur final.  tari9a lwa7ida bax t3amel m3a references o const

Admin::~Admin() {}

bool Admin::login(const std::string& password) const {
    return password == adminPassword;
}

void Admin::addRoom(int id_hotel, const std::string& type, double prix) {
    // id_chambre is assigned by MySQL AUTO_INCREMENT; pass 0 as placeholder
    database.addRoom(Room(0, id_hotel, type, prix, true));              // cree objet room and send it to DB       0 7it mysql autoincrement howa y3tiha id
    std::cout << "[Admin] Room (" << type << ") added at "
              << std::fixed << std::setprecision(2) << prix
              << " MAD/night for hotel #" << id_hotel << ".\n";
}

void Admin::removeRoom(int id_chambre) {
    if (database.removeRoom(id_chambre))
        std::cout << "[Admin] Chambre #" << id_chambre << " removed.\n";
    else
        std::cout << "[Error] Chambre #" << id_chambre << " not found.\n";
}

void Admin::toggleAvailability(int id_chambre, bool available) {
    if (database.updateRoomAvailability(id_chambre, available))
        std::cout << "[Admin] Chambre #" << id_chambre
                  << (available ? " set to Available.\n" : " set to Occupied.\n");
    else
        std::cout << "[Error] Could not update chambre #" << id_chambre << ".\n";
}

void Admin::listAllRooms() const {
    const auto& rooms = database.getRooms();                                                  // auto katkhlli compiler y7edded no3
    if (rooms.empty()) {
        std::cout << "[Info] No rooms in database.\n";
        return;
    }

    std::cout << "\n--- Chambres (from DB) ---\n"
              << std::left
              << std::setw(6)  << "ID"
              << std::setw(8)  << "Hotel"
              << std::setw(12) << "Type"
              << std::setw(14) << "Prix/nuit"
              << "Disponible\n"
              << std::string(50, '-') << "\n";

    for (const auto& r : rooms) {
        std::cout << std::setw(6)  << r.id_chambre
                  << std::setw(8)  << r.id_hotel
                  << std::setw(12) << r.type
                  << std::setw(14) << (std::to_string((int)r.prix) + " MAD")
                  << (r.disponible ? "Oui" : "Non") << "\n";
    }
    std::cout << "\n";
}
