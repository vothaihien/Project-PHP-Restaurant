# 🍕 Pigeon - Food Delivery System

A comprehensive food delivery platform built with Laravel, featuring separate dashboards for customers, restaurants, drivers, and administrators.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.x-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Default Credentials](#default-credentials)
- [Project Structure](#project-structure)
- [API Keys](#api-keys)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## ✨ Features

### 👤 Customer Features
- 🔐 User registration and authentication
- 🔍 Search restaurants by name, category, rating, or price
- 🍽️ Browse menus and add items to cart
- 📍 Select delivery address with Mapbox Geocoder (Ho Chi Minh City, Vietnam)
- 💳 Secure payment with Stripe
- 💰 Add tips for drivers
- 📊 Track order status in real-time
- ⭐ Rate and review restaurants

### 🏪 Restaurant Features
- 🔐 Restaurant registration and authentication
- 📊 Dashboard with dynamic sales and orders charts
- 🍔 Menu management (add, edit, delete items)
- ⏰ Manage operating hours
- 📦 View and manage orders
- ✅ Mark orders as ready for pickup
- ❌ Cancel orders
- 💬 View customer reviews

### 🚗 Driver Features
- 🔐 Driver registration and authentication
- 📝 Add driver's license information (with validation)
- 🚙 Add vehicle information (with validation)
- 📋 Dashboard with available orders
- 🎯 Reserve orders for delivery
- 🗺️ Map navigation with Mapbox Directions
- ✅ Mark pickup and delivery complete
- 📜 View delivery history

### 👨‍💼 Admin (Pigeon) Features
- 📊 Comprehensive dashboard with charts
- 👀 View all orders across all restaurants
- ❌ Cancel orders
- 💸 Process refunds
- 🏪 Manage restaurants
- 👥 Manage users

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x (PHP 8.x)
- **Frontend**: Blade Templates, Bootstrap 5, jQuery
- **Database**: MySQL / SQLite
- **Maps**: Mapbox GL JS + Geocoder
- **Charts**: Chart.js
- **Payment**: Stripe
- **Authentication**: Laravel Multi-Auth (4 guards)
- **Caching**: Redis / File Cache

---

## 💻 System Requirements

- PHP >= 8.1
- Composer
- Node.js >= 16.x & NPM
- MySQL >= 5.7 or SQLite
- Git

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/vothaihien/Project-PHP-Restaurant.git
cd Project-PHP-Restaurant
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install --force
```

### 4. Create Environment File

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Build Frontend Assets

```bash
npm run dev
# or for production
npm run build
```

---

## ⚙️ Configuration

### 1. Database Configuration

Edit `.env` file:

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pigeon
DB_USERNAME=root
DB_PASSWORD=your_password
```

**For SQLite (Development):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

### 2. Stripe Configuration

Get your Stripe API keys from [Stripe Dashboard](https://dashboard.stripe.com/apikeys):

```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

### 3. Mapbox Configuration

The project uses Mapbox for maps and geocoding. The API key is already included in the code:

```javascript
mapboxgl.accessToken = 'pk.eyJ1Ijoia25pZmVib3NzIiwiYSI6ImNrOWlyazllcTE1NmQzZXBuZXh5MHVpM3QifQ.eNaU-QnXEbcFzghOYUGVvA';
```

For production, get your own key from [Mapbox](https://account.mapbox.com/access-tokens/).

### 4. Mail Configuration (Optional)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@pigeon.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🗄️ Database Setup

### 1. Create Database

**For MySQL:**
```bash
mysql -u root -p
CREATE DATABASE pigeon;
EXIT;
```

**For SQLite:**
```bash
touch database/database.sqlite
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Seed Database

```bash
php artisan db:seed
```

This will create:
- 40 sample users
- 40 restaurants with addresses in Ho Chi Minh City, Vietnam
- 40 delivery addresses in Ho Chi Minh City
- 10 food categories
- 1 test driver account
- Sample menus, reviews, and operating hours

### 4. Or Run Migration + Seed in One Command

```bash
php artisan migrate:fresh --seed
```

⚠️ **Warning**: This will drop all tables and recreate them!

---

## 🏃 Running the Application

### 1. Start Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### 2. Watch Frontend Assets (Optional)

In a separate terminal:

```bash
npm run dev
```

### 3. Clear Cache (If Needed)

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## 🔑 Default Credentials

### 👤 Customer Account
- **Email**: Any email from `database/seeders/UserTableSeeder.php`
- **Password**: `secret`

Example:
- Email: `aceventura@mail.com`
- Password: `secret`

### 🏪 Restaurant Accounts
- **Email**: `{restaurant_name}@mail.com` (lowercase, no spaces)
- **Password**: `secret`

Examples:
- Email: `pizzapizza@mail.com` / Password: `secret`
- Email: `pizzahut@mail.com` / Password: `secret`
- Email: `kfc@mail.com` / Password: `secret`

### 🚗 Driver Account
- **Email**: `driver@mail.com`
- **Password**: `secret`

### 👨‍💼 Admin (Pigeon) Account
- **Email**: `pigeon@mail.com`
- **Password**: `secret`

---

## 📁 Project Structure

```
pigeon-food-delivery/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CheckoutController.php    # Checkout & payment
│   │   │   ├── RestaurantController.php  # Restaurant dashboard
│   │   │   ├── DriverController.php      # Driver dashboard
│   │   │   └── PigeonController.php      # Admin dashboard
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Restaurant.php
│   │   ├── Driver.php
│   │   ├── Order.php
│   │   └── ...
│   └── Providers/
│       └── AuthServiceProvider.php       # Gates & policies
├── database/
│   ├── migrations/                       # Database schema
│   ├── seeders/                          # Sample data
│   └── database.sqlite                   # SQLite database
├── public/
│   ├── css/
│   ├── js/
│   │   └── geocoder.js                   # Mapbox geocoder config
│   └── dashboard/
│       └── js/
│           └── dashboard-charts.js       # Dynamic charts
├── resources/
│   ├── views/
│   │   ├── checkout.blade.php
│   │   ├── driver/
│   │   ├── dashboard/
│   │   │   ├── restaurant/
│   │   │   └── pigeon/
│   │   └── layouts/
│   └── sass/
├── routes/
│   └── web.php                           # All routes
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 🔐 API Keys

### Mapbox
- **Current Key** (included): `pk.eyJ1Ijoia25pZmVib3NzIiwiYSI6ImNrOWlyazllcTE1NmQzZXBuZXh5MHVpM3QifQ.eNaU-QnXEbcFzghOYUGVvA`
- **Get Your Own**: [Mapbox Access Tokens](https://account.mapbox.com/access-tokens/)
- **Usage**: Maps, geocoding, directions
- **Location**: `public/js/geocoder.js`, `resources/views/driver/order.blade.php`

### Stripe
- **Get Keys**: [Stripe Dashboard](https://dashboard.stripe.com/apikeys)
- **Test Mode**: Use test keys for development
- **Live Mode**: Use live keys for production

---

## 🐛 Troubleshooting

### Issue: "Class not found" errors
```bash
composer dump-autoload
```

### Issue: "No application encryption key has been set"
```bash
php artisan key:generate
```

### Issue: "SQLSTATE[HY000] [1045] Access denied"
- Check your `.env` database credentials
- Make sure MySQL service is running

### Issue: "Mix manifest not found"
```bash
npm install
npm run dev
```

### Issue: Orders not showing for drivers
- Make sure restaurant has marked orders as "ready for pickup"
- Check order status in database

### Issue: Map not loading
- Check Mapbox API key
- Check browser console for errors
- Verify internet connection

### Issue: Payment not working
- Verify Stripe API keys in `.env`
- Use Stripe test card: `4242 4242 4242 4242`

---

## 📊 Order Status Flow

```
1. new                      → Order placed by customer
2. food_ready_for_pickup    → Restaurant marks order ready
3. reserved                 → Driver reserves the order
4. food_picked_up           → Driver picks up food from restaurant
5. delivered                → Driver delivers to customer
6. cancelled                → Order cancelled
7. refunded                 → Order refunded by admin
```

---

## 🌍 Localization

The application is configured for **Ho Chi Minh City, Vietnam**:

- 📍 All addresses are in Ho Chi Minh City
- 🗺️ Map center: `[106.6975, 10.7758]` (Nguyen Hue Street)
- 💰 Tax: VAT 10% (Vietnam standard)
- 💵 Currency: USD (can be changed to VND)
- 🌐 Language: English (Vietnamese can be added)

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Test Accounts
Use the default credentials above to test different user roles.

### Test Payment
Use Stripe test cards:
- Success: `4242 4242 4242 4242`
- Decline: `4000 0000 0000 0002`

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👥 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📧 Support

For support, email support@pigeon.com or open an issue on GitHub.

---

## 🙏 Acknowledgments

- Laravel Framework
- Mapbox for maps and geocoding
- Stripe for payment processing
- Chart.js for beautiful charts
- Bootstrap for responsive design

---

**Made with ❤️ in Vietnam 🇻🇳**
