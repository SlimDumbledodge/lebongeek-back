# lebongeek-back

> Symfony-based backend for the lebongeek marketplace project.

This repository contains the API and admin backend for lebongeek — a classifieds/marketplace service. It is built with Symfony and provides REST-like controllers, authentication (JWT), file uploads, and database migrations.

## Technologies

- **PHP** (Symfony)
- **Symfony** (framework)
- **Doctrine ORM**
- **LexikJWTAuthenticationBundle** (JWT authentication)
- **VichUploaderBundle** (file uploads)
- **KnpPaginatorBundle** (pagination)
- **NelmioCorsBundle** (CORS)
- **Twig** (server-side templates for back-office views)

## Contents

- `src/Controller/Api` — API controllers (Ad, Address, Category, Contact, Product, Search, Transaction, User)
- `src/Controller/Back` — back-office controllers and templates
- `src/Entity` — Doctrine entities
- `migrations/` — Doctrine migration classes
- `public/` — public document root (images, index.php)

## Requirements

- PHP 8.1+ (or the version required by your local environment)
- Composer
- A database supported by Doctrine (MySQL, PostgreSQL, ...)
- OpenSSL (to generate JWT keys)

## Quick setup (Development)

These commands assume you are on Windows PowerShell. Replace values for your environment.

1. Clone the repository

```powershell
git clone https://github.com/SlimDumbledodge/lebongeek-back.git
cd lebongeek-back
```

2. Install PHP dependencies

```powershell
composer install
```

3. Environment variables

Create a `.env.local` (gitignored) with at least the following variables updated:

```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
# LexikJWT settings (paths are examples)
JWT_PASSPHRASE="your_jwt_passphrase"
JWT_PRIVATE_KEY_PATH="%kernel.project_dir%/config/jwt/private.pem"
JWT_PUBLIC_KEY_PATH="%kernel.project_dir%/config/jwt/public.pem"
```

4. Generate JWT keys (example using OpenSSL)

```powershell
# create config/jwt directory if needed
mkdir config\jwt
# generate a private key (encrypted) — you will be prompted for passphrase
openssl genrsa -aes256 -out config/jwt/private.pem 4096
# extract the public key
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

If you want an unencrypted private key (not recommended for production), omit `-aes256`.

5. Database setup & migrations

```powershell
# create the database (Doctrine must be configured in DATABASE_URL)
php bin/console doctrine:database:create
# run migrations
php bin/console doctrine:migrations:migrate
# (optional) load fixtures if available
php bin/console doctrine:fixtures:load
```

If you prefer to restore the provided SQL backup, use your DB client to import `backup.sql`.

6. Run the application

With the Symfony CLI:

```powershell
symfony serve
```

Or with PHP built-in server (for quick local testing):

```powershell
php -S 127.0.0.1:8000 -t public
```

Then open your browser at `http://127.0.0.1:8000` (or API endpoints as appropriate).

## API & Routes

The project exposes API controllers in `src/Controller/Api`. Routes are defined using annotations (or YAML); inspect those controllers to see route paths and request methods. Example controllers:

- `AdController`
- `ProductController`
- `UserController`
- `TransactionController`

Authentication is handled with JWT (Lexik). Protect your requests by obtaining a token using the configured login route (see `SecurityController` / `LoginFormAuthenticator` and Lexik config in `config/packages/lexik_jwt_authentication.yaml`).

## File uploads

Uploaded images and files are stored under `public/images` (product, user/ avatar/banner). VichUploader is used to manage file uploads — check `src/Entity` and `config/packages/vich_uploader.yaml` for mappings.

## Admin / Back-office

There is a basic back-office implemented with Twig templates under `templates/back/` and controllers under `src/Controller/Back`. These views are meant for administrative tasks like listing users, viewing products and transactions.

## Useful commands

- Run database migrations: `php bin/console doctrine:migrations:migrate`
- Clear cache: `php bin/console cache:clear`
- Create user fixtures or load fixtures: `php bin/console doctrine:fixtures:load`

## Troubleshooting

- If JWT auth fails, verify key paths and passphrase in `.env.local` and ensure keys exist under `config/jwt`.
- If uploads fail, check filesystem permissions on `public/images` and VichUploader mapping.
- If routes are missing, inspect controllers for annotation-based routes or `config/routes` YAML files.

## Contributing

Contributions are welcome. Typical workflow:

1. Fork the repository
2. Create a feature branch
3. Implement your changes and add tests if applicable
4. Run `composer install` and ensure the app runs locally
5. Create a pull request with a clear description

## License

No license specified in this repository. Add a `LICENSE` file if you wish to make licensing explicit.

## Contact

Repository owner: `SlimDumbledodge` on GitHub.

If you want the README placed as `README.md` (replacing the existing file) or translated/expanded, tell me which option you prefer.
