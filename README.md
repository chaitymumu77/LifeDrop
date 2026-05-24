# 🩸 LifeDrop - Blood Management System

LifeDrop is a comprehensive and user-friendly web-based Blood Management System designed to bridge the gap between blood donors and recipients. The platform facilitates emergency blood requests, maintains a verified database of donors, and provides an intuitive administrative dashboard to manage the entire process seamlessly.

## 🌟 Key Features

### For Users / Donors
* **Quick Donor Search**: Efficiently find matching blood donors based on blood group and location.
* **Emergency Requests**: Quickly submit emergency blood requests to notify available donors.
* **Donor Registration**: Seamless registration process to become a donor and save lives.
* **User Profile**: Donors can update their profiles and manage their availability status.
* **Notice Board**: Stay updated with real-time emergency alerts and community announcements.

### For Administrators
* **Manage Donors**: Admin can review, verify, update, or remove donor profiles to ensure data reliability.
* **Manage Requests**: Track and handle incoming blood requests efficiently.
* **Admin Dashboard**: A centralized portal to monitor system activity and manage users.

## 🛠️ Technology Stack

**Frontend:**
* **HTML5:** Used within PHP files to structure the web pages.
* **CSS3 & Bootstrap 5:** Implemented for modern styling, animations, and ensuring the UI is fully responsive across all devices (Custom styles in `css/style.css`).
* **JavaScript & jQuery:** Handles client-side interactivity, form validation, and asynchronous requests (AJAX) to improve user experience without reloading pages (`js/app.js`, `js/crud.js`).

**Backend & Database:**
* **PHP (Core):** Server-side logic, session management, and routing.
* **MySQL:** Relational database to store donors, requests, and admin records securely.

## 🚀 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/lifedrop.git
   ```
2. **Move to Server Directory:** 
   Place the project folder inside your local server's root directory (e.g., `htdocs` for XAMPP or `www` for WAMP).
3. **Database Configuration:**
   * Create a new MySQL database named `blood_management` (or your preferred name).
   * Import the provided `.sql` database file into your newly created database.
   * Update the database connection credentials in your PHP configuration file.
4. **Run the Application:**
   Open your browser and navigate to `http://localhost/lifedrop` (or whatever you named the folder).

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the issues page.

## 📄 License

This project is licensed under the MIT License.

---
*“Your one drop of blood can give someone a second chance at life.”* ❤️
