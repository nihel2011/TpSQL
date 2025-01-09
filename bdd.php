<?php

// Informations de connexion à la base de données
// $host = 'localhost:3307';
$host = 'localhost:5508';
$user = 'root';
$password = '';
$database = 'dailytrip_0';

try {
    // Connexion au serveur MySQL sans sélectionner de base de données
    $conn = new PDO("mysql:host=$host", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la base de données si elle n'existe pas
    $sql = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET = 'utf8mb4'";
    $conn->exec($sql);
    echo "Base de données '$database' créée avec succès.\n";
    
    // Se connecter à la base de données créée
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Définir le moteur InnoDB pour la création des tables
    $engine = 'ENGINE = InnoDB';
    
    // Création des tables
    $tables = [
        // TODO: Ajoutez vos requêtes SQL de création de tables ici
        "CREATE TABLE `category`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `image` VARCHAR(255) NOT NULL
        );",
          "CREATE TABLE `localisation`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `start` VARCHAR(255) NOT NULL,
            `finish` VARCHAR(255) NOT NULL,
            `distance` DECIMAL(8, 3) NOT NULL,
            `duration` TIME NOT NULL
        );",
          "CREATE TABLE `gallery`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY
        );",
          "CREATE TABLE `images`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY
        );",
          "CREATE TABLE `admin`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL
        );" ,
        "CREATE TABLE `trips`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `ref` VARCHAR(255) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NULL,
            `cover` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `localisation_id` INT NOT NULL,
            `category_id` INT NOT NULL,
            `gallery_id` INT NULL,
            `status` BOOLEAN NOT NULL,
            FOREIGN KEY (`category_id`) REFERENCES category(`id`),
            FOREIGN KEY (`gallery_id`) REFERENCES gallery(`id`),
            FOREIGN KEY (`localisation_id`) REFERENCES localisation(`id`)

        );",
      
        "CREATE TABLE `poi`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `point` VARCHAR(255) NOT NULL,
            `localisation_id` INT NOT NULL,
            `gallery_id` INT NULL,
            FOREIGN KEY (`gallery_id`) REFERENCES gallery(`id`),
            FOREIGN KEY (`localisation_id`) REFERENCES localisation(`id`)


        );",
      
      
        "CREATE TABLE `gallery_images`(
            `gallery_id` INT NOT NULL,
            `image_id` INT NOT NULL,
            FOREIGN KEY (`gallery_id`) REFERENCES gallery(`id`),
            FOREIGN KEY (`image_id`) REFERENCES images(`id`)

        );",
      
        "CREATE TABLE `rating`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `note` INT NOT NULL,
            `ip_address` VARCHAR(255) NOT NULL,
            `trip_id` INT NOT NULL,
            FOREIGN KEY (`trip_id`) REFERENCES trips(`id`)

        );",
"        CREATE TABLE `review`(
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `fullname` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `trip_id` INT NOT NULL,
            FOREIGN KEY (`trip_id`) REFERENCES trips(`id`)

        );"
             


    ];
    
    // Exécution de la création des tables
    foreach ($tables as $tableSql) {
        try {
            $conn->exec($tableSql);
            echo "Table créée avec succès.\n";
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la table : " . $e->getMessage() . "\n";
        }
    }
    
    // Ajout des clés étrangères
    $constraints = [
        // TODO: Ajoutez vos requêtes SQL de contraintes ici
        "ALTER TABLE poi ADD CONSTRAINT poi_localisation_id FOREIGN KEY (localisation_id) REFERENCES localisation(id);",
        "ALTER TABLE poi ADD CONSTRAINT poi_gallery_id FOREIGN KEY (gallery_id) REFERENCES gallery(id);",
        "ALTER TABLE trips ADD CONSTRAINT trips_localisation_id FOREIGN KEY (localisation_id) REFERENCES localisation(id);",
        "ALTER TABLE trips ADD CONSTRAINT trips_category_id FOREIGN KEY (category_id) REFERENCES category(id);",
        "ALTER TABLE trips ADD CONSTRAINT trips_gallery_id FOREIGN KEY (gallery_id) REFERENCES gallery(id);",
        "ALTER TABLE rating ADD CONSTRAINT rating_trip_id FOREIGN KEY (trip_id) REFERENCES trips(id);",
        "ALTER TABLE review ADD CONSTRAINT review_trip_id FOREIGN KEY (trip_id) REFERENCES trips(id);",
        "ALTER TABLE gallery_images ADD CONSTRAINT gallery_images_gallery_id FOREIGN KEY (gallery_id) REFERENCES gallery(id);",
        "ALTER TABLE gallery_images ADD CONSTRAINT gallery_images_image_id FOREIGN KEY (image_id) REFERENCES images(id);"





    ];
    
    // Exécution des contraintes de clés étrangères
    foreach ($constraints as $constraintSql) {
        try {
            $conn->exec($constraintSql);
            echo "Contrainte de clé étrangère ajoutée avec succès.\n";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la contrainte : " . $e->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit;
} finally {
    // Fermer la connexion
    $conn = null;
}


