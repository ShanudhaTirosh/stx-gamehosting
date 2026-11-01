# STX Enterprises - Commercial Cloud & Game Server Hosting Platform

STX Enterprises is a unified, commercial-grade cloud and game server hosting platform built on containerized microservice architecture, native Sri Lankan payment rails, and enterprise node orchestration.

---

## 🌟 Platform Highlights

- **Branded Storefront & Customer Portal**: Custom modern Glassmorphism storefront with dynamic pricing in Sri Lankan Rupees (**LKR**).
- **Sri Lanka Payment Rails**: Direct Bank Transfer (Commercial Bank, Sampath, BOC, HNB, Peoples Bank), Dialog eZ Cash, and Helakuru HelaPay with slip uploads and instant SHA-256 deduplication.
- **Automated Invoice Generator**: Official printable and downloadable tax invoices (`#INV-STX-XXXXX`) with verification signatures and real-time payment stamps.
- **Automated Live Status Page (`/status`)**: Real-time cluster health monitoring, MariaDB/Redis latency metrics, Wings node statuses, and 90-day SLA history.
- **Game & Software Managers**:
  - **1-Click Version Changer**: Instant switching between Paper, Purpur, Fabric, Forge, NeoForge, and Vanilla Minecraft via the MCJars API.
  - **Modpack Manager**: Browse and install thousands of modpacks from CurseForge, Modrinth, FTB, and ATLauncher directly from the server panel.
  - **Minecraft Modrinth & Rust uMod**: In-panel plugin and mod installers for Minecraft and Rust.
  - **Live Player Query & Counter**: Real-time player tracking via `xpaw/php-minecraft-query`.
  - **MCLogs Crash Reporter**: 1-click crash log uploader to `mclo.gs` with sensitive credentials masked.
  - **Subdomain Routing**: Friendly DNS routing (e.g., `play.myserver.lk`) via Cloudflare DNS integration.
- **Enterprise Infrastructure**:
  - **STX Panel**: Central control plane running on PHP 8.4, Laravel, and Filament.
  - **Wings Container Daemon**: High-speed, isolated Docker game runtime nodes.
  - **SMTP Integration**: Transactional order confirmation, slip verification, and automated server credentials dispatch.

---

## 🚀 Quick Start & Service Endpoints

| Service / Interface | URL | Description |
| :--- | :--- | :--- |
| **Public Storefront** | [http://localhost/](http://localhost/) | Modern glassmorphism hosting portal & plans |
| **Cluster Status** | [http://localhost/status](http://localhost/status) | Real-time automated system telemetry |
| **Client Registration** | [http://localhost/server/register](http://localhost/server/register) | Customer self-registration |
| **Client Dashboard** | [http://localhost/customer-orders](http://localhost/customer-orders) | Customer services and order tracking |
| **Admin Portal** | [http://localhost/admin](http://localhost/admin) | Master administration & payment approval |

### Default Administrative Credentials
- **Email**: `admin@pelican.local`
- **Password**: `Password123!`

---

## 📦 Architecture Overview

```
                                [ Public Internet / Customer ]
                                              │
                      ┌───────────────────────┴───────────────────────┐
                      ▼                                               ▼
         [ STX Storefront & Billing ]                    [ STX Server Control Panel ]
         • Glassmorphism Storefront                      • Server Web Console
         • Sri Lanka Payment Rails (LKR)                 • 1-Click Version Changer
         • Slip Upload & Verification                    • CurseForge / Modrinth
         • Automated Tax Invoices                        • Live Player Tracker
                      │                                               │
                      └───────────────────────┬───────────────────────┘
                                              ▼
                                   [ STX Central Engine ]
                                  (MariaDB, Redis, Queue)
                                              │
                                              ▼
                                 [ ProvisionPaidServerJob ]
                                              │
                                              ▼
                             [ STX Wings Nodes (Docker Daemon) ]
                          (Minecraft, Hytale, FiveM, Bots, Apps)
```

---

## 🛡️ License

Copyright &copy; 2026 STX Enterprises Lanka (Pvt) Ltd. All rights reserved.  
Proprietary Commercial Software.
