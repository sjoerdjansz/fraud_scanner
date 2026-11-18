# VZ Fraud Scanner

Laravel 13 + Inertia 3 + Vue 3 (Tailwind 4) app that fetches all customers from the VZ Customers API, checks them for fraud and stores every scan.

## Setup

The app runs in Docker with Laravel Sail. You only need **Docker Desktop** (running). PHP, Composer and Node are not needed on your machine.

The app uses ports **8000** (app) and **8080** (Customers API).

### Step 1: Clone the project

```bash
git clone <repo-url>
cd fraud_scanner
```

### Step 2: Create the `.env` file

```bash
cp .env.example .env
```

### Step 3: Install the PHP dependencies

Sail itself is a Composer package, so the `vendor/` folder must exist before you can use it.

If you have PHP 8.4.1 and Composer installed:

```bash
composer install
```

If you don't, run Composer once in a temporary Docker container:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

This starts a throwaway container with PHP and Composer (`--rm`), runs it as your own user so the files belong to you (`-u`), mount this folder into the container (`-v`, `-w`) and run `composer install`. This approach is taken from the Laravel Sail docs.

### Step 4: Start the containers

```bash
./vendor/bin/sail up -d
```

This starts two containers: the Laravel app and the VZ Customers API. The first run builds the image and takes a few minutes.

### Step 5: Set up the app

```bash
touch database/database.sqlite
./vendor/bin/sail composer run setup
```

The first command creates the empty SQLite database. The second generates the app key, runs the migrations, installs the npm packages and builds the frontend.

### Step 6: Open the app

| What                      | URL                                    |
| ------------------------- | -------------------------------------- |
| App                       | http://localhost:8000                  |
| Customers API (from host) | http://localhost:8080/api/v1/customers |

Click **New Scan** to run your first scan.

### Useful commands

```bash
./vendor/bin/sail down                   # stop the containers
./vendor/bin/sail npm run dev            # frontend with hot reload (during development)
./vendor/bin/sail artisan test           # run the PHPUnit tests
./vendor/bin/sail composer ci:check      # formatting, lint, types, Pint, PHPStan and PHPUnit
```

Inside Docker the app reaches the API via `CUSTOMERS_API_URL=http://customers-api` (set in `.env`).

## What I implemented

**Scan flow**

The "New Scan" button posts to `POST /scans` (`FraudScanController`), which fetches customers, runs the fraud checks, saves the scan in a DB transaction and redirects to the scan detail page.

**CustomerService**

HTTP call with timeout and retries. Connection/HTTP errors and invalid payloads throw a `CustomerException`. This is logged and shown as an error on the scanner page.

**FraudService**

Flags foreign (non +31) phone numbers, customers under 18, and shared IBAN/IP addresses. Each customer gets a list of `FraudReason` enums.

**Models**

A `Scan` hasMany `Customer`. Customers are a snapshot per scan, with fraud reasons stored as JSON.

**Pages**

Scanner (`/`), scan overview (`/scans`) and scan detail (`/scans/{id}`). The overview lists every scan. The detail page shows all recorded data of that scan, with fraudulent rows highlighted and a details modal per customer.

### Other

- Styling with Tailwind.
- Fraud reason per customer: every `FraudReason` enum case has a readable label (e.g. "IBAN is shared between customers"). A `fraud_reason_labels` accessor on `Customer` (appended to the JSON) sends these labels to the frontend, so the labels live in one place (PHP) and the modal shows them.
- PHPUnit tests
    - _Unit_: the four fraud rules in `FraudService`, including edge cases (turning 18 today, customers without IBAN/IP do not count as "shared"), and date parsing in `Customer::fromApi()` (invalid dates like `31-02-2000` become `null`).
    - _Feature_: the full scan flow with `Http::fake()`: a successful scan, a retry after one failed request, junk entries in the customer list, an API error and `success: false`. Plus the Inertia props of the overview and detail pages.
- Restore after refresh: technically not implemented as true caching (with Laravel's Cache). It's pseudo-covered because of the current design. Every scan is stored and has its own URL (`/scans/{id}`), and the scan button redirects there, so a refresh reloads the same results from the database. Didn't implement because of time constraints.

## Choices & trade-offs

- **Fraud reasons instead of `is_fraudulent`**: one source of truth that also explains _why_. Fraudulent = reasons not empty. Trade-off: a bit more verbose to query "all fraudulent customers" in SQL.
- **Customer snapshot per scan**: data and age change over time, so every scan stores its own copy. This costs more storage than linking to unique customers but feels like a correct implementation of the presented data.
- **Data minimisation**: I only store what is needed for the fraud checks and to recognise a customer. The API also returns a BSN, address, tag and last login/invoice dates. I made the decision to not store these in the database and thus not show them in the frontend due to GDPR (AVG).
- **Messy data is tolerated**: Junk entries that are not customers are skipped and only an unusable response fails the whole scan, so no misleading partial scan is saved. An invalid birth date is stored as null for example.
- **Missing data is not treated as fraud**: a customer without a phone number or valid birth date is not flagged as fraudulent.
- **Skipped:** JSON API endpoints and the OpenAPI v3 spec, to focus on the minimal requirements.

## With more time I would implement

- Fraud reasons with context ("same IP as John Doe (127.0.0.1)") instead of a fixed label per reason. I would probably do this by putting each fraud rule in its own class with a shared interface
- Run the scan as a queue job (fetch, check and persist to the database) because with the current implementation you'll be watching 'hold your horses...' for quite some time when the dataset is big or the API is slow. With 100 customers the wait is short and YAGNI.
- JSON API endpoints and an OpenAPI v3 spec.
- Scan overview with totals (customers / fraudulent) per scan, and pagination. The overview currently eager loads all customers of every scan, which it does not need.
- Retry on invalid JSON, not only on connection/HTTP errors. Normalize IBAN/IP before comparing.
- UI, UX and accessibility. I kept the interface relatively simple to focus on the essential  
  requirements. Possible improvements can include:
    - formatting the date of birth for the nl-NL locale (14-06-1990) with a nice helper function
    - grouping the fraud reasons in the modal under a single heading
    - closing the modal with the esc key
    - adding aria-labels and other accessibility improvements
    - filtering scans and customers, for example on fraudulent only
    - clearer navigation and more reusable components (buttons, badges, tables)

## AI usage

**Claude Code**, configured via a small `CLAUDE.md`. I generally tend to use AI as a Socratic tutor. This means I set it up to be less eager to code. It provides hints, asks questions, and points me to the right document to help me figure out the solution to a problem. The second tier is pseudocode and the last resort is concrete examples, usually from a different domain first. Only when I explicitly ask it to give me the code, it will.

**I used it for:**

- Explaining Inertia concepts (what, how, why). Inertia is relatively new to me.
- Explaining Laravel concepts, point me to the right documentation
- Reviewing my solutions and pointing out bugs and edge cases
- Debugging when I got stuck, in the Socratic way described above. For example in FraudService I experimented with Collections and ended up using a plain foreach for the per-customer checks (more readable to me) and Collections (countBy) for finding duplicate IBANs and IP addresses.* Helping me with the Docker setup
- Concrete code for a few small parts where I got stuck, like the `fraud_reason_labels` accessor and its PHPStan docblock
- Pointing out errors in the display data in the modal and reviewing my error handling
- Generating the PHPUnit tests which I double checked to see if they cover the essentials of the application. Namely: the fraud rules, the scan flow and how errors from the Customers API are handled. I could have let Claude generate a lot more, but that would probably miss the point and would only add quantity rather than me being confident in what is being tested.
- This README file. Specifically a first draft which I reviewed and edited where needed
