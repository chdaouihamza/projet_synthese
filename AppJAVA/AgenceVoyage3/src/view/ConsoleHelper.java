package view;

import java.util.List;
import java.util.Scanner;

public class ConsoleHelper {

    static Scanner sc = new Scanner(System.in);

    public static String getUserInput(String message) {
        System.out.print(message + " : ");
        return sc.nextLine();
    }

    public static int getIntInput(String message) {
        System.out.print(message + " : ");
        return sc.nextInt();
    }

    // afficher une liste avec un titre
    public static void printList(String titre, List<?> liste) {
        System.out.println("\n--- " + titre + " ---");
        if (liste.isEmpty()) {
            System.out.println("  aucun element trouve.");
        } else {
            for (Object item : liste) {
                System.out.println("  " + item.toString());
            }
            System.out.println("  total : " + liste.size() + " element(s)");
        }
    }

    public static void printReport(String titre, String contenu) {
        System.out.println("\n========== " + titre + " ==========");
        System.out.println(contenu);
        System.out.println("====================================");
    }
}