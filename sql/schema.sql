-- Create Users table first to establish user_id primary key
CREATE TABLE Users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Now create Customers table
CREATE TABLE Customers (
    customer_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15),
    address VARCHAR(255),
    created_by INT UNSIGNED,  -- User ID of the person who created this customer
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT UNSIGNED,  -- User ID of the person who last updated this customer
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign key to link creator and updater to the Users table
    CONSTRAINT fk_created_by FOREIGN KEY (created_by) REFERENCES Users(user_id) ON DELETE SET NULL,
    CONSTRAINT fk_updated_by FOREIGN KEY (updated_by) REFERENCES Users(user_id) ON DELETE SET NULL
);

-- Finally, create Orders table
CREATE TABLE Orders (
    order_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED,
    order_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    shipping_address VARCHAR(255),

    -- Foreign key to establish the one-to-many relationship
    CONSTRAINT fk_customer FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE
);

INSERT INTO Users (username, email, password)
VALUES 
('jdoe', 'jdoe@example.com', 'password123'),
('asmith', 'asmith@example.com', 'password123'),
('bjohnson', 'bjohnson@example.com', 'password123'),
('mwilson', 'mwilson@example.com', 'password123'),
('kclark', 'kclark@example.com', 'password123');

INSERT INTO Customers (first_name, last_name, email, phone_number, address, created_by, updated_by)
VALUES 
('John', 'Doe', 'johndoe@example.com', '123-456-7890', '123 Main St, Springfield', 1, 1),
('Alice', 'Smith', 'alicesmith@example.com', '234-567-8901', '456 Oak St, Springfield', 2, 2),
('Bob', 'Johnson', 'bobjohnson@example.com', '345-678-9012', '789 Pine St, Springfield', 3, 3),
('Megan', 'Wilson', 'meganwilson@example.com', '456-789-0123', '321 Maple St, Springfield', 4, 4),
('Karen', 'Clark', 'karenclark@example.com', '567-890-1234', '654 Cedar St, Springfield', 5, 5);
