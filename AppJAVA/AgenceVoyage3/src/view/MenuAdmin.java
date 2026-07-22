package view;

import java.util.Scanner;

public class MenuAdmin {

    Scanner sc = new Scanner(System.in);

    public int displayMainMenu() {
        System.out.println("       AGENCE DE VOYAGE - ADMIN             ");
        System.out.println("---------------------------------------------");
        System.out.println("  1. Statistiques & Rapports");
        System.out.println("  0. Quitter");
        System.out.println("--------------------------------------------");
        System.out.print("Votre choix : ");
        return sc.nextInt();
    }

    public int displayStatMenu() {
        System.out.println("\n--- STATISTIQUES ---");
        System.out.println("1. Rapport complet");
        System.out.println("2. Compter par statut");
        System.out.println("3. Voir toutes les reservations");
        System.out.println("4. Reservations d'un touriste");
        System.out.print("Choix : ");
        return sc.nextInt();
    }
}