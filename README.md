# 🚀 D-Tools Kit

> All-in-one toolkit: CC Checker, BIN Lookup, VCC Detector & Server Monitoring

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-3.6-0769AD?logo=jquery&logoColor=white)
![License](https://img.shields.io/badge/License-Unlicense-lightgrey)

---

## 📦 What's Inside

| Directory | Description |
|-----------|-------------|
| **`cc/`** | 🃏 CC Checker Pro — Web app for card validation, BIN lookup, card generation, CVV checker, VCC detection, country filter & BIN scanner |
| **`vcc/`** | 💳 VCC Checker Pro — Standalone Virtual Credit Card detector with full UI |
| **`tools/`** | 🛠️ Server monitoring scripts (HTTP check, disk alert, SSL expiry, ping host) |
| **`scripts/`** | 🔔 Telegram notification scripts (PowerShell + Bash) |

---

## 🃏 CC Checker Pro (`cc/`)

A modern PHP web app with **8 tools** in one:

- ✅ **CC Checker** — Validate cards (Luhn, expiry, CVV) with simulated scoring
- 🔍 **BIN Lookup** — Identify bank, country & card type from 6-digit BIN
- 🎲 **Generator** — Generate test card numbers with Luhn checksums
- 🔐 **CVV Checker** — Validate CVV format & length by card type
- 🟡 **VCC Checker** — Detect Virtual Credit Cards by BIN
- 🌍 **Country Filter** — Filter cards by issuing country
- 📡 **BIN Scanner** — Scan BIN ranges to find matching issuers
- 👑 **Admin Dashboard** — Session-based stats & check log

### 🎨 Themes

5 built-in themes: Glassmorphism, Cyberpunk, Minimal Clean, Dark Premium, 3D Glass

### 🏦 Supported Card Types

Visa · Mastercard · Amex · Discover · Diners Club · JCB · UnionPay · Maestro · Mir · Troy

### ⚙️ Configuration

Edit `cc/config.php`:
- `ENABLE_LUHN_CHECK` — Toggle Luhn algorithm
- `MIN/MAX_CARD_LENGTH` — Card number length limits
- `MIN_EXPIRY_YEAR` / `MAX_EXPIRY_YEARS_AHEAD` — Expiry bounds
- `CONTACT_EMAIL` — Footer contact

---

## 💳 VCC Checker Pro (`vcc/`)

Standalone virtual card detector with:
- 🟡 Amber/gold themed UI
- 🔎 BIN-based VCC detection (EntroPay, Neteller, Skrill, Revolut, Wise, Privacy.com, etc.)
- 🏷️ Issuer, country, type & confidence level display

---

## 🛠️ Server Monitoring Tools (`tools/`)

| Tool | Description |
|------|-------------|
| `http-check.sh` / `.ps1` | 🔗 HTTP endpoint health check → Telegram alert |
| `disk-alert.sh` / `.ps1` | 💾 Disk usage threshold monitor → Telegram alert |
| `ssl-expiry.sh` | 🔒 SSL certificate expiry check → Telegram alert |
| `ping-host.sh` | 📡 Ping-based host monitoring → Telegram alert |

All tools send notifications via **Telegram** using `scripts/telegram-notify.sh` or `.ps1`.

### 📋 Setup Monitoring

```bash
cp scripts/.env.example scripts/.env
# Edit .env with your bot token & chat ID
```

Then add to crontab:

```cron
*/5 * * * * /path/to/tools/http-check.sh https://example.com
0 */6 * * * /path/to/tools/ssl-expiry.sh example.com 30
```

---

## 🚀 Quick Start

### Web App (PHP)

```bash
cd cc
php -S localhost:8080
# Or use PowerShell:
./dev-server.ps1
```

Open http://localhost:8080 in your browser.

### Requirements

- **PHP 8.0+** with `json` extension
- **Bootstrap 5.3**, **jQuery 3.6** (loaded via CDN)
- For tools: `bash`, `curl`, `openssl`, `ping`, `python3`

---

## ⚠️ Disclaimer

> 🎓 **This is for educational purposes only.**
> The "Live/Die" scoring is a **local simulation** (hash-based, deterministic).
> It does **not** contact any real payment gateway or perform actual authorization.
> Do **not** use this tool for illegal activities.
> Do **not** sell this script — it's 100% free.

---

## 📄 License

[Unlicense](cc/LICENSE) — Public domain. Free to use, modify, and distribute.

---

## 📬 Contact

<werlist99@outlook.com>

---

<p align="center">Made with ❤️ by <a href="https://github.com/zougar99">zougar99</a></p>
