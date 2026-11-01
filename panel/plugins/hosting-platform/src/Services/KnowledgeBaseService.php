<?php

namespace BrandedHosting\Platform\Services;

class KnowledgeBaseService
{
    /**
     * Retrieve all knowledge base categories with articles.
     */
    public function getCategories(): array
    {
        return [
            'console' => [
                'name' => 'Console & Power Controls',
                'icon' => 'ti ti-terminal-2',
                'description' => 'Real-time terminal, container states, Start/Stop/Restart/Kill, and live performance telemetry.',
                'articles' => [
                    'power-actions' => [
                        'title' => 'Start, Stop, Restart & Kill: Mastering Server Power Actions',
                        'summary' => 'Comprehensive guide to every power button in the STX console and when to use graceful stop versus forceful kill.',
                        'read_time' => '4 min read',
                        'content' => $this->getArticleContent('power-actions'),
                    ],
                    'terminal-commands' => [
                        'title' => 'Console Command Line: Running Admin & In-Game Commands',
                        'summary' => 'How to send commands directly to stdin, configure OP permissions, whitelists, and review live server logs.',
                        'read_time' => '3 min read',
                        'content' => $this->getArticleContent('terminal-commands'),
                    ],
                    'vitals-telemetry' => [
                        'title' => 'Understanding CPU, RAM, Disk & Network Live Telemetry',
                        'summary' => 'Monitoring container utilization, avoiding out-of-memory (OOM) crashes, and understanding resource graphs.',
                        'read_time' => '3 min read',
                        'content' => $this->getArticleContent('vitals-telemetry'),
                    ],
                ],
            ],
            'files' => [
                'name' => 'File Manager & SFTP Remote Access',
                'icon' => 'ti ti-folders',
                'description' => 'Browser file editor, drag-and-drop uploads, ZIP archives, chmod permissions, and FileZilla/WinSCP SFTP.',
                'articles' => [
                    'file-manager-buttons' => [
                        'title' => 'File Manager: Every Button, Action & Mass Selection Tool',
                        'summary' => 'Detailed walkthrough of New File, New Folder, Upload, Download, Rename, Duplicate, Archive, and Permissions.',
                        'read_time' => '5 min read',
                        'content' => $this->getArticleContent('file-manager-buttons'),
                    ],
                    'sftp-filezilla' => [
                        'title' => 'Connecting to SFTP via FileZilla, WinSCP & Cyberduck',
                        'summary' => 'Direct port 2022 access instructions for ultra-fast remote file transfers, mod installation, and world uploads.',
                        'read_time' => '4 min read',
                        'content' => $this->getArticleContent('sftp-filezilla'),
                    ],
                ],
            ],
            'databases' => [
                'name' => 'MariaDB & MySQL Databases',
                'icon' => 'ti ti-database',
                'description' => 'Dedicated database credentials, connecting plugins, resetting passwords, and remote client management.',
                'articles' => [
                    'databases-management' => [
                        'title' => 'Managing Databases: New Database, Reset Password & Credentials',
                        'summary' => 'How to locate host, port 3306, username, database name, and rotate passwords with zero downtime.',
                        'read_time' => '4 min read',
                        'content' => $this->getArticleContent('databases-management'),
                    ],
                    'plugin-mysql-config' => [
                        'title' => 'Connecting Plugins to MariaDB (LuckPerms, CoreProtect, FiveM)',
                        'summary' => 'Configuration copy-paste examples for Minecraft plugins, FiveM oxmysql, and web applications.',
                        'read_time' => '5 min read',
                        'content' => $this->getArticleContent('plugin-mysql-config'),
                    ],
                ],
            ],
            'schedules' => [
                'name' => 'Schedules & Cron Automation',
                'icon' => 'ti ti-calendar-time',
                'description' => 'Automated server restarts, scheduled backups, broadcast notices, and custom cron expressions.',
                'articles' => [
                    'schedules-builder' => [
                        'title' => 'Creating Automated Schedules: Cron Presets & Chained Tasks',
                        'summary' => 'Step-by-step guide to configuring Minute, Hour, Day, Month, and linking Send Command and Backup tasks.',
                        'read_time' => '6 min read',
                        'content' => $this->getArticleContent('schedules-builder'),
                    ],
                ],
            ],
            'backups' => [
                'name' => 'Backups & Cloud Vault Recovery',
                'icon' => 'ti ti-archive',
                'description' => 'Manual snapshots, automated daily backups, locking critical restores, and instant one-click rollbacks.',
                'articles' => [
                    'backups-recovery' => [
                        'title' => 'Backups: Create Snapshot, Lock, Download & Restore with One Click',
                        'summary' => 'How to protect your game worlds and configs before updates, understand lock status, and restore safely.',
                        'read_time' => '4 min read',
                        'content' => $this->getArticleContent('backups-recovery'),
                    ],
                ],
            ],
            'network' => [
                'name' => 'Network Allocations & Extra Ports',
                'icon' => 'ti ti-network',
                'description' => 'Primary game connection, requesting extra ports for Dynmap/VoiceChat/Geyser, aliases, and port notes.',
                'articles' => [
                    'network-allocations' => [
                        'title' => 'Port Allocations: Primary IP, Secondary Ports, Voice Chat & Dynmap',
                        'summary' => 'Configuring primary endpoints, port aliases (play.shanutechx.com), and allocating ports for plugins.',
                        'read_time' => '4 min read',
                        'content' => $this->getArticleContent('network-allocations'),
                    ],
                ],
            ],
            'startup' => [
                'name' => 'Startup Parameters, Java & Docker',
                'icon' => 'ti ti-adjustments-horizontal',
                'description' => 'Selecting Java 17/21, changing JAR files, tuning Aikar flags, and adjusting environment variables.',
                'articles' => [
                    'startup-variables' => [
                        'title' => 'Startup Parameters: Java Version, JAR Files & JVM Memory Optimization',
                        'summary' => 'How to switch between Java versions, set custom startup commands, and optimize server performance.',
                        'read_time' => '5 min read',
                        'content' => $this->getArticleContent('startup-variables'),
                    ],
                ],
            ],
            'users' => [
                'name' => 'Subusers & Team Permissions',
                'icon' => 'ti ti-users',
                'description' => 'Delegating server access to staff, administrators, and builders with granular permission checklists.',
                'articles' => [
                    'subusers-access' => [
                        'title' => 'Subusers: Inviting Staff, Granular Permission Checklists & 2FA',
                        'summary' => 'Safely granting teammates access to console, file editor, or power controls without sharing master credentials.',
                        'read_time' => '4 min read',
                        'content' => $this->getArticleContent('subusers-access'),
                    ],
                ],
            ],
            'settings' => [
                'name' => 'Settings & Reinstallation',
                'icon' => 'ti ti-settings',
                'description' => 'Server renaming, description editing, and the Reinstall Server button.',
                'articles' => [
                    'settings-reinstall' => [
                        'title' => 'Server Settings: Renaming & Using the Reinstall Server Action',
                        'summary' => 'How to customize server display name and how to safely use the Reinstall Server feature to re-download clean game eggs.',
                        'read_time' => '3 min read',
                        'content' => $this->getArticleContent('settings-reinstall'),
                    ],
                ],
            ],
            'billing' => [
                'name' => 'Billing, Invoices & Orders',
                'icon' => 'ti ti-receipt-2',
                'description' => 'Tracking order status, downloading official PDF invoices, and submitting renewal payment slips.',
                'articles' => [
                    'billing-invoices' => [
                        'title' => 'Orders & Invoices: Payment Tracking, PDF Receipts & Bank Slips',
                        'summary' => 'Step-by-step walkthrough of viewing invoices, printing official receipts, and uploading bank transfer slips.',
                        'read_time' => '3 min read',
                        'content' => $this->getArticleContent('billing-invoices'),
                    ],
                ],
            ],
        ];
    }

