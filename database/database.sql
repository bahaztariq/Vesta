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
    FOREIGN KEY (userID) REFERENCES Users(id),
    FOREIGN KEY (logementID) REFERENCES logements(id)
)

create table if not exists favourite(
    id int PRIMARY KEY AUTO_INCREMENT,
    userID int NOT NULL,
    logementID int NOT NULL
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

INSERT INTO Users (FirstName, LastName, UserName, Email, Password, roles, Avatar) VALUES 
('Yassine', 'Benali', 'yassine_admin', 'yassine@example.com', 'hashed_pass_01', 'Admin', 'uploads/avatars/yassine.jpg'),
('Sarah', 'Mansouri', 'sarah_host', 'sarah@example.com', 'hashed_pass_02', 'Hote', 'uploads/avatars/sarah.png'),
('Karim', 'Tazi', 'karim_travels', 'karim@example.com', 'hashed_pass_03', 'Voyageur', NULL),
('Fatima', 'Zahra', 'fati_z', 'fatima@example.com', 'hashed_pass_04', DEFAULT, 'uploads/avatars/flower.jpg'),
('Omar', 'Draoui', 'omar_d', 'omar@example.com', 'hashed_pass_05', 'Hote', NULL);

INSERT INTO logements (HoteID, name, country, city, price, imgPath, description, guestNum) VALUES
(2, 'Dar Bouazza Villa', 'Morocco', 'Casablanca', 1500.00, 'uploads/Rooms/villa_darb.jpg', 'Beautiful villa with pool near the beach.', 8),
(2, 'Cozy Apartment Agdal', 'Morocco', 'Rabat', 450.00, 'uploads/Rooms/apt_agdal.jpg', 'Modern apartment in the heart of Agdal.', 4),
(5, 'Riad authentic', 'Morocco', 'Marrakech', 800.00, 'uploads/Rooms/riad_mkech.jpg', 'Traditional Riad experience.', 6),
(5, 'Taghazout Surf House', 'Morocco', 'Taghazout', 300.00, 'uploads/Rooms/surf_house.jpg', 'Perfect for surfers, sea view.', 3);

INSERT INTO reservations (userID, logementID, startDate, endDate) VALUES
(3, 1, '2025-06-01', '2025-06-07'),
(4, 2, '2025-07-10', '2025-07-15'),
(3, 3, '2025-08-20', '2025-08-25');

INSERT INTO favourite (userID, logementID) VALUES
(3, 2),
(4, 1),
(4, 3);

INSERT INTO reviews (userID, logementID, comment, rating) VALUES
(3, 1, 'Amazing stay, huge pool!', 5),
(4, 2, 'Great location but a bit noisy.', 4);

INSERT INTO reclamations (userID, logementID, message) VALUES
(3, 1, 'The wifi was not working on the first day.');

INSERT INTO messages (senderID, receiverID, message) VALUES
(3, 2, 'Hi, is the pool heated?'),
(2, 3, 'Hello, yes it is heated during winter months.');

INSERT INTO notifications (userID, message) VALUES
(2, 'You have a new reservation for Dar Bouazza Villa.'),
(3, 'Your reservation for Dar Bouazza Villa is confirmed.');

INSERT INTO system_logs (log_level, message) VALUES
('INFO', 'System backup completed successfully.'),
('WARNING', 'High memory usage detected.');