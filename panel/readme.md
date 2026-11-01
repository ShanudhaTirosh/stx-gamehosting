# STX Enterprises - Control Plane & Web Engine

This is the primary control plane for STX Enterprises Cloud and Game Server Hosting.

## Configuration & Environment

- **Brand Name**: STX Enterprises
- **Base URL**: http://localhost
- **Database Engine**: MariaDB 10.11 (Container: `panel-database-1`)
- **Cache & Queue Daemon**: Redis Alpine (Container: `panel-cache-1`)
- **Web & Application Runtime**: Caddy & PHP 8.4-FPM (Container: `panel-panel-1`)

## Key Commands

```bash
# Clear view and route caches
docker compose -f compose-full-stack.yml exec panel php artisan optimize:clear

# Import official game server eggs
docker compose -f compose-full-stack.yml exec panel php plugins/hosting-platform/import_eggs_runner.php

# Monitor background queue worker
docker compose -f compose-full-stack.yml exec panel php artisan queue:monitor redis:default
```
