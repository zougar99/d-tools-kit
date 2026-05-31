<?php
/**
 * Configuration file for CC Checker
 * Enhanced with robust bank algorithm validation settings
 * 
 * @author OshekharO
 */

// ============================================
// Card Validation Settings
// ============================================

// Enable Luhn algorithm validation (ISO/IEC 7812-1)
define('ENABLE_LUHN_CHECK', true);

// Card number length constraints
define('MIN_CARD_LENGTH', 13);  // Minimum: 13 digits (some older Visa cards)
define('MAX_CARD_LENGTH', 19);  // Maximum: 19 digits (some UnionPay cards)

// ============================================
// Expiry Validation
// ============================================

// Reject obviously wrong expiry years (typos / garbage), independent of "not expired"
define('MIN_EXPIRY_YEAR', 2000);

// Maximum years after the current calendar year (issuer cards are rarely valid >10y)
define('MAX_EXPIRY_YEARS_AHEAD', 10);

// Legacy alias used by older copies of api.php fallbacks
define('MIN_VALID_YEAR', (int) date('Y'));

// Public contact (footer + structured data)
define('CONTACT_EMAIL', 'werlist99@outlook.com');

// ============================================
// Supported Card Types (IIN/BIN Ranges)
// ============================================
// The following card types are automatically detected:
// - Visa (4xxx)
// - Mastercard (51xx-55xx, 222100-272099)
// - American Express (34xx, 37xx)
// - Discover (6011, 644-649, 65)
// - Diners Club (300-305, 36, 38)
// - JCB (2131, 1800, 35)
// - UnionPay (62)
// - Maestro (5018, 5020, 5038, 5893, 6304, 6759, 6761-6763)

?>
