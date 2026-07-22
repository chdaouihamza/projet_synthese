===============================================
   AGENCE VOYAGE - PROJET JAVA ECLIPSE
   (connecte a la base agence_voyage.sql)
===============================================

ETAPE 1 - Telecharger le driver MySQL JDBC
-------------------------------------------
1. Aller sur : https://dev.mysql.com/downloads/connector/j/
2. Choisir "Platform Independent" --> telecharger le ZIP
3. Extraire --> copier le fichier mysql-connector-j-x.x.x.jar
4. Le coller dans le dossier  AgenceVoyage/lib/
5. Renommer-le exactement : mysql-connector-j-8.3.0.jar
   (ou modifier .classpath ligne "lib/mysql-connector-j-8.3.0.jar")

ETAPE 2 - Importer la base de donnees
---------------------------------------
1. Ouvrir phpMyAdmin ou MySQL Workbench
2. Creer une base nommee : agence_voyage
3. Importer le fichier : agence_voyage.sql

ETAPE 3 - Importer dans Eclipse
---------------------------------
1. File --> Import --> General --> Existing Projects into Workspace
2. Choisir le dossier AgenceVoyage
3. Finish

ETAPE 4 - Ajouter le JAR au Build Path
----------------------------------------
1. Clic droit sur le projet --> Build Path --> Configure Build Path
2. Onglet Libraries --> Add JARs
3. Choisir lib/mysql-connector-j-8.3.0.jar
4. Apply and Close

ETAPE 5 - Configurer la connexion (si besoin)
-----------------------------------------------
Ouvrir src/database/Database.java et modifier :

   private static String url      = "jdbc:mysql://localhost:3306/agence_voyage";
   private static String user     = "root";
   private static String password = "";   <-- mettre votre mot de passe ici

ETAPE 6 - Lancer
------------------
Clic droit sur src/Main.java --> Run As --> Java Application

===============================================
TABLES UTILISEES DANS CE PROJET
===============================================

admin               --> connexion admin (non utilise en cours, a etendre)
touriste            --> affiche dans les reservations (JOIN)
reservation         --> statistiques, archives
sejour              --> affiche dans les archives
hotel               --> catalogue hotels
chambre             --> chambres disponibles par hotel
restaurant          --> catalogue restaurants
plat                --> plats par restaurant
activite            --> catalogue activites
lieutouristique     --> catalogue lieux + itineraires
guidetouristique    --> catalogue guides
transport           --> catalogue transports
itineraire          --> liste des itineraires
itineraire_lieutouristique --> lieux d'un itineraire (avec duree)
itineraire_transport       --> transports d'un itineraire

===============================================
STRUCTURE DU PROJET
===============================================

AgenceVoyage/
├── src/
│   ├── Main.java
│   ├── database/
│   │   └── Database.java
│   ├── model/
│   │   ├── RapportReservation.java
│   │   ├── StatistiquesRapport.java
│   │   ├── LogActivite.java
│   │   └── CatalogueItem.java
│   ├── service/
│   │   ├── StatistiquesService.java
│   │   ├── CatalogueService.java
│   │   ├── ArchiveService.java
│   │   └── ItineraireService.java
│   └── view/
│       ├── MenuAdmin.java
│       └── ConsoleHelper.java
├── lib/
│   └── mysql-connector-j-8.3.0.jar  <-- a mettre manuellement
├── .project
└── .classpath
