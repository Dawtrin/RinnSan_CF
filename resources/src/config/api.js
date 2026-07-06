/**
 * API base URL.
 * - Dev: set VITE_API_URL=http://localhost:8000 trong .env
 * - Production (Vercel): để trống → dùng same-origin (/api/...)
 */
export const API_BASE = (import.meta.env.VITE_API_URL || '').replace(/\/$/, '');

export function apiUrl(path) {
  const normalized = path.startsWith('/') ? path : `/${path}`;
  return `${API_BASE}${normalized}`;
}

export function assetUrl(path) {
  if (!path) return '';
  if (path.startsWith('http')) return path;

  const cleanPath = path.startsWith('/') ? path.slice(1) : path;
  return `${API_BASE}/${cleanPath}`;
}
