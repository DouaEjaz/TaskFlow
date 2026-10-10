-- TaskFlow schema (MySQL / MariaDB)
-- Create a database named taskflow_db first (utf8mb4_unicode_ci),
-- select it in phpMyAdmin, then import this file.
-- Uses CREATE TABLE IF NOT EXISTS, so it will not overwrite existing tables.

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(20) DEFAULT '#3498db'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('todo','in_progress','done') NOT NULL DEFAULT 'todo',
    priority ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
    due_date DATE,
    category_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tasks_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Starter categories (only inserted if the categories table is empty).
-- No task data is included.
INSERT INTO categories (name, color)
SELECT * FROM (
    SELECT 'Work' AS name, '#3498db' AS color
    UNION ALL SELECT 'Personal', '#2ecc71'
    UNION ALL SELECT 'Study', '#9b59b6'
    UNION ALL SELECT 'Health', '#e74c3c'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM categories);
