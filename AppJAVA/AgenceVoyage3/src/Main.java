import database.Database;
import model.RapportReservation;
import service.StatistiquesService;
import view.ConsoleHelper;
import view.MenuAdmin;

import java.util.List;
import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        // connexion a la base de donnees
        Database.getConnection();

        // creation des services
        StatistiquesService statsService = new StatistiquesService();//objet service bax les methodes analyserreservation , countbystatut

        // creation de la vue
        MenuAdmin menu = new MenuAdmin();
        Scanner sc = new Scanner(System.in);

        int choix = -1;

        while (choix != 0) {

            choix = menu.displayMainMenu();

            // 1. STATISTIQUES
            if (choix == 1) {

                int sousChoix = menu.displayStatMenu();

                if (sousChoix == 1) {
                    // rapport complet
                    ConsoleHelper.printReport("RAPPORT COMPLET", statsService.analyserReservations().toString());

                } else if (sousChoix == 2) {
                    // compter par statut
                    sc.nextLine();  
                    String statut = ConsoleHelper.getUserInput("Entrer le statut (EnAttente / Confirme / Annule / Paye)");
                    int count = statsService.countByStatus(statut);
                    System.out.println("Nombre de reservations '" + statut + "' : " + count);

                } else if (sousChoix == 3) {
                    // toutes les reservations
                    List<RapportReservation> reservations = statsService.getReservations();
                    ConsoleHelper.printList("TOUTES LES RESERVATIONS", reservations);

                } else if (sousChoix == 4) {
                    // reservations d'un touriste
                    System.out.print("ID du touriste : ");
                    int idTouriste = sc.nextInt();
                    List<RapportReservation> reservations = statsService.getReservationsByTouriste(idTouriste); //list dyal les objet   et recuperer les information from DB
                    ConsoleHelper.printList("RESERVATIONS DU TOURISTE #" + idTouriste, reservations);
                }

            } else if (choix != 0) {
                System.out.println("choix invalide, reessayez.");
            }
        }

        System.out.println("\nAu revoir !");
    }
}