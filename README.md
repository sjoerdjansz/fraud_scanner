# VZ Fraud Scanner

Laravel 13 + Inertia 3 + Vue 3 (Tailwind 4) app that fetches all customers from the VZ Customers API, checks them for fraud and stores every scan.

## Setup

Requirements: Docker (Laravel Sail).

```bash
git clone <repo-url> && cd vz_backend
cp .env.example .env

# Install PHP dependencies without local PHP
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php84-composer:latest composer install --ignore-platform-reqs

./vendor/bin/sail up -d                  # starts the app + the customers-api container
touch database/database.sqlite
./vendor/bin/sail composer run setup     # key, migrations, npm install, build
```

| What                      | URL                                    |
| ------------------------- | -------------------------------------- |
| App                       | http://localhost:8000                  |
| Customers API (from host) | http://localhost:8080/api/v1/customers |

Inside Docker the app talks to the API via `CUSTOMERS_API_URL=http://customers-api`. For frontend development run `./vendor/bin/sail npm run dev`.

## What I implemented

- **Scan flow**: the "New Scan" button posts to `POST /scans` (`FraudScanController`), which fetches customers, runs the fraud checks, saves the scan in a DB transaction and redirects to the scan detail page.
- **`CustomerService`**: HTTP call with timeout and retries. Connection/HTTP errors and invalid payloads (`success !== true`, missing `customers`) throw a `CustomerException`. This is logged and shown as an error on the scanner page.
- **`FraudService`**: flags foreign (non `+31`) phone numbers, customers under 18, and shared IBAN/IP addresses. Each customer gets a list of `FraudReason` enums instead of a single boolean.
- **Models**: `Scan` hasMany `Customer`. Customers are a snapshot per scan, with fraud reasons stored as JSON.
- **Pages**: scanner (`/`), scan overview (`/scans`) and scan detail (`/scans/{id}`) with fraudulent rows highlighted and a details modal per customer.
- **Bonus**: Tailwind styling and showing the fraud reason per customer.

## Choices & trade-offs

- **Fraud reasons instead of `is_fraudulent`**: one source of truth that also explains _why_. Fraudulent = reasons not empty. Trade-off: harder to query "all fraudulent customers" in SQL.
- **Customer snapshot per scan**: data and age change over time, so every scan stores its own copy. This costs more storage than linking to unique customers.
- **Messy data is tolerated, a broken response is not**: invalid records are skipped and an invalid birth date becomes `null`. An unusable response fails the whole scan, so no misleading partial scan is saved.
- **Missing data is not treated as fraud**: a customer without a phone number or valid birth date is not flagged.
- **Skipped bonuses**: API endpoints/OpenAPI, caching the last scan and PHPUnit tests, to focus on the required flow and error handling.

## With more time

- PHPUnit tests for `FraudService` (pure logic, easy to test) and the scan flow with `Http::fake()`.
- Human-readable fraud reasons with context ("same IP as John Doe (127.0.0.1)").
- Retry on invalid JSON, not only on connection/HTTP errors. Normalize IBAN/IP before comparing.
- Scan overview with totals (customers / fraudulent) per scan, and pagination.
- JSON API endpoints with an OpenAPI v3 spec. Restore the last scan after a refresh.

## AI usage

- **Claude Code (Anthropic)** as a _Socratic tutor_, configured via a `CLAUDE.md` with explicit rules: no code unless I explicitly ask for it, hints and questions instead of solutions. Used for:
    - explaining Laravel/Inertia concepts (what, how, why)
    - reviewing my code and pointing out bugs and edge cases, without fixing them
    - debugging by reading logs and running read-only commands
    - Docker/Sail setup questions
    - a first draft of this README, which I reviewed and edited
- All application code was written by me.
