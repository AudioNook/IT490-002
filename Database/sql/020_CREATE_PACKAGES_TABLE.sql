/* old packages table
CREATE TABLE IF NOT EXISTS Packages(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    version_id INT NOT NULL,
    environment ENUM('dev', 'qa', 'prod') NOT NULL,
    package_type ENUM('fe', 'be', 'db', 'dmz') NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (version_id) REFERENCES Versions(id)
);*/