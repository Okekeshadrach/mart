# MART

Repo layout:

- [`frontend/`](./frontend): Laravel frontend on `http://127.0.0.1:8000`
- [`backend/`](./backend): Laravel API on `http://127.0.0.1:8001`

Admin panel:

- Filament lives in [`backend/`](./backend)
- Admin URL: `http://127.0.0.1:8001/admin`
- Site settings page: `http://127.0.0.1:8001/admin/site-settings`

Local port map:

- Frontend Laravel app: `8000`
- Backend Laravel API: `8001`
- Frontend Vite: `5173`
- Backend Vite: `5174`

Run locally:

```powershell
cd D:\HP\Prod\mart\backend
php artisan serve --host=127.0.0.1 --port=8001
```

```powershell
cd D:\HP\Prod\mart\frontend
php artisan serve --host=127.0.0.1 --port=8000
```

If you need Vite in both apps, run `npm run dev` separately in each project; the ports are already pinned to avoid collisions.

Backend setup, routes, seeded accounts, and artisan commands are documented in [`backend/README.md`](./backend/README.md).
