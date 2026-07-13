package model;

// stocke les statistiques calculees depuis la base de donnees
public class StatistiquesRapport {

    public int nbReservations;
    public int nbConfirmees;
    public int nbAnnulees;
    public int nbEnAttente;
    public int nbPayees;
    public int nbTouristes;
    public int nbHotels;
    public int nbRestaurants;

    public String toString() {                                       //kay recuperer les donnes bxkel mrtteb pour laffichage
        return "=== RAPPORT STATISTIQUES ===\n"
             + "Total reservations : " + nbReservations + "\n"
             + "  - Confirmees     : " + nbConfirmees   + "\n"
             + "  - En attente     : " + nbEnAttente    + "\n"
             + "  - Annulees       : " + nbAnnulees     + "\n"
             + "  - Payees         : " + nbPayees       + "\n"
             + "Total touristes    : " + nbTouristes    + "\n"
             + "Total hotels       : " + nbHotels       + "\n"
             + "Total restaurants  : " + nbRestaurants;
    }
}