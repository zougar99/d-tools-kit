# 🛡️ d-tools-kit — All-in-one toolkit: CC Checker, BIN Lookup, VCC Detector & Server Monitoring — built for security professionals and developers

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/zougar99/d-tools-kit/blob/main/LICENSE)
[![GitHub stars](https://img.shields.io/github/stars/zougar99/d-tools-kit?style=social)](https://github.com/zougar99/d-tools-kit)
[![Platform](https://img.shields.io/badge/platform-Windows%20%7C%20Linux-blue)](https://github.com/zougar99/d-tools-kit)

> All-in-one toolkit: CC Checker, BIN Lookup, VCC Detector & Server Monitoring — built for security professionals and developers.

---

## 📖 Table of Contents
- [Features](#-features)
- [How It Works](#-how-it-works)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage Guide](#-usage-guide)
- [Screenshots](#-screenshots)
- [Roadmap](#-roadmap)
- [FAQ](#-faq)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features
- ✔ **CC Checker** — Validates credit card numbers (Luhn algorithm) with BIN matching
- ✔ **BIN Lookup** — Queries BIN/IIN databases for bank, country, card type info
- ✔ **VCC Detector** — Identifies virtual credit cards vs physical cards
- ✔ **Server Monitor** — Real-time uptime, ping, port monitoring dashboard
- ✔ **Batch Processing** — Process thousands of entries from CSV/JSON
- ✔ **Export** — CSV, JSON, PDF reports for all modules
- ✔ **Dark Theme** — Professional dark UI with customizable accent colors

---

## 🔮 How It Works

```
  Input ──► Processing Pipeline ──► Output
  ┌────────┐   ┌────────┐   ┌────────┐
  │ Data   │──►│ Engine │──►│ Result │
  │ Source │   │ Logic  │   │        │
  └────────┘   └────────┘   └────────┘
```

1. **Input** — Load data from file, API, or user input
2. **Process** — Core engine applies logic/analysis/transformation
3. **Output** — Results displayed in UI, saved to file, or sent via API

---

## 💻 Tech Stack

| Component | Technology |
|-----------|-----------|
| Language | Python 3.10+ |
| UI | CustomTkinter |
| Database | SQLite + BIN DB |
| Network | socket + requests |
| Platform | Windows / Linux |

---

## 🚀 Installation

```bash
git clone https://github.com/zougar99/d-tools-kit.git
cd d-tools-kit
pip install -r requirements.txt
```

---

## 📄 Configuration

Create a `config.yaml` or `.env` file in the project root:

```yaml
# Application settings
debug: false
port: 8080
theme: dark
language: en
```

---

## 🧰 Usage Guide

1. Launch: `python main.py`
2. Select a module (CC Checker / BIN Lookup / VCC / Monitor)
3. Input data manually or upload a file
4. View results in real-time
5. Export reports as needed

---

## 🖼 Screenshots

> *(Screenshots coming soon. PRs welcome!)*

---

## 🔄 Roadmap

- 🟢 Web dashboard
- 🟡 Mobile companion app
- ⚫ API access
- ⚫ Plugin system
- ⚫ Multi-language support

---

## ❓ FAQ

### Is this legal?
Yes — for authorized security testing and validation only.

### Does BIN Lookup require internet?
Yes — it queries external BIN databases.

---

## 🚧 Troubleshooting

| Problem | Solution |
|---------|----------|
| **App won't start** | Check Python version (3.10+); run `pip install -r requirements.txt` |
| **No output** | Check logs in `logs/` folder; enable debug mode in config |
| **Performance issues** | Close other applications; reduce batch size in config |
| **Dependency errors** | Create fresh venv: `python -m venv .venv && source .venv/bin/activate && pip install -r requirements.txt` |

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📐 License
Distributed under the **MIT License**. See [`LICENSE`](https://github.com/zougar99/d-tools-kit/blob/main/LICENSE) for more information.

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/zougar99">zougar99</a>
</p>
