-- Create the database
CREATE DATABASE projectflight1;
USE projectflight1;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('user', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the flights table
CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    departure_place VARCHAR(50) NOT NULL,
    destination VARCHAR(50) NOT NULL,
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    arrival_date DATE NOT NULL,
    arrival_time TIME NOT NULL,
    first_class_passengers INT DEFAULT 0,
    first_class_price DECIMAL(10, 2) DEFAULT 0.00,
    business_class_passengers INT DEFAULT 0,
    business_class_price DECIMAL(10, 2) DEFAULT 0.00,
    economy_class_passengers INT DEFAULT 0,
    economy_class_price DECIMAL(10, 2) DEFAULT 0.00
);

-- Insert sample data into flights table
INSERT INTO flights (flight_number, departure_place, destination, departure_date, departure_time, arrival_date, arrival_time, first_class_passengers, first_class_price, business_class_passengers, business_class_price, economy_class_passengers, economy_class_price) VALUES
('AI101', 'Mumbai', 'Delhi', '2024-08-25', '09:00:00', '2024-08-25', '11:00:00', 5, 5000.00, 10, 3000.00, 50, 1500.00),
('AI102', 'Delhi', 'Mumbai', '2024-08-26', '15:00:00', '2024-08-26', '17:00:00', 4, 5000.00, 8, 3000.00, 40, 1500.00),
('AI103', 'Bangalore', 'Chennai', '2024-08-27', '07:00:00', '2024-08-27', '08:00:00', 3, 4000.00, 6, 2500.00, 30, 1200.00),
('AI104', 'Chennai', 'Bangalore', '2024-08-28', '19:00:00', '2024-08-28', '20:00:00', 3, 4000.00, 6, 2500.00, 30, 1200.00),
('AI105', 'Kolkata', 'Delhi', '2024-08-29', '10:00:00', '2024-08-29', '12:00:00', 6, 5500.00, 12, 3200.00, 60, 1600.00),
('AI106', 'Delhi', 'Kolkata', '2024-08-30', '13:00:00', '2024-08-30', '15:00:00', 6, 5500.00, 12, 3200.00, 60, 1600.00),
('AI107', 'Hyderabad', 'Mumbai', '2024-08-31', '11:00:00', '2024-08-31', '13:00:00', 4, 4200.00, 8, 2700.00, 50, 1400.00),
('AI108', 'Mumbai', 'Hyderabad', '2024-08-25', '17:00:00', '2024-08-25', '19:00:00', 4, 4200.00, 8, 2700.00, 50, 1400.00),
('AI109', 'Delhi', 'Bangalore', '2024-08-26', '08:00:00', '2024-08-26', '10:30:00', 5, 4500.00, 10, 2800.00, 55, 1300.00),
('AI110', 'Bangalore', 'Delhi', '2024-08-27', '16:00:00', '2024-08-27', '18:30:00', 5, 4500.00, 10, 2800.00, 55, 1300.00),
('AI111', 'Chennai', 'Kolkata', '2024-08-28', '09:00:00', '2024-08-28', '11:30:00', 4, 4700.00, 8, 2900.00, 45, 1350.00),
('AI112', 'Kolkata', 'Chennai', '2024-08-29', '14:00:00', '2024-08-29', '16:30:00', 4, 4700.00, 8, 2900.00, 45, 1350.00),
('AI113', 'Hyderabad', 'Delhi', '2024-08-30', '06:00:00', '2024-08-30', '08:30:00', 5, 4900.00, 10, 3000.00, 50, 1400.00),
('AI114', 'Delhi', 'Hyderabad', '2024-08-31', '18:00:00', '2024-08-31', '20:30:00', 5, 4900.00, 10, 3000.00, 50, 1400.00),
('AI115', 'Mumbai', 'Chennai', '2024-08-25', '12:00:00', '2024-08-25', '14:30:00', 3, 4000.00, 6, 2500.00, 30, 1200.00),
('AI116', 'Chennai', 'Mumbai', '2024-08-26', '20:00:00', '2024-08-26', '22:30:00', 3, 4000.00, 6, 2500.00, 30, 1200.00),
('AI117', 'Delhi', 'Kolkata', '2024-08-27', '10:00:00', '2024-08-27', '12:30:00', 6, 5000.00, 12, 3000.00, 60, 1500.00),
('AI118', 'Kolkata', 'Delhi', '2024-08-28', '15:00:00', '2024-08-28', '17:30:00', 6, 5000.00, 12, 3000.00, 60, 1500.00),
('AI119', 'Bangalore', 'Hyderabad', '2024-08-29', '11:00:00', '2024-08-29', '12:00:00', 3, 3800.00, 6, 2400.00, 30, 1100.00),
('AI120', 'Hyderabad', 'Bangalore', '2024-08-30', '17:00:00', '2024-08-30', '18:00:00', 3, 3800.00, 6, 2400.00, 30, 1100.00),
('AI121', 'Chennai', 'Delhi', '2024-08-31', '13:00:00', '2024-08-31', '15:30:00', 5, 4600.00, 10, 2700.00, 50, 1300.00),
('AI122', 'Delhi', 'Chennai', '2024-08-25', '07:00:00', '2024-08-25', '09:30:00', 5, 4600.00, 10, 2700.00, 50, 1300.00),
('AI123', 'Mumbai', 'Kolkata', '2024-08-26', '08:00:00', '2024-08-26', '10:30:00', 4, 4300.00, 8, 2600.00, 45, 1250.00),
('AI124', 'Kolkata', 'Mumbai', '2024-08-27', '14:00:00', '2024-08-27', '16:30:00', 4, 4300.00, 8, 2600.00, 45, 1250.00),
('AI125', 'Delhi', 'Hyderabad', '2024-08-28', '11:00:00', '2024-08-28', '13:30:00', 6, 4700.00, 12, 2900.00, 55, 1400.00);

