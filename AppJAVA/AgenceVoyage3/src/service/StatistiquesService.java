public StatistiquesRapport analyserReservations() {
    StatistiquesRapport rapport = new StatistiquesRapport();
    try (Statement st = conn.createStatement()) {                    //object pour sql

        // total reservations
        ResultSet r1 = st.executeQuery("SELECT COUNT(*) FROM reservation");
        if (r1.next()) {
            rapport.nbReservations = r1.getInt(1);           // if (r1.next()) ila kan xi ster          r1.getInt(1)tjib awal colonne
        }

        // par statut
        ResultSet r2 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'Confirme'");
        if (r2.next()) {
            rapport.nbConfirmees = r2.getInt(1);
        }

        ResultSet r3 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'Annule'");
        if (r3.next()) {
            rapport.nbAnnulees = r3.getInt(1);
        }

        ResultSet r4 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'EnAttente'");
        if (r4.next()) {
            rapport.nbEnAttente = r4.getInt(1);
        }

        ResultSet r5 = st.executeQuery("SELECT COUNT(*) FROM reservation WHERE statut = 'Paye'");
        if (r5.next()) {
            rapport.nbPayees = r5.getInt(1);
        }

        // touristes, hotels, restaurants
        ResultSet r6 = st.executeQuery("SELECT COUNT(*) FROM touriste");
        if (r6.next()) {
            rapport.nbTouristes = r6.getInt(1);
        }

        ResultSet r7 = st.executeQuery("SELECT COUNT(*) FROM hotel");
        if (r7.next()) {
            rapport.nbHotels = r7.getInt(1);
        }

        ResultSet r8 = st.executeQuery("SELECT COUNT(*) FROM restaurant");
        if (r8.next()) {
            rapport.nbRestaurants = r8.getInt(1);
        }

    } catch (Exception e) {
        System.out.println("erreur analyserReservations : " + e.getMessage());
    }
    return rapport;
}
