/* Table for Deployed packages and their versions */
CREATE TABLE IF NOT EXISTS deployments (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    environment ENUM('dev', 'qa') NOT NULL,
    package_type ENUM('db', 'fe', 'dmz') NOT NULL,
    version INT NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_deployments (environment, package_type, version)
);
