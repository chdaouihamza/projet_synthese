package model;

// correspond a la table `reservation`
public class RapportReservation {

    public int    id;
    public String statut;     
    public String dateDebut;
    public String dateFin;
    public String nomTouriste; 
// constructeur remplire lobjet
    public RapportReservation(int id, String statut, String dateDebut, String dateFin, String nomTouriste) {
        this.id          = id;
        this.statut      = statut;
        this.dateDebut   = dateDebut;
        this.dateFin     = dateFin;
        this.nomTouriste = nomTouriste;
    }

    public String toString() {
        return "Reservation #" + id
             + " | touriste: "  + nomTouriste
             + " | statut: "    + statut
             + " | du: "        + dateDebut
             + " au: "          + dateFin;
    }
}