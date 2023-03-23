#!/bin/sh
gnome-terminal --  php rabbitMQserver.php & 
gnome-terminal  -- php jwtServer.php &
gnome-terminal  -- php onLoadServer.php
