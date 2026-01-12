CREATE DATABASE IF NOT EXISTS Vesta;

USE Vesta;


CREATE TABLE IF NOT EXISTS Users(
    id int PRIMARY KEY AUTO_INCREMENT,
    FirstName varchar(255) NOT NULL,
    LastName varchar(255) NOT NULL,
    UserName varchar(255) UNIQUE NOT NULL,
    Email varchar(255) UNIQUE NOT NULL,
    Password varchar(255) NOT NULL,
    roles ENUM('Voyageur', 'Admin', 'Hote') DEFAULT 'Voyageur',
    Avatar varchar(255)
)


create table if not exists logements(
    id int PRIMARY KEY AUTO_INCREMENT,
    HoteID int NOT NULL,
    name varchar(255) NOT NULL,
    country varchar(255) NOT NULL,
    city varchar(255) NOT NULL,
    price DECIMAL (8,2),
    imgPath VARCHAR(255),
    description VARCHAR(1000) not null,
    guestNum int not NULL,
    FOREIGN KEY (HoteID) REFERENCES Users(id)

)



create table if not exists reservations(
    id int PRIMARY KEY AUTO_INCREMENT,
    userID int NOT NULL,
    logementID int NOT NULL,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (userID) REFERENCES Users(id),
    FOREIGN KEY (logementID) REFERENCES logements(id)
)

create table if not exists favourite(
    id int PRIMARY KEY AUTO_INCREMENT,
    userID int NOT NULL,
    logementID int NOT NULL,
    FOREIGN KEY (userID) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (logementID) REFERENCES logements(id) ON DELETE CASCADE
)

create table if not exists reviews(
    id int PRIMARY KEY AUTO_INCREMENT,
    userID int NOT NULL,
    logementID int NOT NULL,
    comment VARCHAR(1000) NOT NULL,
    rating int NOT NULL,
    date_ DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(id),
    FOREIGN KEY (logementID) REFERENCES logements(id)
)

create table if not exists reclamations(
    id int PRIMARY KEY AUTO_INCREMENT,
    userID int NOT NULL,
    logementID int NOT NULL,
    message VARCHAR(1000) NOT NULL,
    FOREIGN KEY (userID) REFERENCES Users(id),
    FOREIGN KEY (logementID) REFERENCES logements(id)
)

create table if not exists messages(
    id int PRIMARY KEY AUTO_INCREMENT,
    senderID int NOT NULL,
    receiverID int NOT NULL,
    message VARCHAR(1000) NOT NULL,
    FOREIGN KEY (senderID) REFERENCES Users(id),
    FOREIGN KEY (receiverID) REFERENCES Users(id)
)

create table if not exists notifications(
    id int PRIMARY KEY AUTO_INCREMENT,
    userID int NOT NULL,
    message VARCHAR(1000) NOT NULL,
    date DATE DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(id)
)

create table if not exists system_logs(
    id int PRIMARY KEY AUTO_INCREMENT,
    log_level VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
USE Vesta;

INSERT INTO Users (FirstName, LastName, UserName, Email, Password, roles, Avatar) VALUES 
('Alice', 'Admin', 'alice_admin', 'alice@vesta.com', 'hashed_pass_001', 'Admin', 'avatar_admin.png'),
('Bob', 'Builder', 'bob_host', 'bob@host.com', 'hashed_pass_002', 'Hote', 'avatar_bob.png'),
('Charlie', 'Chef', 'charlie_host', 'charlie@host.com', 'hashed_pass_003', 'Hote', 'avatar_charlie.png'),
('David', 'Doe', 'dave_traveler', 'david@gmail.com', 'hashed_pass_004', 'Voyageur', 'avatar_dave.png'),
('Eve', 'Explorer', 'eve_traveler', 'eve@yahoo.com', 'hashed_pass_005', 'Voyageur', 'avatar_eve.png');

INSERT INTO logements (HoteID, name, country, city, price, imgPath, description, guestNum) VALUES 
(2, 'Cozy Paris Studio', 'France', 'Paris', 85.00, 'paris_studio.jpg', 'A small but beautiful studio near the Eiffel Tower.', 2),
(2, 'Sunny Villa', 'Spain', 'Barcelona', 250.00, 'bcn_villa.jpg', 'Large villa with a pool and sea view.', 6),
(3, 'NYC Loft', 'USA', 'New York', 180.00, 'nyc_loft.jpg', 'Modern loft in the heart of Brooklyn.', 4),
(3, 'Kyoto Traditional House', 'Japan', 'Kyoto', 120.00, 'kyoto_house.jpg', 'Experience traditional living.', 3);
INSERT INTO reservations (userID, logementID, startDate, endDate, status) VALUES 
(3, 3, '2023-11-01', '2023-11-05', 'confirmed'), -- David in Paris
(3, 3, '2023-12-10', '2023-12-15', 'pending'),   -- David in NYC
(9, 6, '2023-11-20', '2023-11-27', 'confirmed'), -- Eve in Barcelona
(9, 4, '2024-01-05', '2024-01-10', 'cancelled');

INSERT INTO favourite (userID, logementID) VALUES 
(3, 6), -- David likes the Villa in Barcelona
(3, 3), -- Eve likes the Studio in Paris
(3, 3);


INSERT INTO reviews (userID, logementID, comment, rating, date_) VALUES 
(3, 3, 'Great location, but a bit noisy at night.', 4, '2023-11-06 10:00:00'),
(3, 4, 'Absolutely amazing place! The pool was fantastic.', 5, '2023-11-28 14:30:00');

INSERT INTO reclamations (userID, logementID, message) VALUES 
(11, 3, 'The wifi listed in the description was not working.'),
(9, 4, 'I requested a refund for my cancellation but haven\'t received it yet.'); -- Eve likes the Loft in NYC -- Eve in Kyoto
