CREATE DATABASE IF NOT EXISTS zonmart DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zonmart;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  category_id INT NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT 'placeholder.png',
  featured TINYINT(1) DEFAULT 0,
  created_at DATETIME,
  FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT,
  created_at DATETIME,
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  address TEXT,
  payment_method VARCHAR(50),
  created_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample categories
INSERT INTO categories (name,slug) VALUES
('Bags','bags'),('Shoes','shoes'),('Camera','camera'),('Clothes','clothes'),
('Accessories','accessories'),('Beauty','beauty'),('Sports','sports'),('Books','books'),
('Appliances','appliances'),('Furniture','furniture'),('Baby & Kids','baby-kids'),
('Groceries','groceries'),('Watches','watches'),('Jewelry','jewelry'),('Pet Supplies','pets');

-- Sample products
INSERT INTO products (name,category_id,price,image,featured,created_at) VALUES
('iPhone',89999.00,'https://www.notebookcheck.net/fileadmin/Notebooks/News/_nc3/folable_iPhone.jpg
',1,NOW()),


-- Sample reviews
INSERT INTO reviews (product_id,user_id,rating,comment,created_at) VALUES
(1,1,5,'Excellent quality and fast delivery',NOW()),
(2,1,4,'Comfortable shoes, true to size',NOW());

-- SAMPLE USERS (password: 123456)
INSERT INTO users (name, email, password_hash, created_at) VALUES
('Tanvi Kuwar', 'kuwartanvi55@gmail.com', 
'$2y$10$9BCxgXKBC2tSlcRb93h7CeK6E3cY1v7VbBDRVtVRZ.6cHcKaRjzMS', NOW());