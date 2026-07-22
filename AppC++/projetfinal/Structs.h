#ifndef STRUCTS_H
#define STRUCTS_H

#include <string>

// Maps to the `chambre` table in agence_voyage
struct Room {
    int    id_chambre;
    int    id_hotel;
    std::string type;
    double prix;
    bool   disponible;

    Room()
        : id_chambre(0), id_hotel(0), type("Single"), prix(0.0), disponible(true) {}

    Room(int id, int hotel, const std::string& t, double p, bool dispo = true)
        : id_chambre(id), id_hotel(hotel), type(t), prix(p), disponible(dispo) {}
};


#endif // STRUCTS_H