    /**
     * Search articles by keyword.
     */
    public function search(string $query): array
    {
        $query = strtolower(trim($query));
        if (empty($query)) {
            return [];
        }

        $results = [];
        foreach ($this->getCategories() as $catKey => $category) {
            foreach ($category['articles'] as $artKey => $article) {
                if (
                    str_contains(strtolower($article['title']), $query) ||
                    str_contains(strtolower($article['summary']), $query) ||
                    str_contains(strtolower($category['name']), $query)
                ) {
                    $results[] = [
                        'category_slug' => $catKey,
                        'category_name' => $category['name'],
                        'category_icon' => $category['icon'],
                        'article_slug' => $artKey,
                        'title' => $article['title'],
                        'summary' => $article['summary'],
                        'read_time' => $article['read_time'],
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Get specific article by category and slug.
     */
    public function getArticle(string $categorySlug, string $articleSlug): ?array
    {
        $categories = $this->getCategories();
        if (!isset($categories[$categorySlug]['articles'][$articleSlug])) {
            return null;
        }

        $article = $categories[$categorySlug]['articles'][$articleSlug];
        $article['category_slug'] = $categorySlug;
        $article['category_name'] = $categories[$categorySlug]['name'];
        $article['category_icon'] = $categories[$categorySlug]['icon'];
        $article['slug'] = $articleSlug;

        return $article;
    }

    /**
     * Return detailed markdown/HTML content for an article.
     */
    protected function getArticleContent(string $slug): string
    {
        return match ($slug) {
            'power-actions' => <<<'HTML'
<h3>Console Power Controls Overview</h3>
<p>When you open your server in the STX Enterprises Control Panel, the top-right corner of the <strong>Console</strong> tab features four primary action buttons. Understanding when and how to use each button ensures your game data, world chunks, and databases are saved without corruption.</p>

<div class="space-y-4 my-6">
    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
        <h4 class="text-emerald-400 font-bold flex items-center gap-2">
            <span class="px-2 py-0.5 rounded bg-emerald-500 text-black text-xs uppercase font-black">START</span>
            Boot Server Container
        </h4>
        <p class="text-sm text-gray-300 mt-1">
            Initializes the isolated Docker container, executes startup variable validation, binds the assigned port allocation, and starts your game or application server daemon. Use this button whenever your server is in the <code>Offline</code> state.
        </p>
    </div>

    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20">
        <h4 class="text-red-400 font-bold flex items-center gap-2">
            <span class="px-2 py-0.5 rounded bg-red-500 text-white text-xs uppercase font-black">STOP</span>
            Graceful Shutdown Signal
        </h4>
        <p class="text-sm text-gray-300 mt-1">
            Sends the clean termination command configured in your Egg (e.g., <code>stop</code> in Minecraft, <code>quit</code>, or <code>SIGINT</code>). This instructs the server software to save player inventories, flush world chunks to disk, cleanly close SQL connections, and safely exit. <strong>Always use this button first</strong> when turning off your server.
        </p>
    </div>

    <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
        <h4 class="text-amber-400 font-bold flex items-center gap-2">
            <span class="px-2 py-0.5 rounded bg-amber-500 text-black text-xs uppercase font-black">RESTART</span>
            Automated Stop & Start Cycle
        </h4>
        <p class="text-sm text-gray-300 mt-1">
            Issues a graceful stop command, waits for container exit confirmation, and immediately triggers a fresh boot. Perfect for applying configuration changes, updating plugin JAR files, or routine daily reboots.
        </p>
    </div>

    <div class="p-4 rounded-xl bg-gray-500/10 border border-gray-500/20">
        <h4 class="text-gray-300 font-bold flex items-center gap-2">
            <span class="px-2 py-0.5 rounded bg-gray-700 text-red-400 text-xs uppercase font-black">KILL</span>
            Emergency Forceful Termination (SIGKILL)
        </h4>
        <p class="text-sm text-gray-300 mt-1">
            Sends an immediate kernel <code>SIGKILL</code> directly to the process. <em>Warning:</em> The server process does not have time to save unsaved memory caches to disk. Only use this button if your server has completely crashed, frozen in a deadlock, or failed to stop after 60 seconds.
        </p>
    </div>
</div>

<h3>Pro Tips & Best Practices</h3>
<ul class="list-disc pl-5 space-y-2 text-sm text-gray-300">
    <li><strong>Chunk Saving:</strong> In Minecraft servers, run <code>save-all</code> in the console command terminal prior to stopping if you have made extensive world modifications.</li>
    <li><strong>Automated Restarts:</strong> Do not restart manually every day; visit the <strong>Schedules</strong> tab to configure an automated daily reboot at low-traffic hours.</li>
</ul>
HTML,

            'terminal-commands' => <<<'HTML'
<h3>Using the Live Console Terminal</h3>
<p>The interactive console provides a direct WebSocket stream connected to your server's standard input (stdin) and output (stdout/stderr). You can view boot logs, chat history, stack traces, and execute management commands without needing to join the game.</p>

<h3>Command Line Interface</h3>
<p>At the bottom of the console is a text input field marked with a prompt symbol (<code>&gt;</code>). You can type any command supported by your server engine:</p>

<div class="p-4 rounded-xl bg-black/40 border border-white/10 my-4 font-mono text-sm space-y-2">
    <div class="text-gray-400"># Minecraft Operator (Admin) Assignment</div>
    <div class="text-emerald-400">op YourPlayerName</div>
    <div class="text-gray-400"># Enable Server Whitelist</div>
    <div class="text-emerald-400">whitelist on</div>
    <div class="text-emerald-400">whitelist add YourPlayerName</div>
    <div class="text-gray-400"># Broadcast Server Announcement</div>
    <div class="text-emerald-400">say Server scheduled maintenance in 10 minutes!</div>
</div>

<div class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 my-4">
    <h4 class="text-indigo-400 font-bold text-sm">Do Not Include the Leading Slash (/) in Console</h4>
    <p class="text-xs text-gray-300 mt-1">
        In-game chat requires a forward slash (e.g., <code>/op Player</code>) to distinguish commands from normal chat messages. In the web console, you are interacting directly with the server core, so omit the slash (e.g., <code>op Player</code>).
    </p>
</div>
HTML,

            'vitals-telemetry' => <<<'HTML'
<h3>Understanding Live Server Telemetry</h3>
<p>The console dashboard displays real-time telemetry gauges powered by Linux cgroups and container statistics:</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
    <div class="p-4 rounded-xl bg-white/5 border border-white/10">
        <h4 class="font-bold text-white flex items-center gap-2">
            <i class="ti ti-cpu text-indigo-400"></i> CPU Utilization (%)
        </h4>
        <p class="text-xs text-gray-300 mt-1">
            Represents thread execution load. 100% equals 1 full dedicated CPU core. If your plan has 200% CPU, your server can use up to 2 full physical/logical cores simultaneously.
        </p>
    </div>
    <div class="p-4 rounded-xl bg-white/5 border border-white/10">
        <h4 class="font-bold text-white flex items-center gap-2">
            <i class="ti ti-device-sd-card text-emerald-400"></i> Memory (RAM)
        </h4>
        <p class="text-xs text-gray-300 mt-1">
            Live memory resident set size. When memory approaches 98-100%, Java garbage collection may spike CPU. If memory exceeds 100%, the Linux kernel OOM killer will stop the process.
        </p>
    </div>
    <div class="p-4 rounded-xl bg-white/5 border border-white/10">
        <h4 class="font-bold text-white flex items-center gap-2">
            <i class="ti ti-database text-purple-400"></i> NVMe Disk Space
        </h4>
        <p class="text-xs text-gray-300 mt-1">
            Current storage consumed by worlds, plugins, logs, and mods. STX servers utilize ultra-fast Gen4 NVMe enterprise arrays for instantaneous chunk loading.
        </p>
    </div>
    <div class="p-4 rounded-xl bg-white/5 border border-white/10">
        <h4 class="font-bold text-white flex items-center gap-2">
            <i class="ti ti-arrows-diff text-cyan-400"></i> Network Inbound / Outbound
        </h4>
        <p class="text-xs text-gray-300 mt-1">
            Current bandwidth throughput. Shows packets transmitted to connected players and external plugin APIs.
        </p>
    </div>
</div>
HTML,

            'file-manager-buttons' => <<<'HTML'
<h3>File Manager Controls & In-Browser Editor</h3>
<p>The <strong>Files</strong> tab allows full control over your server directory tree. You can upload custom configurations, edit properties files, and organize data without third-party software.</p>

<h3>Toolbar Action Buttons</h3>
<ul class="space-y-3 my-4 text-sm text-gray-300">
    <li class="flex items-start gap-2">
        <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-xs font-bold font-mono">New File</span>
        <span>Opens an in-browser code editor with syntax highlighting for YAML, JSON, Properties, TOML, and Lua. Name your file in the top input and press <code>Save Content</code>.</span>
    </li>
    <li class="flex items-start gap-2">
        <span class="px-2 py-0.5 rounded bg-gray-700 text-white text-xs font-bold font-mono">New Folder</span>
        <span>Creates a directory within the currently opened path (e.g., <code>plugins/MyPlugin/</code>).</span>
    </li>
    <li class="flex items-start gap-2">
        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-xs font-bold font-mono">Upload</span>
        <span>Opens a file picker dialog or accepts drag-and-drop file drops directly onto the file list.</span>
    </li>
</ul>

<h3>Contextual Row Actions (Three Dots Menu)</h3>
<div class="overflow-x-auto my-4">
    <table class="w-full text-left text-xs border border-white/10 rounded-xl overflow-hidden">
        <thead class="bg-white/5 text-gray-400 uppercase">
            <tr>
                <th class="p-3">Action</th>
                <th class="p-3">Description</th>
                <th class="p-3">Shortcut / Notes</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5 text-gray-300">
            <tr>
                <td class="p-3 font-semibold text-white">Edit</td>
                <td class="p-3">Opens text files in the in-browser syntax editor.</td>
                <td class="p-3 font-mono">Ctrl + S to Save</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold text-white">Download</td>
                <td class="p-3">Downloads the file directly to your local computer.</td>
                <td class="p-3">Instant download</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold text-white">Archive</td>
                <td class="p-3">Compresses selected files or folders into a <code>.tar.gz</code> or <code>.zip</code> archive.</td>
                <td class="p-3">High-speed compression</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold text-white">Unarchive</td>
                <td class="p-3">Decompresses ZIP, Tar, or GZ archives directly into the current directory.</td>
                <td class="p-3">Great for modpacks</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold text-white">Permissions (chmod)</td>
                <td class="p-3">Sets Unix permissions (e.g. <code>0755</code> for executables, <code>0644</code> for configs).</td>
                <td class="p-3">Default is 0644</td>
            </tr>
        </tbody>
    </table>
</div>
HTML,

            'sftp-filezilla' => <<<'HTML'
<h3>SFTP Remote File Management</h3>
<p>For uploading large worlds, custom modpacks (over 100MB), or bulk folders, <strong>SFTP (SSH File Transfer Protocol)</strong> is the fastest and most reliable method.</p>

<h3>Connection Parameters</h3>
<p>Navigate to <strong>Settings</strong> in your server control panel to locate your unique connection details:</p>

<div class="p-4 rounded-xl bg-black/40 border border-white/10 my-4 text-xs font-mono space-y-2">
    <div><span class="text-gray-400">Server Address / Host:</span> <span class="text-emerald-400">sftp.shanutechx.com</span> (or your node FQDN)</div>
    <div><span class="text-gray-400">Port:</span> <span class="text-emerald-400">2022</span> (Standard SFTP port on STX)</div>
    <div><span class="text-gray-400">Username:</span> <span class="text-emerald-400">your_username.0df35b2a</span> (Your account username dot server short UUID)</div>
    <div><span class="text-gray-400">Password:</span> <span class="text-emerald-400">Your STX Panel Login Password</span></div>
</div>

<h3>Step-by-Step with FileZilla</h3>
<ol class="list-decimal pl-5 space-y-2 text-sm text-gray-300">
    <li>Open FileZilla and open the <strong>Site Manager</strong> (Ctrl + S).</li>
    <li>Click <strong>New Site</strong> and name it <em>STX Game Server</em>.</li>
    <li>Set Protocol to <strong>SFTP - SSH File Transfer Protocol</strong>.</li>
    <li>Enter the Host, Port (<code>2022</code>), Username, and Password.</li>
    <li>Click <strong>Connect</strong>. If prompted with a host key verification warning, verify and check <em>Always trust this host</em>.</li>
</ol>
HTML,

            'databases-management' => <<<'HTML'
<h3>Automated MariaDB & MySQL Databases</h3>
<p>STX Enterprises automatically seeds and provisions a dedicated MariaDB database for every server with a database allocation. Databases allow your plugins, player statistic loggers, and web systems to store persistent data with sub-millisecond query latency.</p>

<h3>Database Credentials & Actions</h3>
<p>In the <strong>Databases</strong> tab, your active databases are listed with the following controls:</p>

<div class="space-y-3 my-4 text-sm text-gray-300">
    <div class="p-3 rounded-lg bg-white/5 border border-white/10 flex items-center justify-between">
        <div>
            <span class="font-bold text-white">View / Reveal Password</span>
            <p class="text-xs text-gray-400">Click to reveal the 24-character generated password and copy JDBC connection strings.</p>
        </div>
        <span class="px-2 py-1 rounded bg-indigo-600 text-white text-xs font-bold">View</span>
    </div>

    <div class="p-3 rounded-lg bg-white/5 border border-white/10 flex items-center justify-between">
        <div>
            <span class="font-bold text-white">Reset Password</span>
            <p class="text-xs text-gray-400">Generates a fresh cryptographically secure password immediately updating user privileges in MariaDB.</p>
        </div>
        <span class="px-2 py-1 rounded bg-amber-600 text-black text-xs font-bold">Rotate</span>
    </div>

    <div class="p-3 rounded-lg bg-white/5 border border-white/10 flex items-center justify-between">
        <div>
            <span class="font-bold text-white">Delete Database</span>
            <p class="text-xs text-gray-400">Drops the database schema and revokes user credentials. <em>Warning:</em> Data is permanently erased.</p>
        </div>
        <span class="px-2 py-1 rounded bg-red-600 text-white text-xs font-bold">Delete</span>
    </div>
</div>
HTML,

            'plugin-mysql-config' => <<<'HTML'
<h3>Configuring Plugins to Use MariaDB</h3>
<p>Here are copy-paste examples for connecting popular server plugins to your STX database:</p>

<h3>1. LuckPerms (Minecraft Permissions)</h3>
<div class="p-4 rounded-xl bg-black/40 border border-white/10 my-3 font-mono text-xs text-gray-300 space-y-1">
    <div>storage-method: "MariaDB"</div>
    <div>data:</div>
    <div>  address: "database:3306" <span class="text-gray-500"># or node IP if external</span></div>
    <div>  database: "s2_primary"</div>
    <div>  username: "u2_niomfx"</div>
    <div>  password: "YOUR_COPIED_PASSWORD"</div>
    <div>  pool-settings:</div>
    <div>    maximum-pool-size: 10</div>
</div>

<h3>2. FiveM (oxmysql / MySQL-Async)</h3>
<div class="p-4 rounded-xl bg-black/40 border border-white/10 my-3 font-mono text-xs text-gray-300 space-y-1">
    <div>set mysql_connection_string "mysql://u2_niomfx:YOUR_PASSWORD@database:3306/s2_primary?charset=utf8mb4"</div>
</div>
HTML,

            'schedules-builder' => <<<'HTML'
<h3>Automated Schedules & Cron Automation</h3>
<p>Schedules allow your server to execute repetitive maintenance tasks automatically, such as daily restarts, backup creation, or warning broadcasts.</p>

<h3>How to Create a Schedule</h3>
<ol class="list-decimal pl-5 space-y-2 text-sm text-gray-300 my-4">
    <li>Click <strong>New Schedule</strong> in the top-right corner of the Schedules tab.</li>
    <li>Enter a descriptive name (e.g., <code>Daily 4:00 AM Restart</code>).</li>
    <li>Configure the Cron timing:
        <ul class="list-disc pl-5 mt-1 space-y-1 text-xs text-gray-400">
            <li><strong>Minute:</strong> <code>0</code> (at the start of the hour)</li>
            <li><strong>Hour:</strong> <code>4</code> (at 4:00 AM)</li>
            <li><strong>Day of Month / Month / Day of Week:</strong> <code>*</code> (every day)</li>
        </ul>
    </li>
    <li>Toggle <strong>Only When Online</strong> depending on whether the task requires the container to be running.</li>
    <li>Click <strong>Create Schedule</strong>.</li>
</ol>

<h3>Adding Tasks to a Schedule</h3>
<p class="text-sm text-gray-300">Once created, open the schedule to chain sequential tasks:</p>
<ul class="list-disc pl-5 space-y-2 text-xs text-gray-300 my-3">
    <li><strong>Send Command:</strong> <code>say Server restarting in 60 seconds!</code> (Offset: 0s)</li>
    <li><strong>Send Command:</strong> <code>save-all</code> (Offset: 30s)</li>
    <li><strong>Power Action:</strong> <code>Restart the server</code> (Offset: 60s)</li>
</ul>
HTML,

            'backups-recovery' => <<<'HTML'
<h3>Backups & Disaster Recovery</h3>
<p>STX Enterprises automatically seeds an automated daily backup schedule for your server. In addition, you can create manual snapshots whenever you are updating mods, configs, or server core software.</p>

<h3>Creating a Manual Snapshot</h3>
<ol class="list-decimal pl-5 space-y-2 text-sm text-gray-300 my-4">
    <li>Navigate to the <strong>Backups</strong> tab.</li>
    <li>Click the <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-xs font-bold">Create Backup</span> button.</li>
    <li>Provide a name (e.g. <code>Pre-1.21-Update</code>).</li>
    <li>(Optional) In <strong>Ignored Files</strong>, you can specify files or directories to omit (e.g., <code>.cache\nlogs</code>) to save backup quota.</li>
    <li>Click <strong>Start Backup</strong>. The daemon will package and compress your server into the STX Cloud Vault.</li>
</ol>

<h3>Locking Critical Backups</h3>
<p class="text-sm text-gray-300">
    When your server reaches its backup slot limit, the oldest un-locked backup is automatically pruned to make room for new automated daily backups. To permanently protect a milestone backup (e.g., after building a new spawn), click the <strong>Lock</strong> icon. Locked backups are immune to automated deletion.
</p>

<h3>Restoring a Backup</h3>
<p class="text-sm text-gray-300">
    To roll back your server to a previous restore point, click the three-dots menu on the desired backup row and choose <strong>Restore</strong>. You will be prompted to choose whether to format the disk before unpacking or overwrite existing files.
</p>
HTML,

            'network-allocations' => <<<'HTML'
<h3>Network Allocations & Extra Ports</h3>
<p>Every server is assigned a primary network allocation consisting of a public IP (or Anycast FQDN) and a port number (e.g. <code>play.shanutechx.com:25565</code>).</p>

<h3>Requesting Additional Ports</h3>
<p class="text-sm text-gray-300">
    Certain server setups require extra open ports for secondary services:
</p>
<ul class="list-disc pl-5 space-y-1 text-xs text-gray-300 my-3">
    <li><strong>Simple Voice Chat:</strong> Requires an extra UDP port.</li>
    <li><strong>Dynmap / BlueMap:</strong> Requires an extra HTTP web port (e.g. 8123).</li>
    <li><strong>GeyserMC:</strong> Requires a UDP port (e.g. 19132) for Bedrock player crossplay.</li>
    <li><strong>Votifier:</strong> Requires a query port for voting rewards.</li>
</ul>
<p class="text-sm text-gray-300">
    In the <strong>Network</strong> tab, click <strong>Create Allocation</strong> to assign an unused port to your container, or open a ticket if your plan limit requires an increase.
</p>
HTML,

            'startup-variables' => <<<'HTML'
<h3>Startup Parameters & Java Selector</h3>
<p>The <strong>Startup</strong> tab allows you to configure JVM runtime parameters, choose specific Docker container images, and customize environment parameters.</p>

<h3>Switching Java Versions</h3>
<p class="text-sm text-gray-300">
    Minecraft versions require specific Java runtimes:
</p>
<ul class="list-disc pl-5 space-y-1 text-xs text-gray-300 my-3">
    <li><strong>Java 21:</strong> Recommended for Minecraft 1.20.5+ and 1.21+.</li>
    <li><strong>Java 17:</strong> Recommended for Minecraft 1.18 through 1.20.4.</li>
    <li><strong>Java 8 / 11:</strong> Required for legacy Minecraft 1.12.2 and older modpacks.</li>
</ul>
<p class="text-sm text-gray-300">
    Select the appropriate container image from the <strong>Docker Image</strong> dropdown, click save, and restart your server.
</p>
HTML,

            'subusers-access' => <<<'HTML'
<h3>Delegating Access with Subusers</h3>
<p>If you run a server with staff, co-owners, or developers, never share your account login credentials. Instead, invite them as a <strong>Subuser</strong> with strictly defined permissions.</p>

<h3>How to Invite a Subuser</h3>
<ol class="list-decimal pl-5 space-y-2 text-sm text-gray-300 my-4">
    <li>Navigate to the <strong>Users</strong> tab in your server control panel.</li>
    <li>Click <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-xs font-bold">New User</span>.</li>
    <li>Enter the teammate's registered email address.</li>
    <li>Check the specific permissions you want to grant:
        <ul class="list-disc pl-5 mt-1 space-y-1 text-xs text-gray-400">
            <li><strong>Control:</strong> Console read, Start, Stop, Restart.</li>
            <li><strong>Files:</strong> File read, edit, upload, download, delete.</li>
            <li><strong>Backups:</strong> Create and download backups.</li>
            <li><strong>Databases:</strong> View database credentials.</li>
        </ul>
    </li>
    <li>Click <strong>Invite User</strong>. They will immediately see the server appear in their dashboard under <em>Others' Servers</em>.</li>
</ol>
HTML,

            'settings-reinstall' => <<<'HTML'
<h3>Server Settings & Reinstallation</h3>
<p>The <strong>Settings</strong> tab holds high-level container management controls:</p>

<h3>Renaming Your Server</h3>
<p class="text-sm text-gray-300">
    You can customize the friendly name and description of your server at any time. This updates how the server appears in your dashboard list and navigation bar.
</p>

<h3>Using the Reinstall Server Action</h3>
<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 my-4">
    <h4 class="text-red-400 font-bold text-sm">Important Warning: Reinstalling Wipes Files</h4>
    <p class="text-xs text-gray-300 mt-1">
        Clicking <strong>Reinstall Server</strong> executes the egg's installation script from scratch. It will download a fresh server core and may overwrite existing configuration files. <strong>Always create a backup in the Backups tab before triggering a reinstallation.</strong>
    </p>
</div>
HTML,

            'billing-invoices' => <<<'HTML'
<h3>Orders, Invoices & Payment Tracking</h3>
<p>All hosting orders placed on STX Enterprises generate immutable order snapshots and official printable invoices.</p>

<h3>Viewing Your Orders</h3>
<p class="text-sm text-gray-300">
    Visit <strong>My Billing & Orders</strong> (at <code>/customer-orders</code> or via the topbar menu). Every order displays:
</p>
<ul class="list-disc pl-5 space-y-1 text-xs text-gray-300 my-3">
    <li><strong>Order Number:</strong> Unique reference (e.g. <code>ORD-JEJGCNXD</code>).</li>
    <li><strong>Active Server Link:</strong> One-click navigation to your container console.</li>
    <li><strong>Invoice Button:</strong> Opens the official STX Enterprises invoice complete with VAT/tax breakdown and company seals.</li>
    <li><strong>Order Details & Payment:</strong> View instructions for Commercial Bank, Dialog eZ Cash (#111#), and HelaPay LANKAQR.</li>
</ul>
HTML,

            default => '<p>Documentation for this section is currently being updated by the STX NOC engineering team.</p>',
        };
    }
}
