# Shipping Manager

**Graduation Project**  
**Developed by Regragui Othmane**

## Overview

**Shipping Manager** is a comprehensive system designed to streamline and manage the logistics and operations of a shipping business. This application provides tools for managing branches, employees, products, shipments, and transactions, ensuring efficient and organized operations across various locations.

## Features

- **Branch Management**: Create, update, and manage branches of the shipping company.
- **Employee Management**: Assign employees to branches, manage their roles, and track their activities.
- **Product and Stock Management**: Keep track of inventory levels, manage product information, and monitor stock across branches.
- **Shipment Tracking**: Record and track shipments from origin to destination, ensuring smooth delivery processes.
- **Transaction Management**: Handle financial and logistical transactions, recording and managing every stage of the shipping process.
- **User Authentication**: Secure login and logout functionality to ensure that only authorized users can access the system.
- **Responsive UI**: A user-friendly interface with forms and dashboards to manage all aspects of the shipping process.

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/username/shipping-manager.git
   cd shipping-manager
   ```

2. **Set Up the Database:**

   - Import the `shipping_manager_db.sql` file located in the `DB` folder into your MySQL database.
   - Update the database configuration in `Shipping_Manager/database/DB.php` to match your local setup.

3. **Install Dependencies:**

   - Ensure that you have PHP and Composer installed.
   - Run `composer install` to install necessary PHP dependencies.

4. **Configure the Environment:**

   - Modify `autoload.php` and `bootstrap.php` as needed to set up your environment variables and paths.

5. **Run the Application:**
   - Deploy the project on a local server (e.g., using XAMPP, MAMP, or a similar tool).
   - Access the application via `http://localhost/shipping-manager`.

## Usage

- **Admin Dashboard**: Access the admin dashboard to manage branches, employees, products, and shipments.
- **Manage Transactions**: Track and manage all transactions related to shipping operations.
- **Track Shipments**: Use the system to monitor shipments, from departure to delivery.

## Screenshots

Screenshots of the application are available in the `images` directory to provide a visual guide to the UI and functionality.

Here are some screenshots of the Shipping Manager application:

![Home Page](images/Screenshot%202024-07-05%20at%2021-01-08%20Shipping%20Manager.png)

![Manage Branches](images/Screenshot%202024-07-05%20at%2021-28-29%20Shipping%20Manager.png)

![Manage Employees](images/Screenshot%202024-07-05%20at%2021-28-37%20Shipping%20Manager.png)

## Contributing

This project is currently not open for external contributions, as it serves as a graduation project. However, feedback and suggestions are welcome.

## License

This project is licensed under the MIT License. See the `LICENSE` file for more details.

## Acknowledgements

- Special thanks to my mentors and professors who provided guidance throughout the development of this project.
- Thanks to the open-source community for providing tools and resources that made this project possible.
