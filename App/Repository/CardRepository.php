<?php

namespace App\Repository;

class CardRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Save card data to the database
    public function saveCard(array $cardData): bool
    {
        $stmt = $this->pdo->prepare(/** @lang text */
            "INSERT INTO cards (numero, nome, validade, cvv) VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $cardData['numero'],
            $cardData['nome'],
            $cardData['validade'],
            $cardData['cvv']
        ]);
    }
}