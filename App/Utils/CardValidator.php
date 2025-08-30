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

    /**
     * Valida número de cartão (16 dígitos + algoritmo de Luhn)
     */
    private static function isCardNumberValid(string $cardNumber): bool
    {
        $cleanedCardNumber = str_replace(' ', '', $cardNumber);

        if (!preg_match('/^\d{16}$/', $cleanedCardNumber)) {
            return false;
        }

        return self::passesLuhnCheck($cleanedCardNumber);
    }

    private static function passesLuhnCheck(string $number): bool
    {
        $sum = 0;
        $alt = false;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $n = intval($number[$i]);
            if ($alt) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }
            $sum += $n;
            $alt = !$alt;
        }
        return $sum % 10 === 0;
    }

    private static function isCardholderNameValid(string $name): bool
    {
        return (bool) preg_match('/^[A-Za-zÀ-ÿ\s]+$/u', $name);
    }

    private static function isExpirationDateValid(string $expirationDate): bool
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expirationDate)) {
            return false;
        }

        [$month, $year] = explode('/', $expirationDate);

        $month = intval($month);
        $year = intval("20" . $year);

        $currentYear = intval(date('Y'));
        $currentMonth = intval(date('m'));

        // Expirado se ano < atual ou (ano == atual && mês < atual)
        if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
            return false;
        }

        return true;
    }

    private static function isCvvValid(string $cvv): bool
    {
        return (bool) preg_match('/^\d{3,4}$/', $cvv);
    }
}
