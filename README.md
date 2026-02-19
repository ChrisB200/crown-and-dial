# Crown and Dial

E-commerce website specialising in selling watches created using the laravel framework. The project shows key concepts in web development including MVC architecture.

## Authors

Christopher Bonner (240126867)
Hassan Nadeem (240227453)
Eisa Alkandari (230437043)
Shah Ahmed (230178652)
Mohammed Ryhan Ahmad (240153036)
Mohammed Hassan (240146223)
Naly Salar (240150208)
Cameron Chopra (240134345)

## Features

- Light/dark mode toggle
- User authentication - register/login/logout
- Product categories
- Basket to save products for later
- Checkout page to order products
- Admin page for managing users and editing products

## Tech Stack

- Backend: Laravel (PHP Framework)
- Frontend: Blade templates, HTML, CSS, Javascript
- Collaboration software: Git and Github

## Setup & Installation

Follow the steps below to install and run the **Crown and Dial** Laravel project locally.

### **1. Clone the Repository**

```bash
git clone https://github.com/ChrisB200/crown-and-dial
cd crown-and-dial
```

---

### **2. Install PHP & Laravel Dependencies**

The project requires **PHP 8.4**. You can install PHP using the official installer script:

#### **macOS**

```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.4)"
```

#### **Windows (PowerShell as Administrator)**

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```

#### **Linux**

```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"
```

---

### **3. Ensure Node.js Is Installed**

Check npm installation:

```bash
npm -v
```

If missing, install Node.js from: https://nodejs.org/

---

### **4. Run Automatic Project Setup**

This command generates your `.env`, app key, and database:

```bash
php artisan project:setup
```

---

### **5. Install Frontend Dependencies**

Run this once:

```bash
npm install
```

---

### **6. Start the Development Servers**

Use **two terminals**:

#### **Terminal 1 – Vite**

```bash
npm run dev
```

#### **Terminal 2 – Laravel**

```bash
php artisan serve
```

Your application will now be running locally.

## Setup
