<?php
header('Content-Type: application/json; charset=utf-8');

$VCC_BINS = [
    '406469' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '406470' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '489403' => ['issuer' => 'EntroPay', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
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
    '531410' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531411' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531412' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '531413' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '524961' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '527566' => ['issuer' => 'Skrill', 'country' => 'United Kingdom', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '528810' => ['issuer' => 'Paysafecard', 'country' => 'Austria', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '400220' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400221' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400222' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400223' => ['issuer' => 'Revolut', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '440446' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '440447' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '440448' => ['issuer' => 'Vivid Money', 'country' => 'Germany', 'type' => 'Virtual', 'confidence' => 'High'],
    '486209' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '486210' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '535543' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '535544' => ['issuer' => 'N26', 'country' => 'Germany', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '461443' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '461444' => ['issuer' => 'Monese', 'country' => 'United Kingdom', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '536963' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536964' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536965' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536966' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '536967' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '222757' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '222758' => ['issuer' => 'Wise', 'country' => 'United Kingdom', 'type' => 'Virtual', 'confidence' => 'High'],
    '400810' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '400811' => ['issuer' => 'Stripe Issuing', 'country' => 'United States', 'type' => 'Virtual/Physical', 'confidence' => 'Medium'],
    '428830' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
    '428831' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
    '428832' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
    '428833' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
    '428834' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
    '428835' => ['issuer' => 'Privacy.com', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'High'],
    '513268' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid', 'confidence' => 'Medium'],
    '513269' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid', 'confidence' => 'Medium'],
    '529558' => ['issuer' => 'Payoneer', 'country' => 'United States', 'type' => 'Prepaid', 'confidence' => 'Medium'],
    '402036' => ['issuer' => 'US Bank Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '403448' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '403449' => ['issuer' => 'Capital One Virtual', 'country' => 'United States', 'type' => 'Virtual', 'confidence' => 'Medium'],
    '400234' => ['issuer' => 'Neteller', 'country' => 'Canada', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '408562' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '408563' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '408564' => ['issuer' => 'Epay', 'country' => 'United States', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
    '533128' => ['issuer' => 'Paysafecard', 'country' => 'Austria', 'type' => 'Prepaid Virtual', 'confidence' => 'High'],
];

$CARD_TYPES = [
    'visa' => ['name' => 'Visa', 'patterns' => ['/^4/'], 'color' => '#1434CB'],
    'mastercard' => ['name' => 'Mastercard', 'patterns' => ['/^5[1-5]/', '/^2(?:2(?:2[1-9]|[3-9]\d)|[3-6]\d\d|7(?:[01]\d|20))/'], 'color' => '#EB001B'],
    'amex' => ['name' => 'American Express', 'patterns' => ['/^3[47]/'], 'color' => '#007B5E'],
    'discover' => ['name' => 'Discover', 'patterns' => ['/^6011/', '/^65/', '/^64[4-9]/'], 'color' => '#FF6600'],
    'diners' => ['name' => 'Diners Club', 'patterns' => ['/^3(?:0[0-5]|[68])/'], 'color' => '#004A97'],
    'jcb' => ['name' => 'JCB', 'patterns' => ['/^(?:2131|1800|35)/'], 'color' => '#003087'],
    'maestro' => ['name' => 'Maestro', 'patterns' => ['/^(?:5018|5020|5038|5893|6304|6759|676[1-3])/'], 'color' => '#009BDE'],
    'mir' => ['name' => 'Mir', 'patterns' => ['/^220[0-4]/'], 'color' => '#4CAF50'],
    'troy' => ['name' => 'Troy', 'patterns' => ['/^9792/'], 'color' => '#E63946'],
    'unionpay' => ['name' => 'UnionPay', 'patterns' => ['/^62/'], 'color' => '#CC0000'],
];

function detectCardType(string $number): ?array {
    global $CARD_TYPES;
    $clean = preg_replace('/\D/', '', $number);
    foreach ($CARD_TYPES as $type) {
        foreach ($type['patterns'] as $pattern) {
            if (preg_match($pattern, $clean)) return $type;
        }
    }
    return null;
}

$pan = preg_replace('/\D/', '', $_POST['pan'] ?? '');
if (strlen($pan) < 6) {
    echo json_encode(['html' => '<div class="vcc-error">Enter at least 6 digits</div>']);
    exit;
}

$prefix6 = substr($pan, 0, 6);
$brand = detectCardType($pan);
$brandName = $brand ? $brand['name'] : 'Unknown';
$brandColor = $brand ? $brand['color'] : '#a0a3b1';

if (isset($VCC_BINS[$prefix6])) {
    $vcc = $VCC_BINS[$prefix6];
    $isVcc = stripos($vcc['type'], 'Virtual') !== false;
    $statusText = $isVcc ? 'VCC' : 'PHYSICAL';
    $statusColor = $isVcc ? '#f59e0b' : '#34d399';
    $iconSvg = $isVcc ? '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>' : '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#34d399" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>';

    $html = '<div class="vcc-card vcc-card--known">';
    $html .= '<div class="vcc-status-banner" style="background:' . $statusColor . '20;border-left:4px solid ' . $statusColor . '">';
    $html .= $iconSvg;
    $html .= '<span class="vcc-status-text" style="color:' . $statusColor . '">' . $statusText . '</span>';
    $html .= '<span class="vcc-pan">' . htmlspecialchars($prefix6) . 'xxxxxx</span>';
    $html .= '</div>';
    $html .= '<div class="vcc-details">';
    $html .= '<div class="vcc-row"><span class="vcc-label">Issuer</span><span class="vcc-value">' . htmlspecialchars($vcc['issuer']) . '</span></div>';
    $html .= '<div class="vcc-row"><span class="vcc-label">Country</span><span class="vcc-value">' . htmlspecialchars($vcc['country']) . '</span></div>';
    $html .= '<div class="vcc-row"><span class="vcc-label">Type</span><span class="vcc-value">' . htmlspecialchars($vcc['type']) . '</span></div>';
    $html .= '<div class="vcc-row"><span class="vcc-label">Brand</span><span class="vcc-value" style="color:' . $brandColor . '">' . $brandName . '</span></div>';
    $html .= '<div class="vcc-row"><span class="vcc-label">Confidence</span><span class="vcc-value">' . $vcc['confidence'] . '</span></div>';
    $html .= '</div></div>';
} else {
    $html = '<div class="vcc-card vcc-card--unknown">';
    $html .= '<div class="vcc-status-banner" style="border-left:4px solid #a0a3b1">';
    $html .= '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#a0a3b1" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    $html .= '<span class="vcc-status-text" style="color:#a0a3b1">UNKNOWN</span>';
    $html .= '<span class="vcc-pan">' . htmlspecialchars($prefix6) . 'xxxxxx</span>';
    $html .= '</div>';
    $html .= '<div class="vcc-details">';
    $html .= '<div class="vcc-row"><span class="vcc-label">Brand</span><span class="vcc-value" style="color:' . $brandColor . '">' . $brandName . '</span></div>';
    $html .= '<div class="vcc-row"><span class="vcc-label">Note</span><span class="vcc-value vcc-note">BIN not found in VCC database. Likely a physical card.</span></div>';
    $html .= '</div></div>';
}

echo json_encode(['html' => $html]);
?>