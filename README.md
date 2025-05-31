# phpg-stack

A simple PHP project connected to **PostgreSQL**, designed to run on a local machine using **XAMPP** and **pgAdmin 4** for database management.

---

## 📦 Tech Stack

- PHP (via **XAMPP** with Apache)
- PostgreSQL (installed locally)
- pgAdmin 4 (PostgreSQL database UI)

---

## ⚙️ Getting Started

### 1. 🔧 Requirements

- [XAMPP](https://www.apachefriends.org/)
- [PostgreSQL](https://www.postgresql.org/download/)
- [pgAdmin 4](https://www.pgadmin.org/download/)
- PHP PostgreSQL extension enabled (`php_pgsql.dll`)

---

### 2. 📁 Setup Project

1. Place your PHP project in the XAMPP `htdocs` directory:
C:\xampp\htdocs\phpg-stack\

```

2. Start Apache server using the **XAMPP Control Panel**.

3. Visit your project in the browser:

C:\xampp\htdocs\phpg-stack\

```
2. Start Apache server using the **XAMPP Control Panel**.

3. Visit your project in the browser:

🛠️ Enable PHP PostgreSQL Extensions
Open C:\xampp\php\php.ini

Find and uncomment the following lines (remove ;):

ini
extension=pgsql
extension=pdo_pgsql
Restart Apache from the XAMPP Control Panel.

✅ Test
Open your browser and go to http://localhost/phpg-stack/

You should see a success message if the database connection works.

📌 Notes
Ensure PostgreSQL is running before loading the page.

You can use pgAdmin 4 to manage your tables, queries, and schema visually.