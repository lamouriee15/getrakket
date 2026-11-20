# GetRakket on Laravel (one account for everything)

## What changed
- One account type. Anyone can post jobs and apply to other people's jobs.
- Applying needs a work sample (photo, MP4 video, audio, PDF, or a link). The job poster opens it from the job card, then chooses whether to hire.
- Hired work has a status: Hired, In progress, Delivered, Done. The worker updates it, the poster sees it in a bar pinned at the top of the Messages page.
- The Video call button sits in the pinned top bar of the chat and uses an SVG icon so it shows on every phone.
- Video calls: phone fixes plus support for a TURN server (see below).
- Accounts survive restarts when the app uses an external database (see "Deploy on Render").

## Run it from XAMPP (htdocs)
1. Unzip, then copy the `getrakket` folder into `C:\xampp\htdocs\`.
2. Open XAMPP Control Panel and start **Apache**.
3. Open a terminal inside `C:\xampp\htdocs\getrakket` (in VS Code: File > Open Folder, then Terminal > New Terminal) and run once:
   ```
   php artisan migrate
   ```
   If `php` is not recognized, run `C:\xampp\php\php.exe artisan migrate` instead.
4. Open http://localhost/getrakket in your browser. It sends you to http://localhost/getrakket/public, which is the real address of the site.

Other ways to run it: `php artisan serve` then http://127.0.0.1:8000 also works.

Accounts are stored in `database/database.sqlite`, so they stay after you stop Apache.
If migrate says "could not find driver", open `C:\xampp\php\php.ini`, remove the `;` in front of `extension=pdo_sqlite` and `extension=sqlite3`, save, and restart Apache.
To use MySQL instead: create an empty database named `getrakket` in phpMyAdmin, then in `.env` set `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=getrakket`, `DB_USERNAME=root`, `DB_PASSWORD=` and run `php artisan migrate` again.

Upload size: XAMPP allows about 40 MB by default. Bigger files can be shared as a link.

## Deploy on Render so accounts never disappear
Why accounts vanished: a free Render service resets its own disk on every restart, redeploy and sleep, and the old setup kept accounts in a SQLite file on that disk. Accounts now live in an external PostgreSQL database.

1. Create a PostgreSQL database that does not expire. Render's own free database expires after 30 days, so use a provider whose free tier keeps data (for example Neon or Supabase; check their current limits) or a paid Render database. Copy its connection string (it starts with `postgres://` or `postgresql://`).
2. Put this project on GitHub. `.env` and `vendor` are ignored automatically.
3. In Render choose New > Web Service (or Blueprint, it reads `render.yaml`), pick the repo and set the language to Docker.
4. Add these environment variables:
   - `DB_URL` = the connection string from step 1
   - `DB_CONNECTION` = `pgsql`
   - `APP_KEY` = the `APP_KEY=` value from your local `.env` (starts with `base64:`). Keep the same value forever, or everyone is logged out.
   - `APP_ENV` = `production`, `APP_DEBUG` = `false`
5. Deploy. Tables are created automatically on every start (`php artisan migrate --force`).

## Video calls on every phone
- Calls need https (Render gives you that) and a normal browser such as Chrome or Safari. Facebook, Messenger and Instagram in-app browsers often block cameras.
- Both people must have the site open. The other person gets an Accept or Decline screen.
- Some phones are on mobile data or strict Wi-Fi that blocks direct calls. A TURN server fixes that. Create an account with a TURN provider (for example Metered, Cloudflare or Twilio), then add these variables in Render:
  - `TURN_URLS` = comma separated urls, like `turn:example.com:3478,turns:example.com:443`
  - `TURN_USERNAME` and `TURN_CREDENTIAL` = the values your provider gives you

## Reset all data
Local: `php artisan migrate:fresh`
Render: delete the rows in your database, or create a new database and update `DB_URL`.
