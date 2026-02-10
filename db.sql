-- DATABASE
CREATE DATABASE IF NOT EXISTS stock_management;
USE stock_management;

-- CATEGORY TABLE
CREATE TABLE Category (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    description TEXT
);

-- SUPPLIER TABLE
CREATE TABLE Supplier (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255)
);

-- PRODUCT TABLE
CREATE TABLE Product (
    prod_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    cat_id INT,
    supplier_id INT,
    FOREIGN KEY (cat_id) REFERENCES Category(cat_id),
    FOREIGN KEY (supplier_id) REFERENCES Supplier(supplier_id)
);

-- ADMIN TABLE
CREATE TABLE Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    password VARCHAR(255),
    phone VARCHAR(20)
);

-- STOCK TRANSACTION TABLE
CREATE TABLE Stock_Transaction (
    trans_id INT AUTO_INCREMENT PRIMARY KEY,
    trans_type VARCHAR(10),
    quantity INT,
    trans_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    prod_id INT,
    admin_id INT,
    FOREIGN KEY (prod_id) REFERENCES Product(prod_id),
    FOREIGN KEY (admin_id) REFERENCES Admin(admin_id)
);