-- Create the bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    flight_id INT NOT NULL,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    number_of_passengers INT NOT NULL,
    class ENUM('Economy', 'Business', 'First') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);

-- Create the passengers table
CREATE TABLE passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Insert sample users data
INSERT INTO users (username, password, email, role) VALUES
('user1', PASSWORD('userpass'), 'user1@example.com', 'user'),
('admin1', PASSWORD('adminpass'), 'admin1@example.com', 'admin');

-- Alter the flights table to add generic price and class columns
ALTER TABLE flights
ADD COLUMN class ENUM('Economy', 'Business', 'First') AFTER arrival_time,
ADD COLUMN price DECIMAL(10, 2) AFTER class;

-- Table to store deleted flights
CREATE TABLE deleted_flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(255),
    departure_place VARCHAR(255),
    destination VARCHAR(255),
    departure_date DATE,
    departure_time TIME,
    arrival_date DATE,
    arrival_time TIME,
    class VARCHAR(50),
    price DECIMAL(10, 2),
    first_class_passengers INT,
    first_class_price DECIMAL(10, 2),
    business_class_passengers INT,
    business_class_price DECIMAL(10, 2),
    economy_class_passengers INT,
    economy_class_price DECIMAL(10, 2)
);

-- Table to store passengers of deleted flights
CREATE TABLE deleted_passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deleted_flight_id INT,
    name VARCHAR(100),
    email VARCHAR(100),
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    FOREIGN KEY (deleted_flight_id) REFERENCES deleted_flights(id)
);

CREATE TABLE edited_flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(50) NOT NULL,
    departure_place VARCHAR(100),
    destination VARCHAR(100),
    departure_date DATE,
    departure_time TIME,
    edited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE edited_passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    edited_flight_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    FOREIGN KEY (edited_flight_id) REFERENCES edited_flights(id) ON DELETE CASCADE
);

ALTER TABLE bookings ADD total_price DECIMAL(10, 2);
ALTER TABLE bookings ADD payment_status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending';
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('Pending', 'Completed', 'Failed') DEFAULT 'Pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    transaction_id VARCHAR(255) UNIQUE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
INSERT INTO flights (flight_number, departure_place, destination, departure_date, departure_time, arrival_date, arrival_time, first_class_passengers, first_class_price, business_class_passengers, business_class_price, economy_class_passengers, economy_class_price)
VALUES 
('AI124', 'Mumbai', 'Delhi', '2024-08-25', '09:30:00', '2024-08-25', '11:30:00', 5, 5000.00, 10, 3000.00, 50, 1500.00),
('AI125', 'Delhi', 'Mumbai', '2024-08-26', '15:30:00', '2024-08-26', '17:30:00', 4, 5000.00, 8, 3000.00, 40, 1500.00),
('AI126', 'Bangalore', 'Chennai', '2024-08-27', '07:30:00', '2024-08-27', '08:30:00', 3, 4000.00, 6, 2500.00, 30, 1200.00),
('AI127', 'Chennai', 'Bangalore', '2024-08-28', '19:30:00', '2024-08-28', '20:30:00', 3, 4000.00, 6, 2500.00, 30, 1200.00);

ALTER TABLE edited_flights
    ADD COLUMN arrival_date DATE NOT NULL,
    ADD COLUMN arrival_time TIME NOT NULL,
    ADD COLUMN first_class_price DECIMAL(10, 2) NOT NULL,
    ADD COLUMN business_class_price DECIMAL(10, 2) NOT NULL,
    ADD COLUMN economy_class_price DECIMAL(10, 2) NOT NULL,
    ADD COLUMN first_class_passengers INT NOT NULL,
    ADD COLUMN business_class_passengers INT NOT NULL,
    ADD COLUMN economy_class_passengers INT NOT NULL;

ALTER TABLE bookings ADD ticket_number VARCHAR(20) NOT NULL;
ALTER TABLE passengers
ADD COLUMN ticket_number VARCHAR(50);
ALTER TABLE deleted_passengers ADD COLUMN ticket_number VARCHAR(20);
ALTER TABLE edited_passengers ADD COLUMN ticket_number VARCHAR(20);

UPDATE bookings b
JOIN flights f ON b.flight_id = f.id
SET b.total_price = CASE b.class
    WHEN 'first_class' THEN f.first_class_price * b.number_of_passengers
    WHEN 'business_class' THEN f.business_class_price * b.number_of_passengers
    WHEN 'economy_class' THEN f.economy_class_price * b.number_of_passengers
    ELSE 0
END;
