/**
 * インメモリ スライディングウィンドウ レート制限
 * Vercel Serverless対応（インスタンス単位で動作）
 */

interface RateLimitEntry {
  timestamps: number[];
}

const stores = new Map<string, Map<string, RateLimitEntry>>();

function getStore(name: string): Map<string, RateLimitEntry> {
  if (!stores.has(name)) {
    stores.set(name, new Map());
  }
  return stores.get(name)!;
}

interface RateLimitConfig {
  /** このレート制限の識別名 */
  name: string;
  /** 時間ウィンドウ（ミリ秒） */
  windowMs: number;
  /** ウィンドウ内の最大リクエスト数 */
  maxRequests: number;
}

interface RateLimitResult {
  success: boolean;
  remaining: number;
  resetAt: number;
}

export function checkRateLimit(
  config: RateLimitConfig,
  key: string
): RateLimitResult {
  const store = getStore(config.name);
  const now = Date.now();
  const windowStart = now - config.windowMs;

  let entry = store.get(key);
  if (!entry) {
    entry = { timestamps: [] };
    store.set(key, entry);
  }

  // ウィンドウ外のタイムスタンプを除去
  entry.timestamps = entry.timestamps.filter((t) => t > windowStart);

  if (entry.timestamps.length >= config.maxRequests) {
    const oldestInWindow = entry.timestamps[0];
    return {
      success: false,
      remaining: 0,
      resetAt: oldestInWindow + config.windowMs,
    };
  }

  entry.timestamps.push(now);
  return {
    success: true,
    remaining: config.maxRequests - entry.timestamps.length,
    resetAt: now + config.windowMs,
  };
}

/** Vercelのx-forwarded-forヘッダーからクライアントIPを取得 */
export function getClientIp(req: Request): string {
  const forwarded = req.headers.get("x-forwarded-for");
  if (forwarded) {
    return forwarded.split(",")[0].trim();
  }
  return "unknown";
}

// メモリリーク防止: 60秒ごとに古いエントリをクリーンアップ
const CLEANUP_INTERVAL = 60_000;
const STALE_THRESHOLD = 5 * 60_000;

const timer = setInterval(() => {
  const now = Date.now();
  for (const [, store] of stores) {
    for (const [key, entry] of store) {
      const latest = entry.timestamps[entry.timestamps.length - 1] ?? 0;
      if (now - latest > STALE_THRESHOLD) {
        store.delete(key);
      }
    }
  }
}, CLEANUP_INTERVAL);

// Node.jsプロセスの終了をブロックしない
if (typeof timer === "object" && "unref" in timer) {
  timer.unref();
}
