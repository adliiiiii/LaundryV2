# Repository guide

- This is a Laravel 10 / PHP 8.1+ application. HTTP routes are in `routes/web.php` and `routes/api.php`; application code is under `app/`, Blade views under `resources/views/`, and database migrations/seeders under `database/`.
- The frontend is a small Laravel Vite build, with entrypoints `resources/css/app.css` and `resources/js/app.js` configured in `vite.config.js`.
- Run PHP tests with `php artisan test` (or a focused test with `php artisan test --filter=TestName`). PHPUnit's configured suites are `tests/Unit` and `tests/Feature`; testing defaults to array cache/session, synchronous queues, and array mail. Database-backed tests need an explicitly configured test database; SQLite in-memory is currently commented out in `phpunit.xml`.
- Frontend commands are `npm run dev` and `npm run build`. There are no package scripts for linting or typechecking.
- Format PHP with `vendor/bin/pint` (Laravel Pint is a dev dependency); there is no configured lint/format command in Composer scripts.
