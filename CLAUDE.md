# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**Sosro Career** — a Laravel 12 recruitment/career portal. Public job listings, an applicant-facing portal (biodata forms, application tracking), and an admin panel (applicant pipeline management, job postings, reports). Blade + Alpine.js + Tailwind, no SPA framework. UI copy and code comments are predominantly Bahasa Indonesia — match that when editing views/comments in this codebase.

## Running the app

This project runs via **Laravel Sail** (Docker). `DB_HOST=mysql` in `.env` only resolves inside the Sail network — running `php artisan` directly on the host will fail to connect to the database.

```bash
./vendor/bin/sail up -d          # start containers (app, mysql, phpmyadmin)
./vendor/bin/sail artisan ...    # any artisan command needing DB access
composer dev                     # runs server+queue+logs+vite concurrently (inside Sail or with a locally reachable DB)
```

phpMyAdmin is exposed on the port in `FORWARD_PHPMYADMIN_PORT` (default 8080).

## Common commands

```bash
./vendor/bin/sail artisan test                    # run full test suite (Pest, phpunit.xml config)
./vendor/bin/sail artisan test --filter=TestName   # run a single test
./vendor/bin/sail artisan test tests/Feature/Auth/AuthenticationTest.php

./vendor/bin/pint                                  # code style fixer (Laravel preset)
npm run dev                                        # Vite dev server (Tailwind + Alpine assets)
npm run build                                      # production asset build

./vendor/bin/sail artisan migrate                  # run migrations
./vendor/bin/sail artisan tinker                    # REPL for one-off DB/mail testing
```

Tests use `DB_DATABASE=testing` and `SESSION_DRIVER=array` / `CACHE_STORE=array` (see `phpunit.xml`) — no need for a separate test DB setup beyond what Sail's testing DB provides.

## Architecture

### Dual authentication system

Two independent Eloquent auth guards, configured in `config/auth.php`:
- **`web` guard** → `App\Models\User` (table `users`) — applicants/job seekers. Standard Laravel Breeze auth (`routes/auth.php`).
- **`admin` guard** → `App\Models\Admin` (table `admins`) — staff. Separate login/logout/password-reset flow (`app/Http/Controllers/Admin/Auth/*`, routes prefixed `/admin/*` in `routes/web.php`).

Each guard has its own password-reset broker (`users` / `admins` in `config/auth.php`), both backed by the same `password_reset_tokens` table. Do not assume `Auth::user()` — check which guard a controller/middleware is operating under (`Auth::guard('admin')->user()`).

Admin routes are wrapped in `middleware('auth:admin')` in `routes/web.php`; the separate `EnsureUserIsAdmin` middleware (`app/Http/Middleware/EnsureUserIsAdmin.php`) additionally checks `role === 'admin'` and exists but is not currently wired into route groups — verify current usage before relying on it.

### Branch (cabang) data scoping — repeat everywhere in admin controllers

Non-superadmin admins are scoped to their own `cabang_id`. This is enforced **per-controller-method**, not via a global middleware/policy — every admin query that touches `Application`/`Lowongan` data repeats the same pattern:

```php
if (Auth::user()->role !== 'superadmin') {
    $query->whereHas('lowongan', fn($q) => $q->where('cabang_id', Auth::user()->cabang_id));
}
```

See `app/Http/Controllers/Admin/ApplicantController.php` for the canonical shape (`index`, `show`, `byLowongan`, `updateStage`, `downloadPdf`, `bulkUpdate` all repeat it). **When adding a new admin endpoint that lists or mutates applications/lowongan, replicate this check** — there is no shared trait/scope doing it automatically, so it's easy to introduce a scoping gap by copy-pasting without the guard.

### Recruitment pipeline is data-driven, not hardcoded

Stage names, ordering, colors, and per-category pipelines are **not enums in code** — they live in the `recruitment_stages` and `recruitment_stage_pipeline` tables (`app/Models/RecruitmentStage.php`, `RecruitmentStagePipeline.php`). `RecruitmentStage` provides static accessors (`allKeys()`, `pipelines()`, `universalStages()`, `bulkUpdateStages()`, `labels()`, `colors()`) backed by a per-request static cache (`RecruitmentStage::$cache`) — safe because PHP is shared-nothing per request outside Octane. Use `Rule::in(RecruitmentStage::allKeys())` (as `ApplicantController::updateStage` does) rather than hardcoding a stage-key whitelist.

Each stage transition in `ApplicantController::updateStage` triggers a specific `Mailable` (see `app/Mail/*.php` — 9 classes covering psikotes, interview [standard/offline/lanjutan], offering, mcu, rejected, accepted). Adding a new pipeline stage that should notify the applicant means adding both a `recruitment_stages` row and a corresponding branch + Mailable in `updateStage`.

### Applicant data model

`User` (login) → `Applicant` (1:1 via `user_id`, **not DB-unique — only enforced in application code**) → `ApplicantProfile` (1:1, core biodata) → several 1:many child tables (`ApplicantFamilyMember`, `ApplicantWorkExperience`, `ApplicantFormalEducation`, `ApplicantInformalEducation`, `ApplicantDocument`) plus a `job_field_interests` pivot to `JobField`. `Applicant.status` and the various `*_completed` boolean flags / `biodata_progress` int track profile-completion state independent of `Application.status` (recruitment pipeline state, scoped per job application via `Application` → `lowongan_id`).

Profile completion is required before applying: `EnsureApplicantProfileCompleted` middleware (aliased `applicant.complete` in `bootstrap/app.php`) gates `POST /apply/{lowongan}`.

`Admin\Lowongan` model (table `lowongan`, namespaced under `App\Models\Admin`) is the job posting; `bidang`/`kategori` are free-text strings, not FKs, despite a `job_fields` table existing separately for applicant interest tagging.

**Known data-quality quirk** (see `plans/db_sosro_normalized_table_list.md`): `Applicant.status` values are inconsistently cased across code paths (seeder writes `Pending`/`Reviewed`/`Accepted`, `ProfileController` writes `active`/`draft`) — it's a free varchar, not backed by an enum/constraint. Don't assume case-sensitivity is consistent when querying or comparing this column.

### Reference docs

`plans/db_sosro_normalized.dbml` and `plans/db_sosro_normalized_table_list.md` are kept in sync with the **actual** MySQL schema via direct DB introspection (not hand-maintained speculation) — regenerate from the live DB rather than hand-editing when migrations change the schema. Other files under `plans/` are point-in-time feature/investigation notes in Indonesian, not living documentation.

### Mail

SMTP is Gmail-based (`config/mail.php` / `.env` `MAIL_*`), with a documented ~500/day limit — not meant for production bulk sending. `Mail::to()->send()` calls in `ApplicantController` are wrapped in try/catch so a mail failure doesn't block the DB status update (email errors just get logged and flash a degraded success message).

### PDF export

Applicant biodata and admin reports render via `barryvdh/laravel-dompdf` (`Pdf::loadView(...)->stream(...)`). `maatwebsite/excel` (`app/Exports/LaporanPelamarExport.php`) handles the report export in `LaporanController`.
