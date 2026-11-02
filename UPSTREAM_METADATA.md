# Upstream Repository Metadata

This monorepo unifies the following sub-repositories into a single codebase:

## 1. Panel
- **Repository URL**: `https://github.com/ShanudhaTirosh/panel.git`
- **Upstream URL**: `https://github.com/pelican/panel.git`
- **Branch**: `main`
- **Last Commit**: `541f5cbce33ec6500f4d8a4df4a26d4a61ef5eb2`
- **Description**: STX Pelican Panel Control plane with custom Filament Admin Telemetry widgets, cluster monitoring, full-stack docker compose, and bundled plugins.

## 2. Plugins
- **Upstream URL**: `https://github.com/pelican/plugins.git`
- **Custom Plugin**: `hosting-platform` (Storefront, Sri Lanka payment rails, LKR dynamic billing, automated tax invoices, automated provisioning)
- **Branch**: `main`
- **Last Commit**: `fc781052d121d34e8f4af849fccd7d1c669cef69`
- **Status**: Fully consolidated into `panel/plugins/` as native codebase components.

## 3. Wings Daemon
- **Repository URL**: `https://github.com/pelican/wings.git`
- **Branch**: `main`
- **Last Commit**: `1b037224d408e76a69c4cfbe313321aac760ab57`
- **Description**: Pelican container runtime node daemon in Golang.

## 4. Status Page
- **Description**: Built into the `hosting-platform` plugin (`/status`, monitors and incidents managed in the admin panel). The separate Next.js `status-page/` app referenced in earlier versions is not part of this repository.
