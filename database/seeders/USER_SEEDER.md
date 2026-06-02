User Seeder
===========

This documents the users created by Database\Seeders\UserSeeder (database/seeders/UserSeeder.php).

Credentials (for local/dev only)
- Super Admin
  - Name: Super Admin
  - Role: superadmin
  - Email (username): superadmin@shopx.com
  - Phone: 081234567890
  - Password: password

- Admin
  - Name: Admin ShopX
  - Role: admin
  - Email (username): admin@shopx.com
  - Phone: 081234567891
  - Password: password

- Customers (5)
  - Customer 1 — customer1@shopx.com — Password: password
  - Customer 2 — customer2@shopx.com — Password: password
  - Customer 3 — customer3@shopx.com — Password: password
  - Customer 4 — customer4@shopx.com — Password: password
  - Customer 5 — customer5@shopx.com — Password: password

Notes
- Passwords in the seeder are hashed via bcrypt() before insertion. The plain text shown above is what the seeder uses before hashing for convenience in local/dev environments only.
- The seeder also creates default addresses for Super Admin and each customer.

How to run
1. Ensure your local environment is configured and the database is migrated.
2. Run: php artisan db:seed --class=\Database\Seeders\UserSeeder

Security
- Do NOT use these credentials in production. Change passwords and remove any test accounts before deploying.
