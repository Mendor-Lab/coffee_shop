# Bite & Brew - Coffee Shop E-commerce Website

A full-stack e-commerce website for Bite & Brew coffee shop, built with PHP, JavaScript, and Supabase.

## Features

- Dynamic menu with category filtering
- Shopping cart with localStorage persistence
- Checkout system with customer details
- Contact form with validation
- Order management with Supabase database
- Responsive design for all devices
- Real-time cart count updates

## 🚀 Getting Started

### Prerequisites

- PHP 7.4 or higher with cURL extension
- A modern web browser (Chrome, Firefox, Safari, or Edge)
- A code editor (VS Code recommended)
- Supabase account (database is pre-configured)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Mendor-Lab/coffee_shop.git
   cd coffee_shop
   ```

2. **Configure Environment**
   The `.env` file contains Supabase credentials (already configured)

3. **Start PHP Server**
   ```bash
   php -S localhost:8000
   ```
   Then open `http://localhost:8000` in your browser.

## 📁 Project Structure

```
bite-n-brew/
├── assets/
│   ├── css/
│   │   ├── styles.css      # Main styles
│   │   ├── variables.css   # CSS variables
│   │   ├── menu.css        # Menu page styles
│   │   ├── cart.css        # Cart page styles
│   │   └── contact.css     # Contact page styles
│   ├── js/
│   │   ├── main.js         # Core functionality
│   │   ├── cart.js         # Cart operations
│   │   ├── menu.js         # Menu display
│   │   ├── cart-page.js    # Cart page logic
│   │   └── contact.js      # Contact form validation
│   └── public/
│       └── icons/          # Logo and icons
├── data/
│   └── menu.json           # Menu items data
├── includes/
│   ├── header.php          # Shared header
│   └── footer.php          # Shared footer
├── php/
│   ├── supabase-client.php # Supabase connection
│   ├── send-mail.php       # Contact form handler
│   └── process-order.php   # Order processing
├── index.php               # Home page
├── menu.php                # Menu page
├── cart.php                # Shopping cart
├── contact.php             # Contact page
├── about.php               # About page
└── README.md
```

## 🎨 Features Overview

### Menu System
- Dynamic product loading from JSON
- Category filtering (Coffee, Pastries, Specialty Drinks, Snacks)
- Product cards with images, descriptions, and prices
- Add to cart functionality with notifications

### Shopping Cart
- LocalStorage-based persistence
- Quantity controls (increase/decrease)
- Remove items functionality
- Clear cart option
- Real-time total calculation with 15% tax

### Checkout
- Customer details form with validation
- Email and phone number validation
- Order submission to Supabase database
- Unique order ID generation
- Order confirmation display

### Contact Form
- Client-side validation
- Email format validation
- Message storage in Supabase
- Fallback to file storage (messages.txt)
- Success/error feedback

## 🗄️ Database Schema

### Orders Table
- order_id (unique identifier)
- customer_name, customer_email, customer_phone
- delivery_address
- items (JSON)
- subtotal, tax, total
- status (default: pending)
- created_at timestamp

### Messages Table
- name, email, subject, message
- status (default: unread)
- created_at timestamp

## 🔒 Security

- Row Level Security (RLS) enabled on all tables
- Public insert access for orders and messages
- Authenticated read access for admin queries
- Input validation on both client and server
- Prepared statements for database queries

## 🎯 Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: Supabase (PostgreSQL)
- **Styling**: Custom CSS with CSS Variables
- **Icons**: Font Awesome 6.0
- **Fonts**: Google Fonts (Playfair Display, Poppins)

## 📱 Responsive Design

- Mobile-first approach
- Breakpoints: 576px, 768px, 992px
- Touch-optimized controls
- Responsive navigation with mobile menu

## 🤝 Contributing

1. Create a new branch for your feature
2. Commit your changes with descriptive messages
3. Test thoroughly on different devices
4. Push to your branch and create a Pull Request


