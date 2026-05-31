<?php
if (is_file(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}
if (!defined('MIN_EXPIRY_YEAR')) {
    define('MIN_EXPIRY_YEAR', 2000);
}
if (!defined('MAX_EXPIRY_YEARS_AHEAD')) {
    define('MAX_EXPIRY_YEARS_AHEAD', 10);
}
if (!defined('CONTACT_EMAIL')) {
    define('CONTACT_EMAIL', 'werlist99@outlook.com');
}
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CC Checker Pro - Validation, BIN Lookup & Generator Tools</title>
  <meta name="description" content="Free credit card checker, BIN lookup, bulk generator and CVV validator tools.">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo $currentUrl; ?>">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💳</text></svg>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <script>
    window.__CC_CHECKER_CFG = {
      minExpiryYear: <?php echo (int) (defined('MIN_EXPIRY_YEAR') ? MIN_EXPIRY_YEAR : 2000); ?>,
      maxExpiryYearsAhead: <?php echo (int) (defined('MAX_EXPIRY_YEARS_AHEAD') ? MAX_EXPIRY_YEARS_AHEAD : 10); ?>
    };
  </script>
</head>
<body>
  <div class="container-main">
    <header class="page-header">
      <h1 class="page-title">CC Checker Pro</h1>
      <p class="page-subtitle">Validation, BIN lookup, card generator &amp; CVV checker</p>
      <p class="edu-strip" role="note">Educational use only. "Live/Die" is a local simulation, not issuer approval.</p>
    </header>

    <!-- Theme Switcher -->
    <div class="theme-switcher" id="theme-switcher">
      <button class="theme-dot active" data-theme="glass" title="Glassmorphism"></button>
      <button class="theme-dot" data-theme="cyber" title="Cyberpunk"></button>
      <button class="theme-dot" data-theme="minimal" title="Minimal Clean"></button>
      <button class="theme-dot" data-theme="premium" title="Dark Premium"></button>
      <button class="theme-dot" data-theme="3d" title="3D Glass"></button>
    </div>

    <!-- Tab Navigation -->
    <nav class="tab-nav" role="tablist">
      <button class="tab-btn active" data-tab="cc" role="tab">CC Checker</button>
      <button class="tab-btn" data-tab="bin" role="tab">BIN Lookup</button>
      <button class="tab-btn" data-tab="gen" role="tab">Generator</button>
      <button class="tab-btn" data-tab="cvv" role="tab">CVV Checker</button>
      <button class="tab-btn" data-tab="vcc" role="tab">VCC Checker</button>
      <button class="tab-btn" data-tab="filter" role="tab">Country Filter</button>
      <button class="tab-btn" data-tab="scanner" role="tab">BIN Scanner</button>
      <button class="tab-btn" data-tab="admin" role="tab">Admin</button>
    </nav>

    <!-- ==================== TAB 1: CC CHECKER ==================== -->
    <div class="tab-panel active" id="tab-cc" role="tabpanel">
      <div class="info" id="cc-info-message" role="status" aria-live="polite"></div>
      <form method="post" action="api.php" role="form" id="cc-form">
        <div class="box-body">
          <div class="box-content main-card">
            <label for="cc-input" class="form-label field-label">Card numbers</label>
            <textarea class="form-control" rows="8" id="cc-input" name="cc"
              title="Format: card_number|MM|YY|CVV or card_number|MM|YYYY|CVV"
              placeholder="5301272453912345|05|25|653&#10;4111111111111111|12|2026|123&#10;378282246310005|08|27|1234"
              required aria-describedby="cc-format-help" autocomplete="off" spellcheck="false"></textarea>
            <small id="cc-format-help" class="format-help">
              One per line: <code class="format-code">PAN|MM|YY|CVV</code> or <code class="format-code">PAN|MM|YYYY|CVV</code> — Ctrl+Enter to start.
            </small>
            <div class="progress-wrap" id="cc-progress-container" style="display:none;">
              <div class="progress-bar-container" aria-hidden="true">
                <div class="progress-bar" id="cc-progress-bar"></div>
              </div>
              <div class="progress-meta" id="cc-progress-meta" aria-live="polite"></div>
            </div>
            <div class="button text-center mb-3 mt-3">
              <button type="submit" class="btn btn-outline-success" id="cc-start-btn">START</button>
              <button type="button" class="btn btn-outline-danger" id="cc-stop-btn" disabled>STOP</button>
            </div>
            <div class="stats-summary" id="cc-stats-summary" style="display:none;">
              <div class="stat-item"><div class="stat-value" id="cc-stat-total">0</div><div class="stat-label">Total</div></div>
              <div class="stat-item"><div class="stat-value" id="cc-stat-processed">0</div><div class="stat-label">Processed</div></div>
              <div class="stat-item stat-item--live"><div class="stat-value stat-value--live" id="cc-stat-live">0</div><div class="stat-label">Live</div></div>
              <div class="stat-item stat-item--die"><div class="stat-value stat-value--die" id="cc-stat-die">0</div><div class="stat-label">Die</div></div>
              <div class="stat-item stat-item--unknown"><div class="stat-value stat-value--unknown" id="cc-stat-unknown">0</div><div class="stat-label">Unknown</div></div>
            </div>
          </div>
        </div>
        <div class="box-title"><h3 class="panel-title alert alert-primary"><span>Live</span><span class="badge bg-success live" id="cc-live-count">0</span></h3></div>
        <div class="box-body"><div class="box-content alert alert-success"><div class="panel-body success" id="cc-success-panel"></div></div></div>
        <div class="box-title"><h3 class="panel-title alert alert-primary"><span>Die</span><span class="badge bg-danger die" id="cc-die-count">0</span></h3></div>
        <div class="box-body"><div class="box-content alert alert-danger"><div class="panel-body danger" id="cc-danger-panel"></div></div></div>
        <div class="box-title"><h3 class="panel-title alert alert-primary"><span>Unknown</span><span class="badge bg-warning unknown" id="cc-unknown-count">0</span></h3></div>
        <div class="box-body"><div class="box-content alert alert-warning"><div class="panel-body warning" id="cc-warning-panel"></div></div></div>
      </form>
    </div>

    <!-- ==================== TAB 2: BIN LOOKUP ==================== -->
    <div class="tab-panel" id="tab-bin" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <label for="bin-input" class="form-label field-label">BIN / IIN Number</label>
          <div class="bin-input-group">
            <input type="text" class="form-control" id="bin-input" maxlength="6" placeholder="e.g. 414720" autocomplete="off" inputmode="numeric">
            <button class="btn btn-outline-primary" id="bin-lookup-btn">Lookup</button>
            <button class="btn btn-outline-secondary" id="bin-clear-btn">Clear</button>
          </div>
          <small class="format-help">Enter first 6 digits of a card number to identify the issuer.</small>
          <div id="bin-result" class="bin-result"></div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB 3: GENERATOR ==================== -->
    <div class="tab-panel" id="tab-gen" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <label class="form-label field-label">Generate Test Card Numbers</label>
          <div class="gen-form">
            <div class="gen-row">
              <div class="gen-field">
                <label for="gen-count">Count</label>
                <input type="number" class="form-control" id="gen-count" value="10" min="1" max="500">
              </div>
              <div class="gen-field">
                <label for="gen-bin">BIN Prefix (optional)</label>
                <input type="text" class="form-control" id="gen-bin" maxlength="6" placeholder="e.g. 414720">
              </div>
              <div class="gen-field">
                <label for="gen-type">Card Type</label>
                <select class="form-control" id="gen-type">
                  <option value="">Random</option>
                  <option value="visa">Visa</option>
                  <option value="mastercard">Mastercard</option>
                  <option value="amex">Amex</option>
                  <option value="discover">Discover</option>
                </select>
              </div>
            </div>
            <div class="gen-row">
              <div class="gen-field">
                <label for="gen-exp">Expiry</label>
                <select class="form-control" id="gen-exp">
                  <option value="random">Random valid</option>
                  <option value="future">Future (1yr+)</option>
                  <option value="current">Current month</option>
                </select>
              </div>
              <div class="gen-field">
                <label for="gen-cvv">CVV</label>
                <select class="form-control" id="gen-cvv">
                  <option value="random">Random valid</option>
                  <option value="gen">Generated</option>
                  <option value="none">None</option>
                </select>
              </div>
              <div class="gen-field gen-field--btn">
                <button class="btn btn-outline-success" id="gen-btn">Generate</button>
                <button class="btn btn-outline-warning" id="gen-copy-btn">Copy</button>
              </div>
            </div>
          </div>
          <div id="gen-result" class="gen-result">
            <textarea class="form-control" id="gen-output" rows="10" readonly placeholder="Generated card numbers will appear here..."></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB 4: CVV CHECKER ==================== -->
    <div class="tab-panel" id="tab-cvv" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <label for="cvv-input" class="form-label field-label">Check CVV Format</label>
          <div class="cvv-input-group">
            <div class="cvv-field">
              <input type="text" class="form-control" id="cvv-input" maxlength="4" placeholder="CVV (e.g. 123)" inputmode="numeric">
            </div>
            <div class="cvv-field">
              <select class="form-control" id="cvv-card-type">
                <option value="">Auto detect</option>
                <option value="visa">Visa (3 digits)</option>
                <option value="mastercard">Mastercard (3 digits)</option>
                <option value="amex">Amex (4 digits)</option>
                <option value="discover">Discover (3 digits)</option>
                <option value="other">Other (3-4 digits)</option>
              </select>
            </div>
            <button class="btn btn-outline-primary" id="cvv-check-btn">Check</button>
          </div>
          <div id="cvv-result" class="cvv-result"></div>
          <hr class="cvv-divider">
          <label class="form-label field-label mt-3">Bulk CVV Check</label>
          <textarea class="form-control" rows="5" id="cvv-bulk-input" placeholder="Paste CVVs, one per line..."></textarea>
          <button class="btn btn-outline-primary mt-2" id="cvv-bulk-btn">Check Bulk</button>
          <div id="cvv-bulk-result" class="cvv-result"></div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB 5: VCC CHECKER ==================== -->
    <div class="tab-panel" id="tab-vcc" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <label for="vcc-input" class="form-label field-label">Check if Card is VCC (Virtual)</label>
          <div class="bin-input-group">
            <input type="text" class="form-control" id="vcc-input" maxlength="19" placeholder="Enter full card number or first 6 digits" autocomplete="off" inputmode="numeric">
            <button class="btn btn-outline-primary" id="vcc-check-btn">Check VCC</button>
            <button class="btn btn-outline-secondary" id="vcc-clear-btn">Clear</button>
          </div>
          <small class="format-help">Enter a card number to determine if it's a Virtual Credit Card (VCC) or physical card.</small>
          <div id="vcc-result" class="bin-result"></div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB 6: COUNTRY FILTER ==================== -->
    <div class="tab-panel" id="tab-filter" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <label for="filter-cards" class="form-label field-label">Paste Cards to Filter by Country</label>
          <textarea class="form-control" rows="6" id="filter-cards" placeholder="Paste card numbers (one per line)&#10;e.g. 4147201234567890|05|25|123&#10;4921819876543210|12|26|456" autocomplete="off" spellcheck="false"></textarea>
          <div class="filter-controls">
            <div class="filter-field">
              <label for="filter-country">Target Country</label>
              <input type="text" class="form-control" id="filter-country" placeholder="e.g. Saudi Arabia" list="country-list">
              <datalist id="country-list">
                <option value="Saudi Arabia"><option value="United Arab Emirates"><option value="Kuwait"><option value="Qatar"><option value="France"><option value="United Kingdom"><option value="Germany"><option value="Spain"><option value="Italy"><option value="United States"><option value="Brazil"><option value="Canada"><option value="Australia"><option value="India"><option value="China"><option value="Japan"><option value="Malaysia"><option value="Singapore"><option value="Morocco"><option value="Egypt"><option value="Nigeria"><option value="Turkey"><option value="Russia"><option value="Mexico">
              </datalist>
            </div>
            <button class="btn btn-outline-primary" id="filter-btn">Filter</button>
            <button class="btn btn-outline-secondary" id="filter-clear-btn">Clear</button>
          </div>
          <small class="format-help">Cards matching the selected country will be highlighted in green.</small>
          <div id="filter-result" class="filter-result"></div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB 7: BIN SCANNER ==================== -->
    <div class="tab-panel" id="tab-scanner" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <label class="form-label field-label">Scan BIN Range</label>
          <div class="scanner-controls">
            <div class="scanner-field">
              <label for="scanner-start">Start BIN (6 digits)</label>
              <input type="text" class="form-control" id="scanner-start" maxlength="6" placeholder="414720" inputmode="numeric">
            </div>
            <div class="scanner-field">
              <label for="scanner-end">End BIN (6 digits)</label>
              <input type="text" class="form-control" id="scanner-end" maxlength="6" placeholder="414730" inputmode="numeric">
            </div>
            <button class="btn btn-outline-primary" id="scanner-btn">Scan</button>
            <button class="btn btn-outline-secondary" id="scanner-clear-btn">Clear</button>
          </div>
          <small class="format-help">Scan a range of BINs to find which banks and countries exist. Max 1000 BINs per scan.</small>
          <div id="scanner-result" class="filter-result"></div>
        </div>
      </div>
    </div>

    <!-- ==================== TAB 8: ADMIN ==================== -->
    <div class="tab-panel" id="tab-admin" role="tabpanel">
      <div class="box-body">
        <div class="box-content">
          <div id="admin-login">
            <label class="form-label field-label">Admin Access</label>
            <div class="admin-login-row">
              <input type="password" class="form-control" id="admin-password" placeholder="Enter password">
              <button class="btn btn-outline-primary" id="admin-login-btn">Login</button>
            </div>
            <small class="format-help">Default password: <code>admin123</code></small>
          </div>
          <div id="admin-panel" style="display:none;">
            <div class="admin-header">
              <h3>Dashboard</h3>
              <button class="btn btn-outline-danger btn-sm" id="admin-logout-btn">Logout</button>
            </div>
            <div class="admin-stats" id="admin-stats">
              <div class="admin-stat-card"><div class="admin-stat-value" id="admin-stat-total">0</div><div>Total Checks</div></div>
              <div class="admin-stat-card"><div class="admin-stat-value" id="admin-stat-live">0</div><div>Live</div></div>
              <div class="admin-stat-card"><div class="admin-stat-value" id="admin-stat-die">0</div><div>Die</div></div>
              <div class="admin-stat-card"><div class="admin-stat-value" id="admin-stat-unknown">0</div><div>Unknown</div></div>
            </div>
            <div class="admin-section">
              <h4>Recent Checks <button class="btn btn-outline-warning btn-sm" id="admin-clear-log-btn">Clear Log</button></h4>
              <div id="admin-log" class="admin-log"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="site-footer">
      <span class="site-footer__label">Contact</span>
      <a class="site-footer__link" href="mailto:<?php echo htmlspecialchars(CONTACT_EMAIL, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(CONTACT_EMAIL, ENT_QUOTES, 'UTF-8'); ?></a>
    </footer>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
  <script>
  $(document).ready(function() {
    // ============================
    // Theme Switcher
    // ============================
    var savedTheme = localStorage.getItem('cc-theme') || 'glass';
    $('html').attr('data-theme', savedTheme);
    $('.theme-dot[data-theme="' + savedTheme + '"]').addClass('active').siblings().removeClass('active');

    $(document).on('click', '.theme-dot', function() {
      var theme = $(this).data('theme');
      $('html').attr('data-theme', theme);
      localStorage.setItem('cc-theme', theme);
      $(this).addClass('active').siblings().removeClass('active');
    });

    // ============================
    // Tab Navigation
    // ============================
    $('.tab-btn').on('click', function() {
      $('.tab-btn').removeClass('active');
      $('.tab-panel').removeClass('active');
      $(this).addClass('active');
      $('#tab-' + $(this).data('tab')).addClass('active');
    });

    // ============================
    // CARD TYPE DEFINITIONS
    // ============================
    const CARD_TYPES = {
      mir: { name: 'Mir', patterns: [/^220[0-4]/], lengths: [16], cvvLength: 3 },
      visa: { name: 'Visa', patterns: [/^4/], lengths: [13, 16, 19], cvvLength: 3 },
      mastercard: { name: 'Mastercard', patterns: [/^5[1-5]/, /^2(?:2(?:2[1-9]|[3-9]\d)|[3-6]\d\d|7(?:[01]\d|20))/], lengths: [16], cvvLength: 3 },
      amex: { name: 'Amex', patterns: [/^3[47]/], lengths: [15], cvvLength: 4 },
      discover: { name: 'Discover', patterns: [/^6011/, /^65/, /^64[4-9]/, /^622(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d\d|9(?:[01]\d|2[0-5]))/], lengths: [16, 19], cvvLength: 3 },
      diners: { name: 'Diners', patterns: [/^3(?:0[0-5]|[68])/], lengths: [14, 16, 19], cvvLength: 3 },
      jcb: { name: 'JCB', patterns: [/^(?:2131|1800|35)/], lengths: [16, 17, 18, 19], cvvLength: 3 },
      maestro: { name: 'Maestro', patterns: [/^(?:5018|5020|5038|5893|6304|6759|676[1-3])/], lengths: [12, 13, 14, 15, 16, 17, 18, 19], cvvLength: 3 },
      troy: { name: 'Troy', patterns: [/^9792/], lengths: [16], cvvLength: 3 },
      unionpay: { name: 'UnionPay', patterns: [/^62/], lengths: [16, 17, 18, 19], cvvLength: 3 }
    };

    function detectCardType(num) {
      const n = num.replace(/\D/g, '');
      for (const ct of Object.values(CARD_TYPES)) {
        for (const p of ct.patterns) {
          if (p.test(n)) return ct;
        }
      }
      return null;
    }

    // Luhn algorithm
    function isValidLuhn(num) {
      if (!num || !/^\d+$/.test(num)) return false;
      const d = num.split('').map(Number).reverse();
      let s = 0;
      for (let i = 0; i < d.length; i++) {
        let v = d[i];
        if (i % 2 === 1) { v *= 2; if (v > 9) v -= 9; }
        s += v;
      }
      return s % 10 === 0;
    }

    // ============================
    // TAB 1: CC CHECKER
    // ============================
    (function() {
      const $ = jQuery;
      const el = {
        form: $('#cc-form'), startBtn: $('#cc-start-btn'), stopBtn: $('#cc-stop-btn'),
        input: $('#cc-input'), liveCount: $('#cc-live-count'), dieCount: $('#cc-die-count'),
        unknownCount: $('#cc-unknown-count'), successPanel: $('#cc-success-panel'),
        dangerPanel: $('#cc-danger-panel'), warningPanel: $('#cc-warning-panel'),
        info: $('#cc-info-message'), progressContainer: $('#cc-progress-container'),
        progressBar: $('#cc-progress-bar'), progressMeta: $('#cc-progress-meta'),
        stats: $('#cc-stats-summary'), statTotal: $('#cc-stat-total'),
        statProcessed: $('#cc-stat-processed'), statLive: $('#cc-stat-live'),
        statDie: $('#cc-stat-die'), statUnknown: $('#cc-stat-unknown')
      };
      let processing = false, shouldStop = false, total = 0, processed = 0;

      function updateCounter($el, val) {
        const c = parseInt($el.text(), 10) || 0;
        $el.text(c + val);
        $el.css('transform', 'scale(1.2)');
        setTimeout(() => $el.css('transform', 'scale(1)'), 200);
      }

      function displayMessage($panel, msg) { $panel.prepend(msg); $panel.scrollTop(0); }

      function showInfo(msg, type) {
        const colors = { info: 'var(--accent-info)', error: 'var(--accent-danger)', success: 'var(--accent-success)' };
        el.info.html(msg).css('border-color', colors[type] || colors.info).show();
        setTimeout(() => el.info.fadeOut(300), 5000);
      }

      function updateProgress(cur, tot) {
        const pct = tot > 0 ? Math.round((cur / tot) * 100) : 0;
        el.progressBar.css('width', pct + '%');
        el.statProcessed.text(cur);
        el.progressMeta.text(tot > 0 ? 'Processed ' + cur + ' of ' + tot + ' (' + pct + '%)' : '');
      }

      function syncStats() {
        el.statLive.text(el.liveCount.text());
        el.statDie.text(el.dieCount.text());
        el.statUnknown.text(el.unknownCount.text());
      }

      function validateCard(data) {
        const parts = data.split('|');
        if (parts.length !== 4) return { valid: false, errors: ['Invalid format'], cardNumber: '' };
        const [num, month, year, cvv] = parts.map(p => p.trim());
        const clean = num.replace(/\D/g, '');
        const ct = detectCardType(clean);
        const errs = [];
        if (ct) { if (!ct.lengths.includes(clean.length)) errs.push('Invalid length'); }
        else if (clean.length < 13 || clean.length > 19) errs.push('Invalid length');
        if (!isValidLuhn(clean)) errs.push('Failed Luhn');
        const m = parseInt(month, 10), y = parseInt(year, 10);
        if (isNaN(m) || m < 1 || m > 12) errs.push('Invalid month');
        else {
          const now = new Date(), cy = now.getFullYear(), cm = now.getMonth() + 1;
          const fy = year.length === 2 ? 2000 + y : y;
          if (fy < cy || (fy === cy && m < cm)) errs.push('Expired');
          if (fy > cy + 10) errs.push('Year too far');
        }
        if (!/^\d+$/.test(cvv) || cvv.length < 3 || cvv.length > 4) errs.push('Invalid CVV');
        else if (ct && cvv.length !== ct.cvvLength) errs.push('CVV should be ' + ct.cvvLength + ' digits');
        return { valid: errs.length === 0, errors: errs, cardType: ct, cardNumber: clean, month, year, cvv };
      }

      async function processCC(cc) {
        try {
          const v = validateCard(cc);
          if (!v.valid) {
            displayMessage(el.dangerPanel, '<div><b style="color:#ef4444">Invalid</b> | ' + cc + ' | ' + v.errors.join(', ') + '</div>');
            updateCounter(el.dieCount, 1); return;
          }
          const r = await $.post(el.form.attr('action'), { data: cc });
          const target = r.error === 1 ? el.successPanel : r.error === 2 ? el.dangerPanel : el.warningPanel;
          displayMessage(target, r.msg);
          if (r.error === 1) updateCounter(el.liveCount, 1);
          else if (r.error === 2) updateCounter(el.dieCount, 1);
          else if (r.error === 3) updateCounter(el.unknownCount, 1);
        } catch(e) {
          displayMessage(el.warningPanel, '<div><b style="color:#ef4444">Error</b> | ' + cc + ' | Processing failed</div>');
          updateCounter(el.unknownCount, 1);
        }
        syncStats();
      }

      async function processAll(list) {
        total = list.length; processed = 0;
        el.statTotal.text(total); el.stats.show(); el.progressContainer.show();
        for (const cc of list) {
          if (shouldStop) { showInfo('Stopped by user', 'info'); break; }
          await processCC(cc.trim());
          processed++;
          updateProgress(processed, total);
          await new Promise(r => setTimeout(r, 100));
        }
        processing = false;
        el.startBtn.prop('disabled', false).html('START');
        el.stopBtn.prop('disabled', true);
        el.input.prop('disabled', false).val('');
        if (total > 0) el.progressMeta.text('Done — ' + processed + '/' + total + ' lines');
        showInfo('Completed! Live: ' + el.liveCount.text() + ' | Die: ' + el.dieCount.text() + ' | Unknown: ' + el.unknownCount.text(), 'success');
      }

      function resetAll() {
        el.liveCount.text('0'); el.dieCount.text('0'); el.unknownCount.text('0');
        el.successPanel.empty(); el.dangerPanel.empty(); el.warningPanel.empty();
        el.statTotal.text('0'); el.statProcessed.text('0');
        el.statLive.text('0'); el.statDie.text('0'); el.statUnknown.text('0');
        el.progressMeta.text('');
      }

      el.form.on('submit', function(e) {
        e.preventDefault();
        if (processing) return;
        const list = el.input.val().split('\n').map(s => s.trim()).filter(s => s);
        if (!list.length) { showInfo('Enter at least one card', 'error'); return; }
        resetAll();
        processing = true; shouldStop = false;
        el.startBtn.prop('disabled', true).html('Processing...');
        el.stopBtn.prop('disabled', false);
        el.input.prop('disabled', true);
        el.progressBar.css('width', '0%');
        el.progressMeta.text('Starting…');
        processAll(list);
      });

      el.stopBtn.on('click', function() { shouldStop = true; $(this).prop('disabled', true).html('Stopping...'); });
      el.input.on('keydown', function(e) {
        if (e.key === 'Enter' && e.ctrlKey && !processing) { e.preventDefault(); el.form.trigger('submit'); }
      });
    })();

    // ============================
    // TAB 2: BIN LOOKUP
    // ============================
    (function() {
      const $ = jQuery;
      const input = $('#bin-input'), lookupBtn = $('#bin-lookup-btn'), clearBtn = $('#bin-clear-btn'), result = $('#bin-result');

      input.on('input', function() { this.value = this.value.replace(/\D/g, '').slice(0, 6); });
      input.on('keydown', function(e) { if (e.key === 'Enter') lookupBtn.click(); });

      lookupBtn.on('click', function() {
        const bin = input.value.trim();
        if (bin.length < 6) { result.html('<div class="bin-error">Enter 6 digits</div>'); return; }
        result.html('<div class="bin-loading">Looking up BIN...</div>');
        $.post('api.php', { action: 'bin', bin: bin })
          .done(function(r) { result.html(r.html); })
          .fail(function() { result.html('<div class="bin-error">Lookup failed</div>'); });
      });

      clearBtn.on('click', function() { input.value = ''; result.empty(); });
    })();

    // ============================
    // TAB 3: GENERATOR
    // ============================
    (function() {
      const $ = jQuery;
      const count = $('#gen-count'), bin = $('#gen-bin'), type = $('#gen-type');
      const exp = $('#gen-exp'), cvv = $('#gen-cvv'), genBtn = $('#gen-btn');
      const output = $('#gen-output'), copyBtn = $('#gen-copy-btn');

      bin.on('input', function() { this.value = this.value.replace(/\D/g, '').slice(0, 6); });

      // Luhn checksum digit
      function luhnChecksum(partial) {
        const d = (partial + '0').split('').map(Number).reverse();
        let s = 0;
        for (let i = 0; i < d.length; i++) { let v = d[i]; if (i % 2 === 1) { v *= 2; if (v > 9) v -= 9; } s += v; }
        const check = (10 - (s % 10)) % 10;
        return partial + check;
      }

      function randomDigits(len) {
        let s = '';
        for (let i = 0; i < len; i++) s += Math.floor(Math.random() * 10);
        return s;
      }

      function randomExpiry() {
        const now = new Date(), cy = now.getFullYear(), cm = now.getMonth() + 1;
        const y = cy + Math.floor(Math.random() * 5) + 1;
        const m = String(Math.floor(Math.random() * 12) + 1).padStart(2, '0');
        return { month: m, year: String(y).slice(-2) };
      }

      function generateCards() {
        const c = parseInt(count.value, 10) || 10;
        const prefix = bin.value.trim();
        const cardType = type.value;
        const expMode = exp.value;
        const cvvMode = cvv.value;
        const lines = [];

        for (let i = 0; i < c; i++) {
          let p = prefix;
          if (!p) {
            if (cardType === 'visa') p = '4' + randomDigits(5);
            else if (cardType === 'mastercard') p = '5' + (Math.floor(Math.random() * 5) + 1) + randomDigits(4);
            else if (cardType === 'amex') p = '3' + (Math.random() > 0.5 ? '4' : '7') + randomDigits(4);
            else if (cardType === 'discover') p = '6011' + randomDigits(2);
            else {
              const types = ['4', '5', '3', '6'];
              p = types[Math.floor(Math.random() * types.length)] + randomDigits(5);
            }
          }
          const ct = detectCardType(p);
          const len = ct ? ct.lengths[Math.floor(Math.random() * ct.lengths.length)] : 16;
          const remaining = len - p.length - 1;
          if (remaining < 0) continue;
          const partial = p + randomDigits(remaining);
          const pan = luhnChecksum(partial);

          let month, year;
          if (expMode === 'future') {
            const now = new Date();
            year = String(now.getFullYear() + 1 + Math.floor(Math.random() * 5)).slice(-2);
            month = String(Math.floor(Math.random() * 12) + 1).padStart(2, '0');
          } else if (expMode === 'current') {
            const now = new Date();
            year = String(now.getFullYear()).slice(-2);
            month = String(now.getMonth() + 1).padStart(2, '0');
          } else {
            const e = randomExpiry();
            month = e.month; year = e.year;
          }

          let cvvStr = '';
          if (cvvMode === 'none') cvvStr = '';
          else if (cvvMode === 'gen') cvvStr = ct ? String(Math.floor(Math.random() * Math.pow(10, ct.cvvLength))).padStart(ct.cvvLength, '0') : String(Math.floor(Math.random() * 1000)).padStart(3, '0');
          else cvvStr = ct ? String(Math.floor(Math.random() * Math.pow(10, ct.cvvLength))).padStart(ct.cvvLength, '0') : String(Math.floor(Math.random() * 1000)).padStart(3, '0');

          lines.push(pan + '|' + month + '|' + year + '|' + cvvStr);
        }
        output.value = lines.join('\n');
      }

      genBtn.on('click', generateCards);
      copyBtn.on('click', function() {
        if (!output.value) return;
        navigator.clipboard.writeText(output.value).then(function() {
          copyBtn.text('Copied!'); setTimeout(() => copyBtn.text('Copy'), 1500);
        });
      });
    })();

    // ============================
    // TAB 4: CVV CHECKER
    // ============================
    (function() {
      const $ = jQuery;
      const input = $('#cvv-input'), cardType = $('#cvv-card-type'), checkBtn = $('#cvv-check-btn'), result = $('#cvv-result');
      const bulkInput = $('#cvv-bulk-input'), bulkBtn = $('#cvv-bulk-btn'), bulkResult = $('#cvv-bulk-result');

      input.on('input', function() { this.value = this.value.replace(/\D/g, '').slice(0, 4); });

      function checkCVV(cvv, type) {
        if (!/^\d+$/.test(cvv)) return { valid: false, reason: 'Digits only' };
        if (type === 'amex') {
          if (cvv.length !== 4) return { valid: false, reason: 'Amex requires 4 digits' };
          return { valid: true, reason: 'Valid Amex CVV (4 digits)' };
        }
        if (type && type !== 'other') {
          const expected = 3;
          if (cvv.length !== expected) return { valid: false, reason: 'Expected ' + expected + ' digits' };
          return { valid: true, reason: 'Valid ' + type + ' CVV (' + expected + ' digits)' };
        }
        if (cvv.length >= 3 && cvv.length <= 4) return { valid: true, reason: 'Valid CVV (' + cvv.length + ' digits)' };
        return { valid: false, reason: 'CVV must be 3-4 digits' };
      }

      checkBtn.on('click', function() {
        const cvv = input.value.trim();
        if (!cvv) { result.html('<div class="cvv-error">Enter a CVV</div>'); return; }
        const type = cardType.value || null;
        const res = checkCVV(cvv, type);
        const cls = res.valid ? 'cvv-valid' : 'cvv-invalid';
        result.html('<div class="' + cls + '"><strong>' + (res.valid ? 'Valid' : 'Invalid') + ':</strong> ' + res.reason + '</div>');
      });

      input.on('keydown', function(e) { if (e.key === 'Enter') checkBtn.click(); });

      bulkBtn.on('click', function() {
        const lines = bulkInput.value.split('\n').map(s => s.trim()).filter(s => s);
        if (!lines.length) { bulkResult.html('<div class="cvv-error">Enter CVVs</div>'); return; }
        let html = '<table class="cvv-table"><tr><th>CVV</th><th>Status</th><th>Note</th></tr>';
        for (const l of lines) {
          const res = checkCVV(l, null);
          const cls = res.valid ? 'cvv-valid' : 'cvv-invalid';
          html += '<tr class="' + cls + '"><td>' + l + '</td><td>' + (res.valid ? 'Valid' : 'Invalid') + '</td><td>' + res.reason + '</td></tr>';
        }
        html += '</table>';
        bulkResult.html(html);
      });
    })();

    // ============================
    // TAB 5: VCC CHECKER
    // ============================
    (function() {
      const $ = jQuery;
      const input = $('#vcc-input'), checkBtn = $('#vcc-check-btn'), clearBtn = $('#vcc-clear-btn'), result = $('#vcc-result');

      input.on('input', function() { this.value = this.value.replace(/\D/g, '').slice(0, 19); });
      input.on('keydown', function(e) { if (e.key === 'Enter') checkBtn.click(); });

      checkBtn.on('click', function() {
        const pan = input.value.trim();
        if (pan.length < 6) { result.html('<div class="bin-error">Enter at least 6 digits</div>'); return; }
        result.html('<div class="bin-loading">Checking VCC status...</div>');
        $.post('api.php', { action: 'vcc', pan: pan })
          .done(function(r) { result.html(r.html); })
          .fail(function() { result.html('<div class="bin-error">Check failed</div>'); });
      });

      clearBtn.on('click', function() { input.value = ''; result.empty(); });
    })();

    // ============================
    // TAB 6: COUNTRY FILTER
    // ============================
    (function() {
      const $ = jQuery;
      const cards = $('#filter-cards'), country = $('#filter-country'), btn = $('#filter-btn'), clearBtn = $('#filter-clear-btn'), result = $('#filter-result');

      btn.on('click', function() {
        const c = cards.value.trim();
        if (!c) { result.html('<div class="bin-error">Paste some cards first</div>'); return; }
        result.html('<div class="bin-loading">Filtering...</div>');
        $.post('api.php', { action: 'country_filter', cards: c, country: country.value.trim() })
          .done(function(r) { result.html(r.html); })
          .fail(function() { result.html('<div class="bin-error">Filter failed</div>'); });
      });

      clearBtn.on('click', function() { cards.value = ''; country.value = ''; result.empty(); });
    })();

    // ============================
    // TAB 7: BIN SCANNER
    // ============================
    (function() {
      const $ = jQuery;
      const start = $('#scanner-start'), end = $('#scanner-end'), btn = $('#scanner-btn'), clearBtn = $('#scanner-clear-btn'), result = $('#scanner-result');

      start.on('input', function() { this.value = this.value.replace(/\D/g, '').slice(0, 6); });
      end.on('input', function() { this.value = this.value.replace(/\D/g, '').slice(0, 6); });

      btn.on('click', function() {
        const s = start.value.trim(), e = end.value.trim();
        if (s.length < 6 || e.length < 6) { result.html('<div class="bin-error">Enter 6-digit start and end BINs</div>'); return; }
        result.html('<div class="bin-loading">Scanning BIN range...</div>');
        $.post('api.php', { action: 'bin_scanner', start: s, end: e })
          .done(function(r) { result.html(r.html); })
          .fail(function() { result.html('<div class="bin-error">Scan failed</div>'); });
      });

      clearBtn.on('click', function() { start.value = ''; end.value = ''; result.empty(); });
    })();

    // ============================
    // TAB 8: ADMIN
    // ============================
    (function() {
      const $ = jQuery;
      const loginDiv = $('#admin-login'), panelDiv = $('#admin-panel');
      const passInput = $('#admin-password'), loginBtn = $('#admin-login-btn'), logoutBtn = $('#admin-logout-btn');
      const statTotal = $('#admin-stat-total'), statLive = $('#admin-stat-live');
      const statDie = $('#admin-stat-die'), statUnknown = $('#admin-stat-unknown');
      const logDiv = $('#admin-log'), clearBtn = $('#admin-clear-log-btn');

      loginBtn.on('click', function() {
        const pass = passInput.value;
        if (!pass) return;
        $.post('api.php', { action: 'admin_login', password: pass })
          .done(function(r) {
            if (r.success) {
              loginDiv.hide(); panelDiv.show();
              loadAdminStats();
            } else {
              alert('Wrong password');
            }
          });
      });

      passInput.on('keydown', function(e) { if (e.key === 'Enter') loginBtn.click(); });

      logoutBtn.on('click', function() {
        $.post('api.php', { action: 'admin_logout' }).done(function() {
          panelDiv.hide(); loginDiv.show(); passInput.value = '';
        });
      });

      clearBtn.on('click', function() {
        if (!confirm('Clear all logs?')) return;
        $.post('api.php', { action: 'admin_clear' }).done(function() { loadAdminStats(); });
      });

      function loadAdminStats() {
        $.post('api.php', { action: 'admin_stats' })
          .done(function(r) {
            statTotal.text(r.total || 0);
            statLive.text(r.live || 0);
            statDie.text(r.die || 0);
            statUnknown.text(r.unknown || 0);
            if (r.log && r.log.length) {
              logDiv.html(r.log.map(function(e) {
                return '<div class="admin-log-entry"><span class="admin-log-status admin-log-status--' + e.status + '">' + e.status + '</span> ' + e.card + ' <small>' + e.time + '</small></div>';
              }).join(''));
            } else {
              logDiv.html('<div class="admin-log-empty">No entries yet</div>');
            }
          });
      }

      // Auto-login if session exists
      $.post('api.php', { action: 'admin_stats' })
        .done(function(r) {
          if (r.logged_in) { loginDiv.hide(); panelDiv.show(); loadAdminStats(); }
        });
    })();
  });
  </script>
</body>
</html>