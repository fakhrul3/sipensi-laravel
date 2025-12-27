# SIPENSI Laravel

Repo ini berisi website SIPENSI (Laravel) termasuk halaman Dashboard (Home), Inkubator, dan Mitra Kolaborator.

## 🔑 Aturan Kolaborasi (WAJIB)
- ❌ Dilarang push langsung ke `main`
- ✅ Semua perubahan HARUS lewat Pull Request (PR)
- ✅ PR wajib di-ACC oleh Owner Repo
- ✅ Kerja selalu di branch (feature/fix/ui)

## 🌿 Struktur Branch
- `main` → branch final / production
- `feature/nama-fitur` → fitur baru
- `fix/nama-bug` → perbaikan bug
- `ui/nama-halaman` → perubahan tampilan

## 🚀 Quick Start (Local)
> Pastikan sudah install: PHP, Composer, Node.js, dan MySQL (opsional sesuai kebutuhan project)

```bash
composer install
cp .env.example .env
php artisan key:generate
