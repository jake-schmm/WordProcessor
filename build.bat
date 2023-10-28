cd C:\xampp\mysql\bin
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS wordprocessordb;"
mysql -u root -p wordprocessordb < C:\xampp\htdocs\swe-6623-word-processor\wordprocessordb.sql