
CREATE DATABASE IF NOT EXISTS analytics_optimized;
USE analytics_optimized;

CREATE TABLE urls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url_path VARCHAR(1024) NOT NULL,
    UNIQUE INDEX idx_url_path (url_path(255))
) ENGINE=InnoDB;

CREATE TABLE pv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    url_id INT NOT NULL,
    country CHAR(2) NOT NULL, 
    device ENUM('desktop', 'mobile', 'tablet') NOT NULL, 
    created_at DATETIME NOT NULL, 
    duration_ms INT NOT NULL,
    INDEX idx_report (country, created_at, url_id, user_id),
    FOREIGN KEY (url_id) REFERENCES urls(id)
) ENGINE=InnoDB;

SELECT u.url_path, COUNT(DISTINCT p.user_id) as unique_users 
FROM pv p
JOIN urls u ON p.url_id = u.id
WHERE p.country = 'UA' 
  AND p.created_at BETWEEN '2025-09-01 00:00:00' AND '2025-09-30 23:59:59'
GROUP BY p.url_id 
ORDER BY unique_users DESC 
LIMIT 5;

START TRANSACTION;
INSERT INTO pv (user_id, url_id, country, device, created_at, duration_ms) 
VALUES 
(1, 1, 'UA', 'desktop', '2025-09-15 10:11:12', 350),
(2, 1, 'UA', 'mobile', '2025-09-15 11:01:02', 900),
(1, 2, 'PL', 'mobile', '2025-09-16 08:21:00', 120);
COMMIT;