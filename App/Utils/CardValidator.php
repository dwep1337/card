<?php

namespace App\Utils;

class CardValidator
{

    final public static function isValidCard(array $cardData): bool
    {
        return self::areFieldsFilled($cardData)
            && self::isCardNumberValid($cardData['numero'] ?? '')
            && self::isCardholderNameValid($cardData['nome'] ?? '')
            && self::isExpirationDateValid($cardData['validade'] ?? '')
            && self::isCvvValid($cardData['cvv'] ?? '');
    }

    private static function areFieldsFilled(array $cardData): bool
    {
        $requiredFields = ['numero', 'nome', 'validade', 'cvv'];
        return array_all($requiredFields, fn($field) => !empty($cardData[$field]));
    }

    private static function isCardNumberValid(string $cardNumber): bool
    {
        $cleanedCardNumber = str_replace(' ', '', $cardNumber);

        if (!preg_match('/^\d{16}$/', $cleanedCardNumber)) {
            return false;
        }
        return true;
    }

    private static  function isCardholderNameValid(string $name): bool
    {
        if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
            return false;
        }
        return true;
    }

    private static function isExpirationDateValid(string $expirationDate): bool
    {
        if (!preg_match('/^\d{2}\/\d{2}$/', $expirationDate)) {
            return false;
        }
        return true;
    }

    private static function isCvvValid(string $cvv): bool
    {
        if (!preg_match('/^\d{3}$/', $cvv)) {
            return false;
        }
        return true;
    }
}