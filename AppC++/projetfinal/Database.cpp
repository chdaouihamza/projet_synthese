#include "Database.h"
#include <iostream>
#include <sstream>
#include <algorithm>

Database::Database(MYSQL* connection)
    : conn(connection)
{
    if (!loadRoomsFromDB()) {                                                              //direct m3a DB
        std::cerr << "[Database] Warning: could not load rooms from DB.\n";
    }
}

Database::~Database() {}

// ---- Rooms ----

bool Database::loadRoomsFromDB() {
    rooms.clear();

    const char* query =
        "SELECT id_chambre, id_hotel, type, prix, disponible "
        "FROM chambre";

    if (mysql_query(conn, query)) {
        std::cerr << "[DB Error] loadRoomsFromDB: " << mysql_error(conn) << "\n";
        return false;
    }

    MYSQL_RES* result = mysql_store_result(conn);
    if (!result) {
        std::cerr << "[DB Error] mysql_store_result: " << mysql_error(conn) << "\n";
        return false;
    }

    MYSQL_ROW row;                                                                   // kat9ra ster bster
    while ((row = mysql_fetch_row(result))) {                                        //mysql_fetch_row  katjiw ster 1 koll mrra
        Room r;
        r.id_chambre  = row[0] ? std::stoi(row[0]) : 0;
        r.id_hotel    = row[1] ? std::stoi(row[1]) : 0;
        r.type        = row[2] ? row[2] : "";
        r.prix        = row[3] ? std::stod(row[3]) : 0.0;
        r.disponible  = row[4] ? (std::stoi(row[4]) != 0) : true;
        rooms.push_back(r);
    }

    mysql_free_result(result);
    std::cout << "[Database] Loaded " << rooms.size() << " room(s) from chambre.\n";
    return true;
}

void Database::addRoom(const Room& room) {
    // INSERT into DB
    std::ostringstream oss;                                                              // cree sql query dune maniere dynamique b7al string builder
    oss << "INSERT INTO chambre (id_hotel, type, prix, disponible) VALUES ("
        << room.id_hotel << ", '"
        << room.type     << "', "
        << room.prix     << ", "
        << (room.disponible ? 1 : 0) << ")";

    if (mysql_query(conn, oss.str().c_str())) {                                          //oss.str  = ostringstream t ostring
        std::cerr << "[DB Error] addRoom: " << mysql_error(conn) << "\n";
        return;
    }

    // Reload rooms so in-memory list reflects real DB (gets the auto-increment id)
    loadRoomsFromDB();
}

bool Database::removeRoom(int id_chambre) {
    std::ostringstream oss;
    oss << "DELETE FROM chambre WHERE id_chambre = " << id_chambre;

    if (mysql_query(conn, oss.str().c_str())) {
        std::cerr << "[DB Error] removeRoom: " << mysql_error(conn) << "\n";
        return false;
    }

    if (mysql_affected_rows(conn) == 0)
        return false;   // nothing deleted

    // Remove from in-memory list too
    auto it = std::find_if(rooms.begin(), rooms.end(),
        [id_chambre](const Room& r){ return r.id_chambre == id_chambre; });
    if (it != rooms.end()) rooms.erase(it);

    return true;
}

bool Database::updateRoomAvailability(int id_chambre, bool available) {
    std::ostringstream oss;
    oss << "UPDATE chambre SET disponible = " << (available ? 1 : 0)
        << " WHERE id_chambre = " << id_chambre;

    if (mysql_query(conn, oss.str().c_str())) {
        std::cerr << "[DB Error] updateRoomAvailability: " << mysql_error(conn) << "\n";
        return false;
    }

    // Sync in-memory
    Room* r = findRoom(id_chambre);
    if (r) r->disponible = available;
    return true;
}

Room* Database::findRoom(int id_chambre) {
    for (auto& r : rooms)
        if (r.id_chambre == id_chambre) return &r;
    return nullptr;
}

std::vector<Room>& Database::getRooms()             { return rooms; }
const std::vector<Room>& Database::getRooms() const { return rooms; }



