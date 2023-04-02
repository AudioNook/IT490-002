# **AudioNook**: An Online Digital Collection and Trading Platform
**AudioNook** is an online platform for digital collection keeping and trading physical formats of Music. It is is built on [Discog's API](https://www.discogs.com/developers). This project is an a *PHP* implementation of *RabbitMQ* and *PHP's AMQP protocol*.
## Getting Started
- 
### Setting Up
-

## Testing
-
## Composer Packages
[php-amqlib](https://github.com/php-amqplib/php-amqplib)

[php-jwt](https://github.com/firebase/php-jwt)
## Project Milestones
- Midterm Milestone
- Final Milestone
## Project Milestone Tasks
- [Midterm Documentation Tasks](https://github.com/orgs/AudioNook/projects/1)
- [Final Documenation Tasks](https://github.com/orgs/AudioNook/projects/2)
## Project Documentation
- [AudioNooks Wiki](#)
## Server Architecture
- <insert_image>
## Hierarchy
<details>
    <summary>File Structure</summary>
    <p>
    <pre>
    AudioNook/IT-490/
    ├── Frontend/  -- Frontend Instance
    │   ├── lib/
    │   │   └── frontend relavant functions and classess
    │   ├── partials/
    │   ├── public/
    │   │   ├── publicly accesible pages
    │   │   ├── css/ 
    │   │   └── js/
    │   ├── RabbitMQ/
    │   └── logs/
    ├── Database/  -- Database Instance
    │   ├── lib/
    │   │   ├── config.php
    │   │   └── db.php, .env, etc.
    │   ├── sql/ -- .sql files and init_db.php script
    │   ├── logs/
    │   └── db_listner.php  -- listens for DB Requests (e.g., 'login')
    ├── Rabbitmq/
    │   ├── lib/
    │   │   ├── rabbitMQLib.php
    │   │   └── rabbitmq_config.php
    │   └── logs/
    └── DMZ/  -- DMZ Instance
        ├── lib/ -- API class and functions utilizing custom Curl class
        │   └── certs/ -- for curl
        ├── logs/
        └── dmz_listner.php -- listens for DMZ Requests (e.g., 'search')
    </pre>
    </p>
</details>

## Contributors
[Carlos Segarra](https://github.com/Carlomos7)

[Jaylin Cabrera](https://github.com/jaylincabrera10)

[Luanda Silva](https://github.com/LuandaS)

[John Pearson](https://github.com/jmpearson135)
