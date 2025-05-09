# Flight Booking Website - Ganesh Travels

## Overview
This is a flight booking website designed for Ganesh Travels. The website allows users to search for flights, book tickets, and process payments. Admins can manage flights, view bookings, and handle various administrative tasks. The website provides a user-friendly interface for both customers and administrators, with features like login/registration, payment processing, and flight management.

## Features

### User Features:
- **Flight Search**: Users can search for flights based on departure place, destination, and date.
- **Flight Booking**: Users can book flights, select the class (economy, business, or first class), and add passenger details.
- **Payment Gateway**: A payment gateway (fake for testing) allows users to make payments for their bookings.
- **Booking Confirmation**: After payment, users receive a ticket confirmation with flight details.
- **View Bookings**: Users can view their booking details after logging in.

### Admin Features:
- **Admin Login & Registration**: Admins can log in or register to access the admin dashboard.
- **Manage Flights**: Admins can add, update, and delete flights.
- **View Bookings**: Admins can view all user bookings, passenger details, and delete unwanted bookings.
- **Manage Users**: Admins can update user data and manage their access to the platform.

### Customization:
- **Background Images**: The website includes background images for various pages, such as the admin login page and booking pages.
- **Interactive UI**: Hover effects and smooth transitions are applied to buttons and links to enhance user experience.

## Technologies Used
- **HTML/CSS**: For the structure and styling of the website.
- **PHP**: For server-side logic, processing bookings, handling flights, and managing user/admin operations.
- **MySQL**: For storing flight details, bookings, users, and passenger data.
- **JavaScript**: For handling dynamic actions like form submissions, search results, and payment processing.
- **Payment Gateway**: Fake payment gateway for testing payment functionality.

## File Structure
# flight_ticket_booking_website

- `add_flight.php`
- `admin_dashboard.php`
- `admin_forgot_password.php`
- `admin_home.php`
- `admin_login.php`
- `admin_register.php`
- `admin_reset_password.php`
- `booking_confirmation.php`
- `booking_details.php`
- `contact.php`
- `db_config.php`
- `deleted_flights.php`
- `delete_flight.php`
- `edited_flights.php`
- `edit_flight.php`
- `edit_passenger.php`
- `forgot_password.php`
- `image1.jpg`
- `index.php`
- `login.php`
- `logout.php`
- `passenger_details.php`
- `plane.mp4`
- `process_booking.php`
- `register.php`
- `reset_password.php`
- `search_flights.php`
- `select_flight.php`
- `sql/`
  - `flight_booking_website.sql`
- `terms.php`
- `ticket_details.php`
- `tour.jpg`
- `tour1.png`
- `update_user.php`
- `user_dashboard.php`
- `view_deleted_passengers.php`
- `view_edited_passengers.php`
- `view_passengers.php`
- `view_ticket.php`
- `view_tickets.php`



## Setup Instructions

### Requirements:
- Web server like **XAMPP** or **WAMP** for running PHP.
- A MySQL database to store flight, user, and booking information.

### Installation Steps:
1. Clone or download the repository to your local machine.
2. Set up a local web server (e.g., XAMPP) and place the project in the `htdocs` folder.
3. Create a MySQL database and import the SQL structure for the required tables (flights, bookings, users, passengers).
4. Update the database connection details in `database.php` to match your local MySQL credentials.
5. Open the `index.php` file in your browser and start using the website.

### Configuration:
1. Set up the **admin login** credentials in the `admin_login.php` file (for testing purposes, you can add a default admin user).
2. Customize the **background images** and **CSS** styles as needed.
3. For the **payment page**, you can replace the fake payment gateway with a real one when moving to production.

## How It Works

1. **User Interaction**:
   - Users can search for flights based on departure and destination.
   - After selecting a flight, users can input passenger details and proceed to the payment page.
   - After payment, users receive a confirmation email with their flight details and a PDF ticket.

2. **Admin Interaction**:
   - Admins can log in to manage flights and view all bookings.
   - Admins can add new flights, update flight details, and view deleted flights.

3. **Payment**:
   - The website uses a fake payment gateway for testing. Upon successful payment, the system generates a QR code and prints the booking ticket.

## Screenshots

- **Home Page**: ![Home Page Screenshot](./images/home_page.png)
- **Admin Dashboard**: ![Admin Dashboard Screenshot](./images/admin_dashboard.png)
- **Booking Page**: ![Booking Page Screenshot](./images/booking_page.png)

## Contributing
Feel free to fork and modify the project as needed. If you encounter any issues or have suggestions for improvements, please submit an issue or pull request.

## Future Enhancements
- Add a **real payment gateway** (e.g., Stripe, PayPal).
- Implement **user authentication** for saving booking history.
- Allow **flight rating and reviews** by users.
- Add **email notifications** for booking confirmations and cancellations.


