<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\generate_pokemon_sql.php

require 'db.php'; // Inclure la connexion à la base de données

// Boucle pour insérer les 1010 Pokémon
for ($id = 1; $id <= 1010; $id++) {
    // Appeler l'API PokeAPI pour récupérer les données du Pokémon
    $url = "https://pokeapi.co/api/v2/pokemon/$id";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Récupérer le nom et l'image
    $name = ucfirst($data['name']); // Nom du Pokémon (première lettre en majuscule)
    $description = "Un Pokémon nommé $name."; // Description par défaut
    $image = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/$id.png";
    $owner_id = 1; // Par défaut, associez les cartes à l'utilisateur avec l'ID 1

    // Préparer et exécuter la requête SQL
    $stmt = $pdo->prepare("INSERT INTO cards (id, name, description, image, owner_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id, $name, $description, $image, $owner_id]);

    echo "Inséré : $name (ID: $id)\n";
}
?>