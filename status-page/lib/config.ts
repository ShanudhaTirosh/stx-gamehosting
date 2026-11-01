export const intervalSeconds = 30;
export const timeoutMs = 15000;

export interface SiteConfig {
  id: string;
  name: string;
  url: string;
  intervalSeconds: number;
  timeoutMs: number;
}

export const sites: SiteConfig[] = [
  {
    id: "stx-info-portal",
    name: "STX Info & Docs Portal",
    url: "https://info.shanutechx.com",
    intervalSeconds,
    timeoutMs,
  },
  {
    id: "stx-cloud-gateway",
    name: "STX Anycast Cloud Gateway",
    url: "https://one.one.one.one",
    intervalSeconds,
    timeoutMs,
  },
  {
    id: "stx-auth-sso",
    name: "STX Global OAuth Identity Gateway",
    url: "https://accounts.google.com",
    intervalSeconds,
    timeoutMs,
  },
  {
    id: "stx-git-engine",
    name: "STX Git Deployments Engine",
    url: "https://github.com",
    intervalSeconds,
    timeoutMs,
  },
  {
    id: "stx-dns-telemetry",
    name: "STX Edge DNS Resolution",
    url: "https://dns.google/resolve?name=info.shanutechx.com",
    intervalSeconds,
    timeoutMs,
  },
];
