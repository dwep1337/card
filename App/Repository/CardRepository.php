<?php

namespace App\Repository;

use PDO;

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

    public function getCards($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $this->pdo->prepare(/** @lang text */"SELECT * FROM cards LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // ← Aqui!
    }
}