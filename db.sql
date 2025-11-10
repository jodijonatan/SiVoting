-- db.sql
CREATE DATABASE voting_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE voting_db;

-- admin users (role = 'admin') and voter users (role = 'voter' but we'll also have separate voters table)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','voter') NOT NULL DEFAULT 'voter',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- voters (list maintained by admin; each voter gets a token to login)
CREATE TABLE voters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  token VARCHAR(100) NOT NULL UNIQUE, -- short login token/password
  voted TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- voting options
CREATE TABLE options (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  votes_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- votes record (audit)
CREATE TABLE votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  voter_id INT NOT NULL,
  option_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (voter_id) REFERENCES voters(id) ON DELETE CASCADE,
  FOREIGN KEY (option_id) REFERENCES options(id) ON DELETE CASCADE
);

-- create a default admin (change password after import)
INSERT INTO users (username, password, role) VALUES (
  'admin',
  -- default password "adminpass" hashed using PHP password_hash; but we'll instruct to change it.
  '$2y$10$z1y7fCqGJp1Zq0jXb8b5E.sYd8G6s6Q4Qj4Jvwm3iL1ZgFfZQ0U7a', 
  'admin'
);
