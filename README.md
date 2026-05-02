# Luxury Fitness Class Management System

Production-ready PHP 8.3 + MySQL 8 app with secure auth, role-based admin controls, FullCalendar scheduling, and Stripe Checkout payments.

## Requirements
- PHP 8.3+
- MySQL 8+
- Nginx
- Composer

## SQL Database (what to run)
1. Create an app database user (recommended):
   ```sql
   CREATE USER 'fitness_user'@'%' IDENTIFIED BY 'change_me';
   GRANT ALL PRIVILEGES ON luxury_fitness.* TO 'fitness_user'@'%';
   FLUSH PRIVILEGES;
   ```
2. Import schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
3. Confirm tables:
   ```sql
   USE luxury_fitness;
   SHOW TABLES;
   ```

The schema includes:
- `users`
- `classes`
- `class_registrations`
- `payments`

and adds foreign keys, unique constraints, and CHECK constraints for production integrity.

## App Setup
1. Deploy to `/var/www/fitness-app`.
2. Copy `.env.example` to `.env` and set real values.
3. Install dependencies:
   ```bash
   composer require stripe/stripe-php
   ```
4. Nginx document root should be `/var/www/fitness-app/public`.

## Security Defaults
- Prepared statements only (PDO, emulate prepares off)
- CSRF protection
- Secure session cookie settings + timeout
- Role-based admin access checks
- Server-side Stripe session verification before payment persistence
