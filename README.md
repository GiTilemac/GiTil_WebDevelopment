# GiTil_WebDevelopment
Web Development University Project

*GiTil_WebDevelopment is licensed under the GNU General Public License v3.0*

Computer Engineering and Informatics Department  
University of Patras  
Winter Semester 2015  
Web Programming and Systems  

#### Authors: Tilemachos S. Doganis, Georgios Katsandris

This project is a full-stack implementation of a simple website running on localhost. It consists of a database of the [Greek Government's
Atmospheric Pollution data](http://www.ypeka.gr/Default.aspx?tabid=492&language=el-GR), as well as an API and two websites for managing
and querying subsets of this data.

Prerequisites: 
 * Wampserver 3.0.6
 * Apache 2.4.23
 * PHP 5.6.15
 * MySQL 5.7.9

1. wampserver must be installed   
2. Ensure that 'wampapache' and 'wampmysql' services are not disabled
3. Place api-site in .../wamp/www folder  
  
  
   On http://localhost/phpmyadmin/:
4. Ensure that no database named 'apidb' or 'secure_login' exists  
5. Ensure that no user named 'sec_user' exists  
6. Import databases.sql  
  
7. Navigate to http://api-site.com or http://localhost  
8. Ensure that Google Maps API key is up-to-date for heatmap at http://api-site/demo-site  

Image Sources:  
api-site background: https://goodstock.photos/distant-downtown-city-buildings-in-smog/  
demo-site background: https://www.pexels.com/photo/air-air-pollution-chimney-city-221000/

## Website usage snapshots:  

![alt text](https://github.com/GiTilemac/GiTil_WebDevelopment/blob/master/Snapshots/snapshot1.jpg)
![alt text](https://github.com/GiTilemac/GiTil_WebDevelopment/blob/master/Snapshots/snapshot2.jpg)
![alt text](https://github.com/GiTilemac/GiTil_WebDevelopment/blob/master/Snapshots/snapshot3.jpg)
![alt text](https://github.com/GiTilemac/GiTil_WebDevelopment/blob/master/Snapshots/snapshot4.jpg)
