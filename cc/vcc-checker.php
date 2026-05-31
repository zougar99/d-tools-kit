<?php
/**
 * VCC Checker — Virtual Credit Card Detection Library
 * 
 * Usage:
 *   require_once 'vcc-checker.php';
 *   $result = VCCChecker::check('4064691234567890');
 *   // $result = ['is_vcc' => true, 'issuer' => 'EntroPay', 'confidence' => 'High', ...]
 * 
 *   $isVcc = VCCChecker::isVCC('4064691234567890');
 *   // $isVcc = true
 */

class VCCChecker
{
    private static array $database = [
        // EntroPay
        '406469' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '406470' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '489403' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        // Neteller
        '421671' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '421672' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '421673' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '421674' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '402079' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '404681' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '404680' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '448500' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '448501' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '440587' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '400234' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        // Skrill
        '531410' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '531411' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '531412' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '531413' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '531414' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '524961' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '527566' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        // Paysafecard
        '528810' => ['issuer' => 'Paysafecard', 'country' => 'Austria', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '533128' => ['issuer' => 'Paysafecard', 'country' => 'Austria', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        // Revolut
        '400220' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400221' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400222' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400223' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400224' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400225' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400226' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400227' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400228' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400229' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        // Vivid Money
        '440446' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
        '440447' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
        '440448' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
        '440449' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
        '440450' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
        // N26
        '486209' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '486210' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '486211' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '486212' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '535543' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '535544' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '535545' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        // Monese
        '461443' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '461444' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '461445' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        // Wise
        '536963' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '536964' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '536965' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '536966' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '536967' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '222757' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '222758' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        '222759' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
        // Stripe Issuing
        '400810' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400811' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400812' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400813' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400814' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400815' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        '400816' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
        // Privacy.com
        '428830' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428831' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428832' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428833' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428834' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428835' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428836' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428837' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428838' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        '428839' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
        // US Bank Virtual
        '402036' => ['issuer' => 'US Bank Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
        '402037' => ['issuer' => 'US Bank Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
        // Capital One Virtual
        '403448' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
        '403449' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
        '403450' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
        '403451' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
        // Epay
        '408562' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '408563' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '408564' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        '408565' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
        // Payoneer
        '513268' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
        '513269' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
        '513270' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
        '529558' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
        '529559' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
    ];

    private static array $cardNetworks = [
        '/^4/' => 'Visa',
        '/^5[1-5]/' => 'Mastercard',
        '/^2(?:2(?:2[1-9]|[3-9]\d)|[3-6]\d\d|7(?:[01]\d|20))/' => 'Mastercard',
        '/^3[47]/' => 'American Express',
        '/^6011|^65|^64[4-9]/' => 'Discover',
        '/^3(?:0[0-5]|[68])/' => 'Diners Club',
        '/^(?:2131|1800|35)/' => 'JCB',
        '/^(?:5018|5020|5038|5893|6304|6759|676[1-3])/' => 'Maestro',
        '/^220[0-4]/' => 'Mir',
        '/^9792/' => 'Troy',
        '/^62/' => 'UnionPay',
    ];

    /**
     * Check if a card number is a Virtual Credit Card (VCC).
     *
     * @param string $pan Card number (at least 6 digits)
     * @return array Result with keys: is_vcc, issuer, country, type, confidence, brand, bin
     */
    public static function check(string $pan): array
    {
        $clean = preg_replace('/\D/', '', $pan);
        $bin = substr($clean, 0, 6);
        $brand = self::detectBrand($clean);

        if (isset(self::$database[$bin])) {
            $entry = self::$database[$bin];
            $isVirtual = stripos($entry['type'], 'Virtual') !== false;

            return [
                'is_vcc'     => $isVirtual,
                'issuer'     => $entry['issuer'],
                'country'    => $entry['country'],
                'type'       => $entry['type'],
                'confidence' => $entry['confidence'],
                'brand'      => $brand,
                'bin'        => $bin,
            ];
        }

        return [
            'is_vcc'     => false,
            'issuer'     => 'Unknown',
            'country'    => 'Unknown',
            'type'       => 'Unknown',
            'confidence' => 'Low',
            'brand'      => $brand,
            'bin'        => $bin,
        ];
    }

    /**
     * Quick check — returns true if the card is a known VCC.
     *
     * @param string $pan Card number (at least 6 digits)
     * @return bool
     */
    public static function isVCC(string $pan): bool
    {
        $result = self::check($pan);
        return $result['is_vcc'] === true;
    }

    /**
     * Get the card network/brand name.
     *
     * @param string $pan Card number
     * @return string Brand name or 'Unknown'
     */
    public static function detectBrand(string $pan): string
    {
        $clean = preg_replace('/\D/', '', $pan);
        foreach (self::$cardNetworks as $pattern => $name) {
            if (preg_match($pattern, $clean)) {
                return $name;
            }
        }
        return 'Unknown';
    }

    /**
     * Get total number of BINs in the database.
     *
     * @return int
     */
    public static function databaseSize(): int
    {
        return count(self::$database);
    }

    /**
     * Add a custom BIN to the database (runtime only).
     *
     * @param string $bin 6-digit BIN
     * @param array  $info Issuer info (issuer, country, type, confidence)
     */
    public static function addBIN(string $bin, array $info): void
    {
        if (strlen($bin) === 6 && ctype_digit($bin)) {
            self::$database[$bin] = $info;
        }
    }
}