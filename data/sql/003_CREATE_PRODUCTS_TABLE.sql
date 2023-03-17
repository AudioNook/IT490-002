
CREATE TABLE IF NOT EXISTS Products(
    id int AUTO_INCREMENT PRIMARY  KEY,
    name varchar(30), 
    description text,
    category text,
    stock int DEFAULT  0,
    cost int DEFAULT  99999, -- my cost is int because I don't have regular currency; shop people may want to record it as pennies
    Image text, -- this col type can't have a default value; this isn't required for any project, I chose to add it for mine
    visibility BOOLEAN,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    check (stock >= 0), -- don't allow negative stock; I don't allow backorders
    check (cost >= 0) -- don't allow negative costs
)