<?php

namespace App\Service;

use MongoDB\Client;
use MongoDB\Collection;

class MongoService
{
    private Client $client;
    private Collection $usersCollection;

    public function __construct()
    {
        try {
            // Connexion à MongoDB
            $this->client = new Client("mongodb://localhost:27017");
            // Accéder à la collection 'users' dans la base de données 'v2spa'
            $this->usersCollection = $this->client->v2spa->users;
        } catch (\Exception $e) {
            // ERREUR DE SÉCURITÉ : on affiche le message complet d'exception à l'écran
            // au lieu de loguer un message générique côté serveur.
            die("Erreur de connexion à MongoDB : " . $e->getMessage());
        }
    }

    public function getUsersCollection(): Collection
    {
        return $this->usersCollection;
    }

    public function findUserByEmail(string $email)
    {
        return $this->usersCollection->findOne(['email' => $email]);
    }

    public function insertUser(array $user)
    {
        $this->usersCollection->insertOne($user);
    }
}
