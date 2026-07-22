#ifndef DATABASE_H
#define DATABASE_H

#include "Structs.h"
#include <vector>
#include <string>
#include <mysql.h>

class Database {
public:
    // Pass an already-open MySQL connection
    explicit Database(MYSQL* connection);
    virtual ~Database();

    // (chambre table)
    bool loadRoomsFromDB();                          // select from chambre
    void addRoom(const Room& room);
    bool removeRoom(int id_chambre);
    bool updateRoomAvailability(int id_chambre, bool available);
    Room* findRoom(int id_chambre);
    std::vector<Room>& getRooms();
    const std::vector<Room>& getRooms() const;

private:
    MYSQL* conn;

    std::vector<Room>        rooms;



};

#endif // DATABASE_H
