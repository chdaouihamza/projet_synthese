package service;

import database.Database;
import model.RapportReservation;
import model.StatistiquesRapport;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

public class StatistiquesService {

    Connection conn = Database.getConnection();

    // creer un rapport complet avec tous les comptages
    
    
    public StatistiquesRapport analyserReservations() {
        StatistiquesRapport rapport = new StatistiquesRapport();
        try {
            Statement st = conn.createStatement();                    //object pour sql

            // total reservations
            ResultSet r1 = st.executeQuery("SELECT COUNT(*) FROM reservation");
            if (r1.next()) rapport.nbReservations = r1.getInt(1);           // if (r1.next()) ila kan xi ster          r1.getInt(1)tjib awal colonne

            // par statut
            ResultSet r2 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'Confirme'");
            if (r2.next()) rapport.nbConfirmees = r2.getInt(1);

            ResultSet r3 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'Annule'");
            if (r3.next()) rapport.nbAnnulees = r3.getInt(1);

            ResultSet r4 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'EnAttente'");
            if (r4.next()) rapport.nbEnAttente = r4.getInt(1);

            ResultSet r5 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'Paye'");
            if (r5.next()) rapport.nbPayees = r5.getInt(1);

            // touristes, hotels, restaurants
            ResultSet r6 = st.executeQuery("SELECT COUNT(*) FROM touriste");
            if (r6.next()) rapport.nbTouristes = r6.getInt(1);

            ResultSet r7 = st.executeQuery("SELECT COUNT(*) FROM hotel");
            if (r7.next()) rapport.nbHotels = r7.getInt(1);

            ResultSet r8 = st.executeQuery("SELECT COUNT(*) FROM restaurant");
            if (r8.next()) rapport.nbRestaurants = r8.getInt(1);

        } catch (Exception e) {
            System.out.println("erreur analyserReservations : " + e.getMessage());
        }
        return rapport;
    }

    // compter les reservations par statut
    public int countByStatus(String statut) {
        int count = 0;
        try {
            PreparedStatement ps = conn.prepareStatement(                   //protecter mn sql injection
                "SELECT COUNT(*) FROM reservation WHERE statut = ?");
            ps.setString(1, statut);
            ResultSet res = ps.executeQuery();
            if (res.next()) count = res.getInt(1);
        } catch (Exception e) {
            System.out.println("erreur countByStatus : " + e.getMessage());
        }
        return count;
    }

    // recuperer toutes les reservations avec le nom du touriste
    public List<RapportReservation> getReservations() {
        List<RapportReservation> liste = new ArrayList<RapportReservation>();           //List<RapportReservation> liste   cree liste vide
        try {
            Statement st = conn.createStatement();
            ResultSet res = st.executeQuery(
                "SELECT r.id_reservation, r.statut, r.date_debut, r.date_fin, " +
                "t.nom, t.prenom " +
                "FROM reservation r " +
                "JOIN touriste t ON r.id_touriste = t.id_touriste " +
                "ORDER BY r.id_reservation DESC");

            while (res.next()) {                      //kaddor 3la ge3 row's
                String nomTouriste = res.getString("prenom") + " " + res.getString("nom");
                liste.add(new RapportReservation(            //transforme row l object
                    res.getInt("id_reservation"),
                    res.getString("statut"),
                    res.getString("date_debut"),
                    res.getString("date_fin"),
                    nomTouriste
                ));
            }
        } catch (Exception e) {
            System.out.println("erreur getReservations : " + e.getMessage());
        }
        return liste;
    }

    // recuperer les reservations d'un seul touriste
    public List<RapportReservation> getReservationsByTouriste(int idTouriste) {
        List<RapportReservation> liste = new ArrayList<RapportReservation>();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT r.id_reservation, r.statut, r.date_debut, r.date_fin, " +
                "t.nom, t.prenom " +
                "FROM reservation r " +
                "JOIN touriste t ON r.id_touriste = t.id_touriste " +
                "WHERE r.id_touriste = ?");
            ps.setInt(1, idTouriste);
            ResultSet res = ps.executeQuery();

            while (res.next()) {
                String nomTouriste = res.getString("prenom") + " " + res.getString("nom");
                liste.add(new RapportReservation(
                    res.getInt("id_reservation"),
                    res.getString("statut"),
                    res.getString("date_debut"),
                    res.getString("date_fin"),
                    nomTouriste
                ));
            }
        } catch (Exception e) {
            System.out.println("erreur getReservationsByTouriste : " + e.getMessage());
        }
        return liste;
    }
}