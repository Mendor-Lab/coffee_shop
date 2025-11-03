# Bite & Brew — Coffee Shop Web App

A full-stack e-commerce website for Bite & Brew coffee shop, built with PHP, JavaScript, and Supabase.
The project supports a dynamic menu, shopping cart (localStorage), checkout processing, and contact handling.

## 🌟 Key Features

- Dynamic menu with category filtering
- Shopping cart with localStorage persistence
- Secure checkout system
- Contact form with validation
- Order management via Supabase/MySQL
- Responsive design (mobile-first)
- Real-time cart updates

## 🔧 Prerequisites

- PHP 7.4+ (with cURL and mbstring extensions)
- Modern web browser
- VS Code (recommended)
- XAMPP/MAMP or PHP built-in server
- Git
- Supabase account (optional)

## 🚀 Installation

### Option A: Using XAMPP (Recommended)

1. Install XAMPP from [apache friends](https://www.apachefriends.org/)
2. Clone the repository:
   ```bash
   git clone https://github.com/Mendor-Lab/coffee_shop.git
   cd coffee_shop
   ```
3. Move project to XAMPP directory:
   ```bash
   # Windows
   xcopy /E /I coffee_shop C:\xampp\htdocs\coffee_shop
   # macOS/Linux
   cp -r coffee_shop /Applications/XAMPP/htdocs/
   ```
4. Start Apache in XAMPP Control Panel
5. Visit: `http://localhost/coffee_shop`

### Option B: Using PHP Built-in Server

1. Clone the repository
2. Navigate to project directory
3. Start server:
   ```bash
   php -S localhost:8000
   ```
4. Visit: `http://localhost:8000`

## 📁 Project Structure

```
coffee_shop/
├── index.php                # Home page
├── about.php               # About page
├── menu.php               # Menu listing
├── cart.php               # Shopping cart
├── contact.php            # Contact form
│
├── includes/              # Shared components
├── assets/               # Static resources
│   ├── css/
│   ├── js/
│   └── images/
│
├── backend/              # Server logic
├── data/                # Local storage
└── docs/                # Documentation
```

## ⚙️ Configuration

1. Copy environment template:
   ```bash
   cp .env.example .env
   ```

2. Update `.env` with your credentials:
   ```env
   SUPABASE_URL=your_url
   SUPABASE_KEY=your_key
   SUPABASE_TABLE_ORDERS=orders
   SUPABASE_TABLE_MESSAGES=messages
   ```

## 👥 Development Workflow

1. Pull latest changes:
   ```bash
   git pull origin main
   ```

2. Create feature branch:
   ```bash
   git checkout -b feature/your-feature
   ```

3. Commit changes:
   ```bash
   git add .
   git commit -m "Feature: brief description"
   git push origin feature/your-feature
   ```

4. Create Pull Request on GitHub

## 🔍 Testing Checklist

- [ ] Responsive design (320px - 1024px)
- [ ] Form validation
- [ ] Cart functionality
- [ ] Checkout process
- [ ] Database operations
- [ ] Accessibility compliance

## 🛡️ Security Notes

- Never commit `.env` file
- Sanitize all user inputs
- Use prepared statements for DB queries
- Implement server-side validation
- Keep dependencies updated

## 👥 Team

- Elton — UI/UX Designer & handles backend & final merge
- Vinny — About & Contact + mail handler
- Amelia — Menu & Cart JS

## 📫 Support

For questions or issues, please open a GitHub issue or contact the team lead.
