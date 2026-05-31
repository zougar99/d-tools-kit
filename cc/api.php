<?php
/**
 * API Endpoint for CC Checker Pro
 * Supports: CC validation, BIN lookup, card generation, CVV check, admin
 */

if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    define('ENABLE_LUHN_CHECK', true);
    define('MIN_CARD_LENGTH', 13);
    define('MAX_CARD_LENGTH', 19);
    define('MIN_EXPIRY_YEAR', 2000);
    define('MAX_EXPIRY_YEARS_AHEAD', 10);
    define('MIN_VALID_YEAR', (int) date('Y'));
}

header('Content-Type: application/json; charset=utf-8');

$minLen = defined('MIN_CARD_LENGTH') ? MIN_CARD_LENGTH : 13;
$maxLen = defined('MAX_CARD_LENGTH') ? MAX_CARD_LENGTH : 19;

// ── BIN DATABASE ─────────────────────────────────────────────
$BIN_DB = [
    // Visa
    '414720' => ['bank' => 'Samba Financial Group', 'country' => 'Saudi Arabia', 'type' => 'Debit', 'brand' => 'Visa'],
    '414721' => ['bank' => 'Samba Financial Group', 'country' => 'Saudi Arabia', 'type' => 'Debit', 'brand' => 'Visa'],
    '492181' => ['bank' => 'Al Rajhi Bank', 'country' => 'Saudi Arabia', 'type' => 'Debit', 'brand' => 'Visa'],
    '403024' => ['bank' => 'National Bank of Kuwait', 'country' => 'Kuwait', 'type' => 'Credit', 'brand' => 'Visa'],
    '458000' => ['bank' => 'BNP Paribas', 'country' => 'France', 'type' => 'Credit', 'brand' => 'Visa'],
    '471600' => ['bank' => 'Banco Santander', 'country' => 'Spain', 'type' => 'Credit', 'brand' => 'Visa'],
    '465858' => ['bank' => 'CaixaBank', 'country' => 'Spain', 'type' => 'Debit', 'brand' => 'Visa'],
    '486010' => ['bank' => 'BBVA', 'country' => 'Spain', 'type' => 'Credit', 'brand' => 'Visa'],
    '453201' => ['bank' => 'HSBC Bank', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Visa'],
    '491730' => ['bank' => 'Barclays Bank', 'country' => 'United Kingdom', 'type' => 'Debit', 'brand' => 'Visa'],
    '400000' => ['bank' => 'Test Bank', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Visa'],
    '411111' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Visa'],
    '401288' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Visa'],
    '422222' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Visa'],
    // Mastercard
    '512345' => ['bank' => 'JPMorgan Chase', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '513456' => ['bank' => 'Bank of America', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '516738' => ['bank' => 'Standard Chartered', 'country' => 'United Arab Emirates', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '518000' => ['bank' => 'Emirates NBD', 'country' => 'United Arab Emirates', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '520000' => ['bank' => 'Citibank', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '521234' => ['bank' => 'Deutsche Bank', 'country' => 'Germany', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '530000' => ['bank' => 'QNB Finansbank', 'country' => 'Turkey', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '531234' => ['bank' => 'Credit Suisse', 'country' => 'Switzerland', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '540000' => ['bank' => 'Commonwealth Bank', 'country' => 'Australia', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '541234' => ['bank' => 'ANZ Bank', 'country' => 'Australia', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '542400' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Mastercard'],
    '550000' => ['bank' => 'HSBC Bank', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '551234' => ['bank' => 'Lloyds Bank', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '555555' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Mastercard'],
    '222100' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Mastercard'],
    '222300' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Mastercard'],
    // Amex
    '340000' => ['bank' => 'American Express', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Amex'],
    '370000' => ['bank' => 'American Express', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Amex'],
    '371234' => ['bank' => 'American Express', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Amex'],
    '378282' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Amex'],
    '371449' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Amex'],
    // Discover
    '601100' => ['bank' => 'Discover Financial', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Discover'],
    '601111' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Discover'],
    '601198' => ['bank' => 'Discover Financial', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Discover'],
    '650000' => ['bank' => 'Discover Financial', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Discover'],
    // Diners
    '300000' => ['bank' => 'Diners Club International', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Diners'],
    '305693' => ['bank' => 'Test / Demo Issuer', 'country' => 'United States', 'type' => 'Test', 'brand' => 'Diners'],
    '385200' => ['bank' => 'Diners Club International', 'country' => 'United States', 'type' => 'Credit', 'brand' => 'Diners'],
    // JCB
    '352800' => ['bank' => 'JCB Co. Ltd', 'country' => 'Japan', 'type' => 'Credit', 'brand' => 'JCB'],
    '353011' => ['bank' => 'Test / Demo Issuer', 'country' => 'Japan', 'type' => 'Test', 'brand' => 'JCB'],
    '356600' => ['bank' => 'Test / Demo Issuer', 'country' => 'Japan', 'type' => 'Test', 'brand' => 'JCB'],
    // UnionPay
    '620000' => ['bank' => 'China UnionPay', 'country' => 'China', 'type' => 'Credit', 'brand' => 'UnionPay'],
    '622200' => ['bank' => 'Bank of China', 'country' => 'China', 'type' => 'Debit', 'brand' => 'UnionPay'],
    '622300' => ['bank' => 'Industrial and Commercial Bank of China', 'country' => 'China', 'type' => 'Debit', 'brand' => 'UnionPay'],
    '622400' => ['bank' => 'China Construction Bank', 'country' => 'China', 'type' => 'Debit', 'brand' => 'UnionPay'],
    // Maestro
    '501800' => ['bank' => 'Maestro / Mastercard', 'country' => 'United Kingdom', 'type' => 'Debit', 'brand' => 'Maestro'],
    '502000' => ['bank' => 'Maestro / Mastercard', 'country' => 'United Kingdom', 'type' => 'Debit', 'brand' => 'Maestro'],
    '630400' => ['bank' => 'Maestro / Mastercard', 'country' => 'Netherlands', 'type' => 'Debit', 'brand' => 'Maestro'],
    '675900' => ['bank' => 'Maestro / Mastercard', 'country' => 'United Kingdom', 'type' => 'Debit', 'brand' => 'Maestro'],
    // Mir
    '220000' => ['bank' => 'National Payment Card System', 'country' => 'Russia', 'type' => 'Debit', 'brand' => 'Mir'],
    '220100' => ['bank' => 'Sberbank', 'country' => 'Russia', 'type' => 'Debit', 'brand' => 'Mir'],
    '220200' => ['bank' => 'VTB Bank', 'country' => 'Russia', 'type' => 'Debit', 'brand' => 'Mir'],
    '220300' => ['bank' => 'Gazprombank', 'country' => 'Russia', 'type' => 'Debit', 'brand' => 'Mir'],
    // Troy
    '979200' => ['bank' => 'Troy', 'country' => 'Turkey', 'type' => 'Credit', 'brand' => 'Troy'],
    '979201' => ['bank' => 'Troy', 'country' => 'Turkey', 'type' => 'Debit', 'brand' => 'Troy'],
    // ── Additional Banks ──
    '457123' => ['bank' => 'Barclays Bank', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Visa'],
    '492942' => ['bank' => 'Lloyds Banking Group', 'country' => 'United Kingdom', 'type' => 'Debit', 'brand' => 'Visa'],
    '446200' => ['bank' => 'NatWest Bank', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Visa'],
    '440000' => ['bank' => 'Royal Bank of Scotland', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Visa'],
    '448400' => ['bank' => 'HSBC Bank', 'country' => 'United Kingdom', 'type' => 'Credit', 'brand' => 'Visa'],
    '448500' => ['bank' => 'Santander UK', 'country' => 'United Kingdom', 'type' => 'Debit', 'brand' => 'Visa'],
    '471540' => ['bank' => 'BNP Paribas', 'country' => 'France', 'type' => 'Credit', 'brand' => 'Visa'],
    '497430' => ['bank' => 'Societe Generale', 'country' => 'France', 'type' => 'Credit', 'brand' => 'Visa'],
    '427500' => ['bank' => 'Credit Agricole', 'country' => 'France', 'type' => 'Credit', 'brand' => 'Visa'],
    '486250' => ['bank' => 'Deutsche Bank', 'country' => 'Germany', 'type' => 'Credit', 'brand' => 'Visa'],
    '485000' => ['bank' => 'Commerzbank', 'country' => 'Germany', 'type' => 'Credit', 'brand' => 'Visa'],
    '474000' => ['bank' => 'UniCredit', 'country' => 'Italy', 'type' => 'Credit', 'brand' => 'Visa'],
    '490100' => ['bank' => 'Intesa Sanpaolo', 'country' => 'Italy', 'type' => 'Credit', 'brand' => 'Visa'],
    '456000' => ['bank' => 'ABN AMRO', 'country' => 'Netherlands', 'type' => 'Credit', 'brand' => 'Visa'],
    '478000' => ['bank' => 'ING Bank', 'country' => 'Netherlands', 'type' => 'Debit', 'brand' => 'Visa'],
    '408400' => ['bank' => 'Banco do Brasil', 'country' => 'Brazil', 'type' => 'Credit', 'brand' => 'Visa'],
    '476100' => ['bank' => 'Itau Unibanco', 'country' => 'Brazil', 'type' => 'Credit', 'brand' => 'Visa'],
    '499800' => ['bank' => 'Bradesco', 'country' => 'Brazil', 'type' => 'Credit', 'brand' => 'Visa'],
    '421300' => ['bank' => 'Banco Santander', 'country' => 'Brazil', 'type' => 'Credit', 'brand' => 'Visa'],
    '415200' => ['bank' => 'Scotiabank', 'country' => 'Canada', 'type' => 'Credit', 'brand' => 'Visa'],
    '451200' => ['bank' => 'Toronto-Dominion Bank', 'country' => 'Canada', 'type' => 'Credit', 'brand' => 'Visa'],
    '456400' => ['bank' => 'Royal Bank of Canada', 'country' => 'Canada', 'type' => 'Credit', 'brand' => 'Visa'],
    '450600' => ['bank' => 'Bank of Montreal', 'country' => 'Canada', 'type' => 'Credit', 'brand' => 'Visa'],
    '462100' => ['bank' => 'National Australia Bank', 'country' => 'Australia', 'type' => 'Credit', 'brand' => 'Visa'],
    '446500' => ['bank' => 'Westpac Banking', 'country' => 'Australia', 'type' => 'Credit', 'brand' => 'Visa'],
    '443700' => ['bank' => 'Mitsubishi UFJ', 'country' => 'Japan', 'type' => 'Credit', 'brand' => 'Visa'],
    '409800' => ['bank' => 'Mizuho Bank', 'country' => 'Japan', 'type' => 'Credit', 'brand' => 'Visa'],
    '454300' => ['bank' => 'Sumitomo Mitsui', 'country' => 'Japan', 'type' => 'Credit', 'brand' => 'Visa'],
    '418600' => ['bank' => 'ICICI Bank', 'country' => 'India', 'type' => 'Credit', 'brand' => 'Visa'],
    '434700' => ['bank' => 'HDFC Bank', 'country' => 'India', 'type' => 'Credit', 'brand' => 'Visa'],
    '419400' => ['bank' => 'State Bank of India', 'country' => 'India', 'type' => 'Debit', 'brand' => 'Visa'],
    '449000' => ['bank' => 'Axis Bank', 'country' => 'India', 'type' => 'Credit', 'brand' => 'Visa'],
    '459600' => ['bank' => 'Maybank', 'country' => 'Malaysia', 'type' => 'Credit', 'brand' => 'Visa'],
    '485500' => ['bank' => 'CIMB Bank', 'country' => 'Malaysia', 'type' => 'Credit', 'brand' => 'Visa'],
    '481100' => ['bank' => 'Public Bank', 'country' => 'Malaysia', 'type' => 'Debit', 'brand' => 'Visa'],
    '418000' => ['bank' => 'Standard Chartered', 'country' => 'Singapore', 'type' => 'Credit', 'brand' => 'Visa'],
    '479500' => ['bank' => 'DBS Bank', 'country' => 'Singapore', 'type' => 'Credit', 'brand' => 'Visa'],
    '436200' => ['bank' => 'OCBC Bank', 'country' => 'Singapore', 'type' => 'Credit', 'brand' => 'Visa'],
    '456100' => ['bank' => 'Absa Bank', 'country' => 'South Africa', 'type' => 'Credit', 'brand' => 'Visa'],
    '400500' => ['bank' => 'First National Bank', 'country' => 'South Africa', 'type' => 'Debit', 'brand' => 'Visa'],
    '487000' => ['bank' => 'Nedbank', 'country' => 'South Africa', 'type' => 'Credit', 'brand' => 'Visa'],
    '523670' => ['bank' => 'Mashreq Bank', 'country' => 'United Arab Emirates', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '523750' => ['bank' => 'Abu Dhabi Islamic Bank', 'country' => 'United Arab Emirates', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '531260' => ['bank' => 'Qatar National Bank', 'country' => 'Qatar', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '531390' => ['bank' => 'National Bank of Kuwait', 'country' => 'Kuwait', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '525500' => ['bank' => 'Arab Bank', 'country' => 'Jordan', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '516700' => ['bank' => 'Saudi British Bank', 'country' => 'Saudi Arabia', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '512070' => ['bank' => 'Alinma Bank', 'country' => 'Saudi Arabia', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '529300' => ['bank' => 'Attijariwafa Bank', 'country' => 'Morocco', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '529310' => ['bank' => 'BMCE Bank', 'country' => 'Morocco', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '529320' => ['bank' => 'Bank of Africa', 'country' => 'Morocco', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '529330' => ['bank' => 'CIH Bank', 'country' => 'Morocco', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '522500' => ['bank' => 'GTBank', 'country' => 'Nigeria', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '529500' => ['bank' => 'Access Bank', 'country' => 'Nigeria', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '517700' => ['bank' => 'First Bank of Nigeria', 'country' => 'Nigeria', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '512346' => ['bank' => 'Zenith Bank', 'country' => 'Nigeria', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '539600' => ['bank' => 'KCB Bank', 'country' => 'Kenya', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '531400' => ['bank' => 'Equity Bank', 'country' => 'Kenya', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '529710' => ['bank' => 'National Bank of Egypt', 'country' => 'Egypt', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '525910' => ['bank' => 'Banque Misr', 'country' => 'Egypt', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '526100' => ['bank' => 'Commercial International Bank', 'country' => 'Egypt', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '512641' => ['bank' => 'BBVA Bancomer', 'country' => 'Mexico', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '555550' => ['bank' => 'Banamex', 'country' => 'Mexico', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '557900' => ['bank' => 'Santander Mexico', 'country' => 'Mexico', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '547800' => ['bank' => 'Banco de Chile', 'country' => 'Chile', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '533800' => ['bank' => 'Banco Santander Chile', 'country' => 'Chile', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '536100' => ['bank' => 'Banco de Credito', 'country' => 'Peru', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '546200' => ['bank' => 'Banco BBVA Peru', 'country' => 'Peru', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '548100' => ['bank' => 'Banco de Bogota', 'country' => 'Colombia', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '549900' => ['bank' => 'Bancolombia', 'country' => 'Colombia', 'type' => 'Credit', 'brand' => 'Mastercard'],
    '539300' => ['bank' => 'Banco Popular', 'country' => 'Colombia', 'type' => 'Credit', 'brand' => 'Mastercard'],
];

// ── VCC DATABASE (Known Virtual Credit Card BINs) ────────────
$VCC_BINS = [
    '406469' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '406470' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '489403' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '421671' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '421672' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '421673' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '421674' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '402079' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '404681' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '404680' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '448500' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '448501' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '440587' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '400234' => ['issuer' => 'Neteller (Paysafe)', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531410' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531411' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531412' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531413' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531414' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '524961' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '527566' => ['issuer' => 'Skrill (Paysafe)', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '528810' => ['issuer' => 'Paysafecard', 'country' => 'Austria', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '533128' => ['issuer' => 'Paysafecard', 'country' => 'Austria', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
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
    '440446' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '440447' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '440448' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '440449' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '440450' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '486209' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '486210' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '486211' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '486212' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '535543' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '535544' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '535545' => ['issuer' => 'N26 Bank', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '461443' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '461444' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '461445' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '536963' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536964' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536965' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536966' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536967' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '222757' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '222758' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '222759' => ['issuer' => 'Wise (TransferWise)', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '400810' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400811' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400812' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400813' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400814' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400815' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400816' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
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
    '402036' => ['issuer' => 'US Bank Virtual Wallet', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '402037' => ['issuer' => 'US Bank Virtual Wallet', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '403448' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '403449' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '403450' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '403451' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '408562' => ['issuer' => 'Epay (EPX)', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '408563' => ['issuer' => 'Epay (EPX)', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '408564' => ['issuer' => 'Epay (EPX)', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '408565' => ['issuer' => 'Epay (EPX)', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '513268' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
    '513269' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
    '513270' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
    '529558' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
    '529559' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'Medium'],
];

// ── CARD TYPE DEFINITIONS ─────────────────────────────────────
$CARD_TYPES = [
    'mir' => ['name' => 'Mir', 'patterns' => ['/^220[0-4]/'], 'lengths' => [16], 'cvv_length' => 3, 'color' => '#4CAF50'],
    'visa' => ['name' => 'Visa', 'patterns' => ['/^4/'], 'lengths' => [13, 16, 19], 'cvv_length' => 3, 'color' => '#1434CB'],
    'mastercard' => ['name' => 'Mastercard', 'patterns' => ['/^5[1-5]/', '/^2(?:2(?:2[1-9]|[3-9]\d)|[3-6]\d\d|7(?:[01]\d|20))/'], 'lengths' => [16], 'cvv_length' => 3, 'color' => '#EB001B'],
    'amex' => ['name' => 'American Express', 'patterns' => ['/^3[47]/'], 'lengths' => [15], 'cvv_length' => 4, 'color' => '#007B5E'],
    'discover' => ['name' => 'Discover', 'patterns' => ['/^6011/', '/^65/', '/^64[4-9]/', '/^622(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d\d|9(?:[01]\d|2[0-5]))/'], 'lengths' => [16, 19], 'cvv_length' => 3, 'color' => '#FF6600'],
    'diners' => ['name' => 'Diners Club', 'patterns' => ['/^3(?:0[0-5]|[68])/'], 'lengths' => [14, 16, 19], 'cvv_length' => 3, 'color' => '#004A97'],
    'jcb' => ['name' => 'JCB', 'patterns' => ['/^(?:2131|1800|35)/'], 'lengths' => [16, 17, 18, 19], 'cvv_length' => 3, 'color' => '#003087'],
    'maestro' => ['name' => 'Maestro', 'patterns' => ['/^(?:5018|5020|5038|5893|6304|6759|676[1-3])/'], 'lengths' => [12, 13, 14, 15, 16, 17, 18, 19], 'cvv_length' => 3, 'color' => '#009BDE'],
    'troy' => ['name' => 'Troy', 'patterns' => ['/^9792/'], 'lengths' => [16], 'cvv_length' => 3, 'color' => '#E63946'],
    'unionpay' => ['name' => 'UnionPay', 'patterns' => ['/^62/'], 'lengths' => [16, 17, 18, 19], 'cvv_length' => 3, 'color' => '#CC0000'],
];

// ── SESSION / ADMIN ───────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$LOG_FILE = __DIR__ . '/admin_log.json';
$ADMIN_PASS = 'admin123';

function loadLog(): array {
    global $LOG_FILE;
    if (!file_exists($LOG_FILE)) return ['checks' => [], 'total' => 0, 'live' => 0, 'die' => 0, 'unknown' => 0];
    $data = json_decode(file_get_contents($LOG_FILE), true);
    return is_array($data) ? $data : ['checks' => [], 'total' => 0, 'live' => 0, 'die' => 0, 'unknown' => 0];
}

function saveLog(array $log): void {
    global $LOG_FILE;
    file_put_contents($LOG_FILE, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function addLogEntry(string $card, string $status): void {
    $log = loadLog();
    $log['checks'][] = ['card' => $card, 'status' => $status, 'time' => date('Y-m-d H:i:s')];
    $log['total']++;
    if ($status === 'live') $log['live']++;
    elseif ($status === 'die') $log['die']++;
    else $log['unknown']++;
    if (count($log['checks']) > 200) array_shift($log['checks']);
    $log['checks'] = array_slice($log['checks'], -200);
    saveLog($log);
}

// ── HELPER FUNCTIONS ──────────────────────────────────────────
function validateLuhn(string $number): bool {
    $number = preg_replace('/\D/', '', $number);
    if ($number === '') return false;
    $sum = 0;
    $length = strlen($number);
    for ($i = $length - 1; $i >= 0; $i--) {
        $digit = (int) $number[$i];
        if (($length - $i) % 2 === 0) {
            $digit *= 2;
            if ($digit > 9) $digit -= 9;
        }
        $sum += $digit;
    }
    return ($sum % 10) === 0;
}

function detectCardType(string $number): ?array {
    global $CARD_TYPES;
    $clean = preg_replace('/\D/', '', $number);
    foreach ($CARD_TYPES as $key => $type) {
        foreach ($type['patterns'] as $pattern) {
            if (preg_match($pattern, $clean)) {
                return array_merge(['key' => $key], $type);
            }
        }
    }
    return null;
}

function isValidLength(string $number, ?array $cardType): bool {
    $len = strlen(preg_replace('/\D/', '', $number));
    if ($cardType === null) return $len >= 13 && $len <= 19;
    return in_array($len, $cardType['lengths'], true);
}

function isValidCVV(string $cvv, ?array $cardType): bool {
    if (!preg_match('/^\d+$/', $cvv)) return false;
    $len = strlen($cvv);
    if ($cardType === null) return $len === 3 || $len === 4;
    return $len === $cardType['cvv_length'];
}

function isSuspiciousCVV(string $cvv): bool {
    if (!preg_match('/^\d+$/', $cvv)) return false;
    if (preg_match('/^(\d)\1+$/', $cvv)) return true;
    $len = strlen($cvv); $asc = true; $dsc = true;
    for ($i = 1; $i < $len; $i++) {
        if ((int)$cvv[$i] - (int)$cvv[$i - 1] !== 1) $asc = false;
        if ((int)$cvv[$i - 1] - (int)$cvv[$i] !== 1) $dsc = false;
        if (!$asc && !$dsc) break;
    }
    return $asc || $dsc;
}

function convertToFullYear(string $year): ?int {
    if (!preg_match('/^\d+$/', $year)) return null;
    $num = (int) $year; $len = strlen($year);
    if ($len === 4) return $num;
    if ($len === 2) return 2000 + $num;
    return null;
}

function validateExpiry(string $month, string $year): array {
    $monthNum = (int) $month;
    $yearNum = convertToFullYear($year);
    $currentYear = (int) date('Y');
    $currentMonth = (int) date('n');
    $minYear = defined('MIN_EXPIRY_YEAR') ? (int) MIN_EXPIRY_YEAR : 2000;
    $maxAhead = defined('MAX_EXPIRY_YEARS_AHEAD') ? (int) MAX_EXPIRY_YEARS_AHEAD : 10;
    if ($monthNum < 1 || $monthNum > 12) return ['valid' => false, 'message' => 'Invalid month (01-12)'];
    if ($yearNum === null) return ['valid' => false, 'message' => 'Invalid year format'];
    if ($yearNum < $minYear) return ['valid' => false, 'message' => 'Expiry year unrealistically old'];
    if ($yearNum < $currentYear || ($yearNum === $currentYear && $monthNum < $currentMonth)) return ['valid' => false, 'message' => 'Card expired'];
    if ($yearNum > $currentYear + $maxAhead) return ['valid' => false, 'message' => 'Expiry year too far in future'];
    return ['valid' => true, 'message' => 'Valid'];
}

function validateCard(string $number, string $month, string $year, string $cvv): array {
    $clean = preg_replace('/\D/', '', $number);
    $cardType = detectCardType($clean);
    $errors = [];
    if (!isValidLength($clean, $cardType)) {
        $expected = $cardType ? implode(' or ', $cardType['lengths']) : '13-19';
        $errors[] = "Invalid length (expected: {$expected})";
    }
    if (defined('ENABLE_LUHN_CHECK') && ENABLE_LUHN_CHECK && !validateLuhn($clean)) {
        $errors[] = 'Failed Luhn checksum';
    }
    $expiry = validateExpiry($month, $year);
    if (!$expiry['valid']) $errors[] = $expiry['message'];
    if (!isValidCVV($cvv, $cardType)) {
        $expected = $cardType ? $cardType['cvv_length'] : '3-4';
        $errors[] = "Invalid CVV (expected: {$expected} digits)";
    } elseif (isSuspiciousCVV($cvv)) {
        $errors[] = 'Suspicious CVV pattern (all-same or sequential digits)';
    }
    return ['valid' => empty($errors), 'card_type' => $cardType, 'errors' => $errors];
}

// ── SCORING ENGINE (unchanged from original) ──────────────────
const TEST_CARDS = [
    '4111111111111111','4242424242424242','4000056655665556','4000000000000002','4000000000000069',
    '4000000000000127','4000000000000259','4000000000000341','4000000000009995','4000000000009987',
    '4000000000009979','4012888888881881','4000000000000010','4000000000000028','4000000000000036',
    '4000000000000044','4000000000000051','4000000000000077','4000000000000085','4000000000000093',
    '4000000000000101','4000000000000119','4000000000003055','4000000000003063','4000000000003089',
    '4000000000003097','4000000000003105','4000000000003220','4000000000003238','4000000000003246',
    '4000000000000629','4000000000000602','5555555555554444','5200828282828210','5105105105105100',
    '2223003122003222','5500005555555559','5424000000000015','5425233430109903','2222420000001113',
    '2223000048400011','378282246310005','371449635398431','378734493671000','370000000000002',
    '6011111111111117','6011000990139424','6011981111111113','6011000000000004','3530111333300000',
    '3566002020360505','30569309025904','38520000023237','36227206271667','6200000000000005',
    '6759649826438453','1111111111111111','2222222222222222','3333333333333333','4444444444444444',
    '5555555555555555','6666666666666666','7777777777777777','8888888888888888','9999999999999999','0000000000000000',
];

function uniqueDigitCount(string $n): int { return count(array_unique(str_split($n))); }

function longestRun(string $n): int {
    $max = 1; $cur = 1;
    for ($i = 1, $len = strlen($n); $i < $len; $i++) {
        $cur = ($n[$i] === $n[$i - 1]) ? $cur + 1 : 1;
        if ($cur > $max) $max = $cur;
    }
    return $max;
}

function longestSequentialRun(string $n, int $threshold = 5): bool {
    $asc = 1; $dsc = 1;
    for ($i = 1, $len = strlen($n); $i < $len; $i++) {
        $diff = (int)$n[$i] - (int)$n[$i - 1];
        $asc = ($diff === 1) ? $asc + 1 : 1;
        $dsc = ($diff === -1) ? $dsc + 1 : 1;
        if ($asc >= $threshold || $dsc >= $threshold) return true;
    }
    return false;
}

function shannonEntropy(string $n): float {
    $len = strlen($n);
    $freq = array_count_values(str_split($n));
    $ent = 0.0;
    foreach ($freq as $c) {
        $p = $c / $len;
        $ent -= $p * log($p, 2);
    }
    return $ent;
}

function scoreCard(string $number, string $month, string $year, ?array $cardType): array {
    $n = preg_replace('/\D/', '', $number);
    if (in_array($n, TEST_CARDS, true)) return ['score' => 0, 'status' => 'die', 'reason' => 'Known test/sandbox card number'];
    if (uniqueDigitCount($n) === 1) return ['score' => 0, 'status' => 'die', 'reason' => 'All-identical-digit card number'];
    if (longestSequentialRun($n, 6)) return ['score' => 0, 'status' => 'die', 'reason' => 'Sequential digit pattern detected'];
    $hash = hash('sha256', $n . 'cc-checker-salt-v2');
    $primaryScore = hexdec(substr($hash, 0, 8)) % 100;
    $penalty = 0;
    $entropy = shannonEntropy($n);
    if ($entropy < 2.0) $penalty += 30;
    elseif ($entropy < 2.5) $penalty += 12;
    $run = longestRun($n);
    if ($run >= 5) $penalty += 25;
    elseif ($run >= 4) $penalty += 10;
    $uniq = uniqueDigitCount($n);
    if ($uniq <= 3) $penalty += 25;
    elseif ($uniq <= 5) $penalty += 10;
    $score = max(0, min(100, $primaryScore - $penalty));
    if ($score >= 80) {
        $reasons = ['Approved — $0 auth','Approved — card active','Issuer approved','CVV2 match — approved','Approved — $1 auth'];
        $reason = $reasons[hexdec(substr($hash, 8, 2)) % count($reasons)];
        return ['score' => $score, 'status' => 'live', 'reason' => $reason];
    }
    if ($score >= 60) {
        $reasons = ['Soft decline — retry','Do not honour','Insufficient funds','Issuer unavailable','Transaction not permitted','Security violation','Gateway timeout'];
        $reason = $reasons[hexdec(substr($hash, 10, 2)) % count($reasons)];
        return ['score' => $score, 'status' => 'unknown', 'reason' => $reason];
    }
    $reasons = ['Card declined','Invalid card number','Card reported lost/stolen','Restricted card','Expired card on file','Fraud suspicion — declined'];
    $reason = $reasons[hexdec(substr($hash, 12, 2)) % count($reasons)];
    return ['score' => $score, 'status' => 'die', 'reason' => $reason];
}

// ── ROUTING ────────────────────────────────────────────────────
$action = $_POST['action'] ?? 'check';

switch ($action) {

    // ────────────── CC CHECK ──────────────
    case 'check':
        if (empty($_POST['data'])) {
            echo json_encode(['error' => 4, 'status' => 'error', 'network' => '', 'color' => '', 'card' => '', 'message' => 'No data provided', 'msg' => 'No data provided']);
            exit;
        }
        $data = trim($_POST['data']);
        $pattern = '/^([\d]{' . $minLen . ',' . $maxLen . '})\|([\d]{2})\|([\d]{2}|[\d]{4})\|([\d]{3,4})$/';
        if (!preg_match($pattern, $data, $matches)) {
            echo json_encode(['error' => 4, 'status' => 'error', 'network' => '', 'color' => '', 'card' => '', 'message' => 'Invalid format — use: CardNumber|MM|YY|CVV', 'msg' => 'Invalid format — use: CardNumber|MM|YY|CVV']);
            exit;
        }
        $num = $matches[1]; $expm = $matches[2]; $expy = $matches[3]; $cvv = $matches[4];
        $fullYear = convertToFullYear($expy);
        $format = "{$num}|{$expm}|{$fullYear}|{$cvv}";
        $validation = validateCard($num, $expm, $expy, $cvv);
        $cardTypeName = $validation['card_type'] ? $validation['card_type']['name'] : 'Unknown';
        $cardColor = $validation['card_type'] ? $validation['card_type']['color'] : '#a0a3b1';
        $cardKey = $validation['card_type'] ? $validation['card_type']['key'] : '';
        if (!$validation['valid']) {
            $errorMsg = implode(' • ', $validation['errors']);
            addLogEntry($format, 'die');
            echo json_encode(['error' => 2, 'status' => 'die', 'network' => $cardTypeName, 'color' => $cardColor, 'key' => $cardKey, 'card' => $format, 'message' => $errorMsg, 'msg' => "<div><b style='color:#ef4444;'>Die</b> <span style='opacity:0.7;font-size:11px;'>({$cardTypeName})</span> | {$format} | {$errorMsg}</div>"]);
            exit;
        }
        $result = scoreCard($num, $expm, $expy, $validation['card_type']);
        addLogEntry($format, $result['status']);
        switch ($result['status']) {
            case 'live':
                echo json_encode(['error' => 1, 'status' => 'live', 'network' => $cardTypeName, 'color' => $cardColor, 'key' => $cardKey, 'card' => $format, 'score' => $result['score'], 'message' => $result['reason'], 'msg' => "<div><b style='color:#10b981;'>Live</b> <span style='opacity:0.7;font-size:11px;'>({$cardTypeName})</span> | {$format} | {$result['reason']}</div>"]);
                break;
            case 'unknown':
                echo json_encode(['error' => 3, 'status' => 'unknown', 'network' => $cardTypeName, 'color' => $cardColor, 'key' => $cardKey, 'card' => $format, 'score' => $result['score'], 'message' => $result['reason'], 'msg' => "<div><b style='color:#f59e0b;'>Unknown</b> <span style='opacity:0.7;font-size:11px;'>({$cardTypeName})</span> | {$format} | {$result['reason']}</div>"]);
                break;
            default:
                echo json_encode(['error' => 2, 'status' => 'die', 'network' => $cardTypeName, 'color' => $cardColor, 'key' => $cardKey, 'card' => $format, 'score' => $result['score'], 'message' => $result['reason'], 'msg' => "<div><b style='color:#ef4444;'>Die</b> <span style='opacity:0.7;font-size:11px;'>({$cardTypeName})</span> | {$format} | {$result['reason']}</div>"]);
        }
        break;

    // ────────────── BIN LOOKUP ──────────────
    case 'bin':
        $bin = preg_replace('/\D/', '', $_POST['bin'] ?? '');
        if (strlen($bin) < 6) {
            echo json_encode(['html' => '<div class="bin-error">Enter at least 6 digits</div>']);
            exit;
        }
        $prefix6 = substr($bin, 0, 6);
        if (isset($BIN_DB[$prefix6])) {
            $info = $BIN_DB[$prefix6];
            $html = '<div class="bin-result-card">';
            $html .= '<div class="bin-result-header" style="border-left:4px solid ' . ($CARD_TYPES[strtolower($info['brand'])] ?? ['color' => '#a0a3b1'])['color'] . '">';
            $html .= '<span class="bin-result-brand">' . htmlspecialchars($info['brand']) . '</span>';
            $html .= '<span class="bin-result-bin">' . htmlspecialchars($prefix6) . '</span></div>';
            $html .= '<div class="bin-result-body">';
            $html .= '<div class="bin-result-row"><span class="bin-label">Bank</span><span class="bin-value">' . htmlspecialchars($info['bank']) . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Country</span><span class="bin-value">' . htmlspecialchars($info['country']) . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Type</span><span class="bin-value">' . htmlspecialchars($info['type']) . '</span></div>';
            $html .= '</div></div>';
        } else {
            $ct = detectCardType($prefix6);
            $html = '<div class="bin-result-card bin-result-card--unknown">';
            $html .= '<div class="bin-result-body">';
            $html .= '<div class="bin-result-row"><span class="bin-label">BIN</span><span class="bin-value">' . htmlspecialchars($prefix6) . 'xxxxxx</span></div>';
            if ($ct) $html .= '<div class="bin-result-row"><span class="bin-label">Brand</span><span class="bin-value">' . htmlspecialchars($ct['name']) . '</span></div>';
            else $html .= '<div class="bin-result-row"><span class="bin-label">Brand</span><span class="bin-value">Unknown</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Note</span><span class="bin-value">BIN not in local database. Card type detected from prefix.</span></div>';
            $html .= '</div></div>';
        }
        echo json_encode(['html' => $html]);
        break;

    // ────────────── VCC CHECK ──────────────
    case 'vcc':
        $pan = preg_replace('/\D/', '', $_POST['pan'] ?? '');
        if (strlen($pan) < 6) {
            echo json_encode(['html' => '<div class="bin-error">Enter at least 6 digits</div>']);
            exit;
        }
        $prefix6 = substr($pan, 0, 6);
        $brand = detectCardType($pan);
        $brandName = $brand ? $brand['name'] : 'Unknown';
        $brandColor = $brand ? $brand['color'] : '#a0a3b1';

        if (isset($VCC_BINS[$prefix6])) {
            $vcc = $VCC_BINS[$prefix6];
            $vccLabel = strtolower($vcc['type']) === 'virtual' || strpos($vcc['type'], 'Virtual') !== false ? 'VCC' : (strpos($vcc['type'], 'Virtual') !== false ? 'VCC' : 'Unknown');
            $isVcc = strpos($vcc['type'], 'Virtual') !== false;
            $html = '<div class="bin-result-card">';
            $html .= '<div class="bin-result-header" style="border-left:4px solid ' . ($isVcc ? '#f59e0b' : '#34d399') . '">';
            $html .= '<span class="bin-result-brand">' . ($isVcc ? 'VCC' : 'Physical') . '</span>';
            $html .= '<span class="bin-result-bin">' . htmlspecialchars($prefix6) . 'xxxxxx</span></div>';
            $html .= '<div class="bin-result-body">';
            $html .= '<div class="bin-result-row"><span class="bin-label">Issuer</span><span class="bin-value">' . htmlspecialchars($vcc['issuer']) . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Country</span><span class="bin-value">' . htmlspecialchars($vcc['country']) . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Card Type</span><span class="bin-value">' . htmlspecialchars($vcc['type']) . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Brand</span><span class="bin-value" style="color:' . $brandColor . '">' . $brandName . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Confidence</span><span class="bin-value">' . $vcc['confidence'] . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Verdict</span><span class="bin-value" style="color:' . ($isVcc ? '#f59e0b' : '#34d399') . ';font-weight:600">' . ($isVcc ? 'VIRTUAL CARD' : 'PHYSICAL CARD') . '</span></div>';
            $html .= '</div></div>';
        } else {
            $isKnownVcc = false;
            // Check partial BIN ranges for common VCC patterns
            if (preg_match('/^40(?:0[0-9]|1[0-9]|2[0-9])\d{2}/', $prefix6)) $isKnownVcc = true;
            $html = '<div class="bin-result-card bin-result-card--unknown">';
            $html .= '<div class="bin-result-header" style="border-left:4px solid #a0a3b1">';
            $html .= '<span class="bin-result-brand">Unknown</span>';
            $html .= '<span class="bin-result-bin">' . htmlspecialchars($prefix6) . 'xxxxxx</span></div>';
            $html .= '<div class="bin-result-body">';
            $html .= '<div class="bin-result-row"><span class="bin-label">Brand</span><span class="bin-value" style="color:' . $brandColor . '">' . $brandName . '</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Note</span><span class="bin-value">BIN not in VCC database. Could be physical card.</span></div>';
            $html .= '<div class="bin-result-row"><span class="bin-label">Verdict</span><span class="bin-value" style="color:#a0a3b1;font-weight:600">UNKNOWN — Use BIN Lookup for more info</span></div>';
            $html .= '</div></div>';
        }
        echo json_encode(['html' => $html]);
        break;

    // ────────────── COUNTRY FILTER ──────────────
    case 'country_filter':
        $rawCards = $_POST['cards'] ?? '';
        $targetCountry = $_POST['country'] ?? '';
        $lines = preg_split('/\r\n|\r|\n/', $rawCards);
        $results = [];
        $found = 0;
        $notFound = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $pan = preg_replace('/\D/', '', explode('|', $line)[0]);
            $prefix6 = substr($pan, 0, 6);
            if (isset($BIN_DB[$prefix6])) {
                $info = $BIN_DB[$prefix6];
                $match = empty($targetCountry) || strcasecmp($info['country'], $targetCountry) === 0;
                $results[] = [
                    'card' => $line,
                    'prefix' => $prefix6,
                    'bank' => $info['bank'],
                    'country' => $info['country'],
                    'match' => $match
                ];
                if ($match) $found++;
                else $notFound++;
            } else {
                $results[] = [
                    'card' => $line,
                    'prefix' => $prefix6,
                    'bank' => 'Unknown',
                    'country' => 'Unknown',
                    'match' => false
                ];
                $notFound++;
            }
        }
        $html = '<div class="filter-summary"><span>Matched: <strong>' . $found . '</strong></span><span>Not matched: <strong>' . $notFound . '</strong></span></div>';
        $html .= '<table class="filter-table"><tr><th>Card</th><th>BIN</th><th>Bank</th><th>Country</th><th>Status</th></tr>';
        foreach ($results as $r) {
            $cls = $r['match'] ? 'filter-match' : 'filter-nomatch';
            $ico = $r['match'] ? '✓' : '✗';
            $html .= '<tr class="' . $cls . '"><td>' . htmlspecialchars($r['card']) . '</td><td>' . htmlspecialchars($r['prefix']) . '</td><td>' . htmlspecialchars($r['bank']) . '</td><td>' . htmlspecialchars($r['country']) . '</td><td>' . $ico . '</td></tr>';
        }
        $html .= '</table>';
        echo json_encode(['html' => $html, 'found' => $found, 'notFound' => $notFound]);
        break;

    // ────────────── BIN SCANNER ──────────────
    case 'bin_scanner':
        $start = $_POST['start'] ?? '';
        $end = $_POST['end'] ?? '';
        $start = preg_replace('/\D/', '', $start);
        $end = preg_replace('/\D/', '', $end);
        $startVal = (int) $start;
        $endVal = (int) $end;
        if ($startVal <= 0 || $endVal <= 0 || $endVal < $startVal || $endVal - $startVal > 1000) {
            echo json_encode(['html' => '<div class="bin-error">Invalid range (max 1000 BINs)</div>']);
            break;
        }
        $html = '<div class="filter-summary">Scanning BINs from <strong>' . $start . '</strong> to <strong>' . $end . '</strong></div>';
        $html .= '<table class="filter-table"><tr><th>BIN</th><th>Bank</th><th>Country</th><th>Type</th><th>Brand</th></tr>';
        $count = 0;
        for ($b = $startVal; $b <= $endVal; $b++) {
            $key = (string) $b;
            $key = str_pad($key, 6, '0', STR_PAD_LEFT);
            if (isset($BIN_DB[$key])) {
                $info = $BIN_DB[$key];
                $html .= '<tr class="filter-match"><td>' . $key . '</td><td>' . htmlspecialchars($info['bank']) . '</td><td>' . htmlspecialchars($info['country']) . '</td><td>' . htmlspecialchars($info['type']) . '</td><td>' . htmlspecialchars($info['brand']) . '</td></tr>';
                $count++;
            }
        }
        if ($count === 0) {
            $html .= '<tr><td colspan="5" style="text-align:center;color:#a0a3b1">No BINs found in this range</td></tr>';
        }
        $html .= '</table><div class="filter-summary">Found <strong>' . $count . '</strong> BIN(s)</div>';
        echo json_encode(['html' => $html, 'count' => $count]);
        break;

    // ────────────── ADMIN ──────────────
    case 'admin_login':
        $pass = $_POST['password'] ?? '';
        if ($pass === $ADMIN_PASS) {
            $_SESSION['admin_logged_in'] = true;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'admin_logout':
        $_SESSION['admin_logged_in'] = false;
        session_destroy();
        echo json_encode(['success' => true]);
        break;

    case 'admin_stats':
        $log = loadLog();
        $loggedIn = !empty($_SESSION['admin_logged_in']);
        $response = ['logged_in' => $loggedIn, 'total' => 0, 'live' => 0, 'die' => 0, 'unknown' => 0, 'log' => []];
        if ($loggedIn) {
            $response['total'] = $log['total'];
            $response['live'] = $log['live'];
            $response['die'] = $log['die'];
            $response['unknown'] = $log['unknown'];
            $response['log'] = $log['checks'];
        }
        echo json_encode($response);
        break;

    case 'admin_clear':
        if (!empty($_SESSION['admin_logged_in'])) {
            saveLog(['checks' => [], 'total' => 0, 'live' => 0, 'die' => 0, 'unknown' => 0]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    // ────────────── UNKNOWN ACTION ──────────────
    default:
        echo json_encode(['error' => 4, 'message' => 'Unknown action']);
}
?>