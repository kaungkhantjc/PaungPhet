# PaungPhet - Wedding Invitation app

[![License](https://img.shields.io/badge/License-AGPL%20v3.0-blue.svg)](https://www.gnu.org/licenses/agpl-3.0.en.html)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-v4.1-blue.svg)](https://filamentphp.com)

A modern wedding invitation application built with **Laravel 12** and **Filament v4.1**.

<https://paungphet.com/admin>

### 🛠️ Tech Stack

* **Framework:** [Laravel 12](https://laravel.com)
* **Admin Panel:** [FilamentPHP v4.1](https://filamentphp.com)
* **Database:** PostgreSQL / MySQL

### 🛠️ Development Setup

- Duplicate the Environment File:
    ```bash
      cp .env.example .env
    ```
- Configure Database
  > Edit `.env` to set your MySQL credentials
- Set Application URL
  > Replace APP_URL in .env with your local access URL (e.g., https://paungphet.test).
- Dependencies and Assets
    ```bash
    composer install
    npm install
    npm run build
    php artisan storage:link
    ```

---

### ⚖️ License & Copyright

**Copyright (c) 2025 Kaung Khant Kyaw and Khun Htetz Naing. All Rights Reserved.**

This project is dual-licensed. You may choose one of the following licenses depending on your use case:

#### 1. GNU AGPL v3.0 (Open Source / Personal Use)

Suitable for:

- Personal use (e.g., hosting your own wedding invitation).
- Open Source development.
- Non-commercial forks where the source code is kept open.

Under this license, if you modify the code and distribute it (or host it as a service over a network), you **must**
release your source code to the users.

[Read the AGPL v3.0 License](LICENSE)

#### 2. Commercial License (Private / Business Use)

Suitable for:

- Commercial entities wanting to keep modifications private.
- Agencies using this app as a base for paid client projects without sharing source code.
- Users who cannot comply with the AGPL v3.0 terms.

For licensing or inquiries, please open a GitHub Issue for follow-up.

> This project was co-developed by [Kaung Khant Kyaw](https://github.com/kaungkhantjc)
> and [Khun Htetz Naing](https://github.com/KhunHtetzNaing). Both parties hold copyright and attribution rights to the
> codebase.

---


