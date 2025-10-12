# Bite & Brew - Coffee Shop Website

A modern, responsive website for Bite & Brew coffee shop, built with HTML, CSS, and JavaScript.

## 🚀 Getting Started

### Prerequisites

- A modern web browser (Chrome, Firefox, Safari, or Edge)
- A code editor (VS Code recommended)
- Node.js (for development and running a local server)
- Git (for version control)

### Recommended VS Code Extensions

For the best development experience, we recommend installing these VS Code extensions:

- **Live Server** - For hot-reloading during development
- **Prettier** - Code formatter
- **ESLint** - JavaScript linter
- **IntelliSense for CSS class names** - CSS class name completion
- **Auto Rename Tag** - Automatically rename paired HTML tags
- **Path Intellisense** - Autocomplete filenames

### Installation

1. **Clone the repository**
   ```bash
   https://github.com/usernames/coffee_shop.git
   cd coffee_shop
   ```

2. **Install dependencies** (if any)
   ```bash
   npm install
   ```

## 🛠 Development

### Running Locally

1. **Using Live Server (Recommended)**
   - Install the Live Server extension in VS Code
   - Right-click on `index.html` and select "Open with Live Server"
   - The site will open in your default browser at `http://127.0.0.1:5500`

2. **Using Python's HTTP Server (Alternative)**
   ```bash
   # Python 3
   python -m http.server 8000
   ```
   Then open `http://localhost:8000` in your browser.

## 📁 Project Structure

```
bite-n-brew/
├── assets/
│   ├── css/
│   │   ├── styles.css     # Main styles
│   │   ├── variables.css  # CSS variables and theming
│   │   └── responsive.css # Responsive styles
│   ├── js/
│   │   └── main.js       # Main JavaScript file
│   └── images/           # Image assets
├── index.html            # Home page
├── about.html            # About page
├── contact.html          # Contact page
└── README.md             # This file
```

## 🎨 Styling Guidelines

- We use CSS custom properties (variables) for theming (see `variables.css`)
- Follow BEM (Block Element Modifier) naming convention for CSS classes
- Mobile-first responsive design approach

## 🤝 Contributing

1. Create a new branch for your feature or bugfix:
   ```bash
   git checkout -b feature/your-feature-name
   ```
2. Commit your changes with descriptive messages
3. Push to your branch and create a Pull Request


