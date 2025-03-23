# MIM Traders Online Store

## Overview
MIM Traders Online Store is a comprehensive e-commerce platform designed to provide a seamless shopping experience for customers while ensuring efficient inventory and order management for administrators.

## Key Features

### Website Features
- Fully functional website with separate portals for administrators and customers.
- Dynamic and interactive navigation system for easy access to sections like Home, Products, Shopping Cart, Orders, Customer Reviews, and Contact Support.
- Mobile-responsive design for accessibility across different devices.

### User Management
- Secure login credentials for both admins and customers.
- Role-based access control to prevent unauthorized modifications to sensitive data.
- Strong password encryption and authentication mechanisms for enhanced security.

### Inventory and Order Processing
- Real-time updates for product inventory, stock availability, and order tracking.
- Admin functionalities for confirming orders, processing refunds, assigning tracking numbers, and managing stock.
- Customers can view order status updates from checkout to delivery.

### Security Features
- Secure transaction handling and customer payment details using SSL encryption and secure payment gateways.
- Compliance with GDPR and other data protection regulations to ensure confidentiality.
- Multi-layer authentication for admin accounts to prevent unauthorized access.

## Special Features and Functions
To enhance user experience and differentiate MIM Traders Online Store from competitors, the platform includes the following unique features:

- **Product Comparison Tool**: Allows customers to compare multiple products side by side based on price, features, and specifications.
- **Live Chat Support**: Provides real-time assistance for customer inquiries, improving response time and customer satisfaction.
- **Printable Invoice with QR Code**: Generates downloadable and printable invoices with embedded QR codes for easy returns or inquiries.
- **Delivery Tracking System**: Customers can track the live status of their orders from dispatch to delivery.
- **Bulk Order Discount System**: Special pricing and discount structures for customers who order in bulk.
- **Wishlist Feature**: Customers can save products to their wishlist for future purchases.
- **Customer Loyalty Program**: Reward points for frequent purchases, which can be redeemed for discounts or free items.
- **Automated Restock Notifications**: Customers can opt-in to receive notifications when out-of-stock items become available.

## Installation
1. Download and install [XAMPP](https://www.apachefriends.org/index.html).
2. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/mim-traders.git
   ```
3. Move all files to the XAMPP htdocs directory:
   ```bash
   mv mim-traders /xampp/htdocs/
   ```
4. Start Apache and MySQL using the XAMPP control panel.
5. Create a database in MySQL using the provided database file:
   ```sql
   CREATE DATABASE mim_traders;
   USE mim_traders;
   SOURCE database_file.sql;
   ```
6. Open the project in a browser:
   - User Portal: [http://localhost:8012/hardwareshop/home.php](http://localhost:8012/hardwareshop/home.php)
   - Admin Portal: [http://localhost:8012/hardwareshop/admin/dashboard.php](http://localhost:8012/hardwareshop/admin/dashboard.php)

## Default Login Credentials
- **User Login:**
  - Email: `user1@gmail.com`
  - Password: `1234` (default, changeable)
- **Admin Login:**
  - Username: `admin`
  - Password: `111` (default, changeable)

## Contribution
Contributions are welcome! Please submit a pull request or open an issue to report bugs or suggest new features.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

### Contributing
Pull requests are welcomed. For major changes, please open an issue first to discuss what you would like to change. Thanks!

Happy Coding!!!

### Copyright
© KAVINDU™ | 2025

