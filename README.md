<div align="center">

# TaskFlow

**A task manager built with PHP and Yii 2, with a Gemini-powered assistant for asking questions about your tasks and generating productivity reports.**

[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Yii 2](https://img.shields.io/badge/Yii-2.0-green?logo=yii)](https://www.yiiframework.com/)
[![MySQL / MariaDB](https://img.shields.io/badge/MySQL%20%2F%20MariaDB-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Gemini API](https://img.shields.io/badge/Google-Gemini%20API-8E75B2?logo=googlegemini&logoColor=white)](https://ai.google.dev/)
[![License](https://img.shields.io/badge/License-BSD--3--Clause-brightgreen)](LICENSE.md)

</div>

> [!WARNING]
> This repository contains the application source code only. You must create your own local database and add your own Gemini API key before the app and its AI features work. **Never commit real credentials or private task data.**

## Table of contents

- [About](#about)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Quick start](#quick-start)
- [Requirements](#requirements)
- [Installation](#installation)
- [Pages and URLs](#pages-and-urls)
- [Demo login](#demo-login)
- [Configuration](#configuration)
- [Project structure](#project-structure)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Security notes](#security-notes)
- [Docker](#docker)
- [Acknowledgements](#acknowledgements)
- [License](#license)

## About

TaskFlow lets you create, organise and track tasks by category, status, priority and due date. It also includes an AI assistant, powered by Google's Gemini API, that can answer questions about your task list and write a short productivity report.

The project started from the [Yii 2 Basic Project Template](https://github.com/yiisoft/yii2-app-basic). The models, CRUD screens and the `assistant` module were scaffolded with Yii's **Gii** code generator and then extended by hand.

## Features

- Create, view, update, search, sort and delete tasks.
- Organise tasks into categories.
- Track status (`todo`, `in_progress`, `done`) and priority (`low`, `medium`, `high`), with an optional due date.
- Quick-add form for creating a task without opening the full form.
- **AI chat:** ask questions about your tasks in plain English. The conversation history is kept in your session and can be cleared.
- **Voice input:** a microphone button in the chat page fills in your question using the browser's built-in speech recognition (works in browsers that support the Web Speech API, such as Chrome and Edge; microphone permission required).
- **AI report:** generate a short written summary of your task list, with a print / save-as-PDF button.
- Built on Yii 2 with Bootstrap 5.

## Tech stack

| Area | Technology |
|---|---|
| Language | PHP 8.2 or later |
| Framework | Yii 2 (`yiisoft/yii2` ~2.0.54) with `yiisoft/yii2-bootstrap5` |
| Database | MySQL or MariaDB |
| AI | Google Gemini API (called from PHP with cURL) |
| Dependencies | Composer |
| Local development | XAMPP on Windows |
| Tests / quality | Codeception, PHP_CodeSniffer, PHPStan |

## Quick start

For Windows with XAMPP. Every step is explained in detail under [Installation](#installation).

```powershell
cd C:\xampp\htdocs
git clone https://github.com/YOUR-GITHUB-USERNAME/TaskFlow.git taskflow
cd taskflow
composer install
copy config\params-local.php.example config\params-local.php
```

Then:

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. In phpMyAdmin, create a database named `taskflow_db` and import `database/schema.sql`.
3. Open `config\params-local.php` and paste your own Gemini API key.
4. Visit <http://localhost/taskflow/web/>.

## Requirements

Install these before starting:

1. **XAMPP** with Apache, **PHP 8.2 or later** and MySQL/MariaDB: <https://www.apachefriends.org/>
2. **Composer**: <https://getcomposer.org/download/>
3. **Git**: <https://git-scm.com/downloads>
4. A **Gemini API key** for the AI features: <https://aistudio.google.com/app/apikey>

The PHP **cURL extension** must be enabled (the AI features use it). Check your versions in PowerShell or Command Prompt:

```powershell
php -v
composer --version
git --version
```

> [!NOTE]
> If `php` is not recognized, run it from `C:\xampp\php` or add `C:\xampp\php` to your Windows PATH, then reopen the terminal. You can also run `php requirements.php` in the project folder to check your server against Yii's requirements.

## Installation

### 1. Get the source code

Clone the repository into XAMPP's `htdocs` folder (replace `YOUR-GITHUB-USERNAME` with your GitHub username):

```powershell
cd C:\xampp\htdocs
git clone https://github.com/YOUR-GITHUB-USERNAME/TaskFlow.git taskflow
cd taskflow
```

The final `taskflow` in the clone command names the folder in lowercase, so the URLs in this README match.

Alternatively, use **Code → Download ZIP** on GitHub and extract it so the project ends up in `C:\xampp\htdocs\taskflow` (the downloaded folder is usually called `TaskFlow-main`, so rename it).

### 2. Install PHP dependencies

From the project root (`C:\xampp\htdocs\taskflow`):

```powershell
composer install
```

This installs the packages listed in `composer.lock` into the `vendor` folder. `vendor` is intentionally not stored on GitHub.

### 3. Create the database

Yii does not create the database for you, so do this once:

1. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open phpMyAdmin at <http://localhost/phpmyadmin/>.
3. Click **New**, name the database `taskflow_db`, choose the collation `utf8mb4_unicode_ci`, and click **Create**.
   (Or open the **SQL** tab and run: `CREATE DATABASE taskflow_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`)
4. Select `taskflow_db`, open the **Import** tab, choose `database/schema.sql` from this repository, and click **Import**.

The schema creates the `categories` and `tasks` tables and the relationship between them, plus four starter categories (only if the `categories` table is empty). It contains no task data and uses `CREATE TABLE IF NOT EXISTS`, so it will not overwrite existing tables.

> [!CAUTION]
> If you already have these tables with data you care about, back up your database first and do not overwrite it.

### 4. Check the database connection

Open `config/db.php`. The default for a fresh XAMPP install is:

```php
return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;dbname=taskflow_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];
```

A fresh XAMPP MySQL `root` account has no password, so this works as is. If your MySQL account has a password or a different username, change those values **locally** and do not commit them.

### 5. Add your Gemini API key

The repository does not contain an API key. To add yours:

1. Copy `config/params-local.php.example` to `config/params-local.php`.
2. Open `config/params-local.php` and replace the placeholder with your own key.
3. Save the file. It is listed in `.gitignore`, so it is not uploaded to GitHub.

```php
<?php

return [
    'geminiApiKey' => 'PASTE_YOUR_OWN_GEMINI_API_KEY_HERE',
];
```

Create a key at <https://aistudio.google.com/app/apikey>.

> [!TIP]
> Windows hides file extensions by default, so a copied file can accidentally end up named `params-local.php.txt`. In File Explorer, turn on **View → Show → File name extensions** and check that the name ends in `.php`.

### 6. Cookie validation key

Yii needs a secret string to sign its cookies. For local development TaskFlow falls back to a clearly named, development-only value, so you do not need to do anything.

Before deploying anywhere public, set your own secret in the `YII_COOKIE_VALIDATION_KEY` environment variable. You can generate a random one with:

```powershell
php -r "echo bin2hex(random_bytes(32));"
```

Never use the development fallback in production.

### 7. Run the application

**Option A: Apache in XAMPP**

With Apache and MySQL running, open <http://localhost/taskflow/web/>.

This assumes the project folder is `C:\xampp\htdocs\taskflow` and Apache uses its default port. If you use a different folder name or port, change the URL to match.

**Option B: PHP's built-in development server**

From the project root:

```powershell
php yii serve
```

Then open the address it prints (normally <http://localhost:8080/>). Keep this server for local development only.

> [!NOTE]
> Only one `php yii serve` can use a given port at a time. If the port is busy, start another with `php yii serve --port=8081` and use that port in the URLs.

## Pages and URLs

TaskFlow uses Yii's default `?r=` URLs. Add the route from the table to one of these base addresses:

| How you run it | Base address |
|---|---|
| Apache (XAMPP) | `http://localhost/taskflow/web/index.php?r=` |
| `php yii serve` | `http://localhost:8080/index.php?r=` |

| Page | Route | Example (Apache) |
|---|---|---|
| Home | `site/index` | <http://localhost/taskflow/web/> |
| Task list | `task/index` | <http://localhost/taskflow/web/index.php?r=task/index> |
| New task | `task/create` | <http://localhost/taskflow/web/index.php?r=task/create> |
| Quick-add task | `task/quick-add` | <http://localhost/taskflow/web/index.php?r=task/quick-add> |
| Category list | `category/index` | <http://localhost/taskflow/web/index.php?r=category/index> |
| AI chat | `assistant/ai/chat` | <http://localhost/taskflow/web/index.php?r=assistant/ai/chat> |
| AI report | `assistant/ai/report` | <http://localhost/taskflow/web/index.php?r=assistant/ai/report> |
| Login | `site/login` | <http://localhost/taskflow/web/index.php?r=site/login> |
| Gii code generator (development only) | `gii` | <http://localhost/taskflow/web/index.php?r=gii> |

Routes inside a module have three parts: `module/controller/action` (for example `assistant/ai/chat`). Routes in the main app have two: `controller/action` (for example `task/index`).

## Demo login

`models/User.php` contains two hard-coded demonstration accounts inherited from the Yii starter:

| Username | Password |
|---|---|
| `admin` | `admin` |
| `demo` | `demo` |

> [!WARNING]
> These accounts are for local demonstration only. Replace them before exposing the application to other users or the internet. Authentication currently uses these in-code records, not a users table in the database.

## Configuration

| File | Purpose | Committed? |
|---|---|---|
| `config/db.php` | Database connection | Yes (no real credentials) |
| `config/params.php` | Default parameters; `geminiApiKey` is empty here | Yes |
| `config/params-local.php` | **Your** Gemini API key and other local overrides | **No** (git-ignored) |
| `config/params-local.php.example` | Template for the file above | Yes |
| `config/web.php` | Web application configuration (modules, components, dev tools) | Yes |

Check the other files in `config/` if you want to customise the application further.

## Project structure

```text
TaskFlow/
├── assets/                 Asset bundle definitions
├── commands/               Console commands
├── config/                 Application configuration (see Configuration)
├── controllers/            Task, Category and Site controllers
├── database/
│   └── schema.sql          MySQL/MariaDB schema (+ starter categories)
├── mail/                   E-mail layouts
├── models/                 Task, Category, search and login models
├── modules/assistant/      AI chat and report (AiController and its views)
├── runtime/                Files generated at runtime (not committed)
├── tests/                  Codeception tests (inherited from the Yii starter)
├── views/                  View templates (task, category, site, layouts)
├── web/                    Entry script (index.php) and public assets
├── widgets/                Reusable widgets
├── composer.json           Composer dependencies and scripts
├── composer.lock           Locked dependency versions
├── LICENSE.md
└── README.md
```

`vendor/` is created by `composer install` and is not committed. The repository also keeps a few files inherited from the Yii starter template (`yii`, `yii.bat`, `requirements.php`, `codeception.yml`, `phpcs.xml.dist`, `phpstan.neon`, `Vagrantfile`, `docker-compose.yml`).

## Testing

Tests live in the `tests` folder and use the [Codeception](https://codeception.com/) testing framework, with three suites: `unit`, `functional` and `acceptance`. After `composer install`:

```powershell
composer tests    # run the tests (uses PHP's built-in server)
composer cs       # check code style (PHP_CodeSniffer)
composer static   # static analysis (PHPStan)
```

You can also run Codeception directly:

```powershell
vendor/bin/codecept run --env php-builtin
```

> [!NOTE]
> The included tests come from the Yii starter template (login, contact, home and about pages). Task, category and AI features do not have automated tests yet, and some suites may need extra environment configuration. Run them locally and review any failures before relying on the results. Test settings are in `config/test.php` and `config/test_db.php`.

<details>
<summary>More test options (acceptance tests, WebDriver, code coverage)</summary>

The `acceptance` suite is configured in `tests/Acceptance.suite.yml`. By default it uses the `PhpBrowser` module against PHP's built-in server (the `php-builtin` environment):

```powershell
# run acceptance tests only
vendor/bin/codecept run Acceptance --env php-builtin
```

To run acceptance tests in a real browser, switch the suite to the `WebDriver` module:

1. Download and start [Selenium Server](https://www.selenium.dev/downloads/).
2. Install a matching browser driver ([GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://googlechromelabs.github.io/chrome-for-testing/)).
3. Edit `tests/Acceptance.suite.yml` to enable `WebDriver` and disable `PhpBrowser` (an example configuration is included, commented out).
4. Run `vendor/bin/codecept run Acceptance --env php-builtin`.

Code coverage is configured in `codeception.yml`:

```powershell
# coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml --env php-builtin

# coverage for unit tests only
vendor/bin/codecept run Unit --coverage --coverage-html --coverage-xml --env php-builtin
```

Coverage output is written to `tests/Support/output`.

</details>

## Troubleshooting

<details>
<summary><code>php</code> or <code>composer</code> is not recognized</summary>

Confirm PHP and Composer are installed. With XAMPP, PHP is usually at `C:\xampp\php\php.exe`. Run it by its full path, or add `C:\xampp\php` to your PATH and reopen PowerShell.

</details>

<details>
<summary>Composer reports a PHP version mismatch</summary>

The project requires PHP 8.2 or later. Make sure the PHP used by Composer in the terminal (`php -v`) is compatible, and that Apache is also using a PHP 8.2+ build. Older XAMPP releases ship an older PHP and need updating.

</details>

<details>
<summary>Database connection error</summary>

- **"Connection refused":** MySQL is not running. Start it in the XAMPP Control Panel.
- **"Unknown database":** create the `taskflow_db` database (see [Create the database](#3-create-the-database)).
- **"Table ... doesn't exist":** import `database/schema.sql` into `taskflow_db`.
- Check `config/db.php` for the correct username and password.

</details>

<details>
<summary>The AI assistant does not respond</summary>

- If you see *"Gemini API key is not configured"*, `config/params-local.php` is missing, misnamed, or still contains the placeholder.
- Check that the PHP cURL extension is enabled in XAMPP's `php.ini`.
- A `503` / `UNAVAILABLE` response means Google's model is temporarily busy. Wait a moment and try again.
- The model name is set in `modules/assistant/controllers/AiController.php`. Confirm that model is available to your API key and project, or change it to one that is.
- Review the PHP/Apache error log for the API response or network error.
- Gemini API use may be subject to quotas, billing and Google's API terms.
- If the microphone button is disabled, your browser does not support speech recognition. Try Chrome or Edge.

</details>

<details>
<summary>Port already in use when running <code>php yii serve</code></summary>

Another process (often another `php yii serve`) is using the port. Stop it, or start this one on a different port: `php yii serve --port=8081`.

</details>

<details>
<summary>Blank page or "unable to write" errors</summary>

Yii must be able to write to the `runtime/` and `web/assets/` folders. Make sure they exist and are writable by the user running the web server.

</details>

## Security notes

- Never commit `config/params-local.php`, API keys, real database passwords, or real user/task data.
- Rotate any credential that was ever committed or shared. Deleting it in a later commit does not remove it from Git history.
- The demo login accounts and development configuration are not production-ready.
- AI prompts include task details that are sent to Google's Gemini API. Do not use confidential or sensitive task data unless you have assessed user consent, data handling and applicable privacy requirements.
- Before production use, further harden the AI integration: API error handling, timeouts, input validation, output handling, access control, and limiting how many task records are sent to the model.
- By default `web/index.php` runs in development mode (`YII_DEBUG` is `true` and `YII_ENV` is `dev`), which enables the Yii debug toolbar and the Gii code generator. For production, turn debug off, set `YII_ENV` to `prod` so these modules are not loaded, and serve the site over HTTPS.

## Docker

This repository still includes the Yii starter's `docker-compose.yml`, which starts only a PHP/Apache container and **no database**. TaskFlow needs MySQL or MariaDB, so Docker is not set up or tested for this project. Use XAMPP as described above.

## Acknowledgements

Built with the [Yii 2 framework](https://www.yiiframework.com/) and the [Yii 2 Basic Project Template](https://github.com/yiisoft/yii2-app-basic).

[![Powered by Yii Framework](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?logo=yii)](https://www.yiiframework.com/)

Useful links:

- [Yii 2 guide](https://www.yiiframework.com/doc/guide/2.0/en) and [API reference](https://www.yiiframework.com/doc/api/2.0)
- [Composer documentation](https://getcomposer.org/doc/)
- [XAMPP](https://www.apachefriends.org/)
- [Google AI Studio API keys](https://aistudio.google.com/app/apikey)

## License

This repository includes [`LICENSE.md`](LICENSE.md), a BSD-style license inherited from the Yii 2 Basic Project Template (copyright Yii Software LLC). Review it before redistributing the project, and make sure it is appropriate for your own code and any third-party assets.
