-- cPanel mysql backup
GRANT USAGE ON *.* TO 'knowled5'@'184.154.46.56' IDENTIFIED BY PASSWORD '*DD94CF33089EE9DF093B2486F153A431C1FD750D';
GRANT ALL PRIVILEGES ON `knowled5\_maindb`.* TO 'knowled5'@'184.154.46.56';
GRANT USAGE ON *.* TO 'knowled5'@'localhost' IDENTIFIED BY PASSWORD '*DD94CF33089EE9DF093B2486F153A431C1FD750D';
GRANT ALL PRIVILEGES ON `knowled5\_maindb`.* TO 'knowled5'@'localhost';
GRANT USAGE ON *.* TO 'knowled5'@'pcluster18.stablehost.com' IDENTIFIED BY PASSWORD '*DD94CF33089EE9DF093B2486F153A431C1FD750D';
GRANT ALL PRIVILEGES ON `knowled5\_maindb`.* TO 'knowled5'@'pcluster18.stablehost.com';
GRANT USAGE ON *.* TO 'knowled5_dbuser'@'184.154.46.56' IDENTIFIED BY PASSWORD '*2B659C4E1171691F48C824BCC9B142981F000901';
GRANT ALL PRIVILEGES ON `knowled5\_maindb`.* TO 'knowled5_dbuser'@'184.154.46.56';
GRANT USAGE ON *.* TO 'knowled5_dbuser'@'localhost' IDENTIFIED BY PASSWORD '*2B659C4E1171691F48C824BCC9B142981F000901';
GRANT ALL PRIVILEGES ON `knowled5\_maindb`.* TO 'knowled5_dbuser'@'localhost';
GRANT USAGE ON *.* TO 'knowled5_dbuser'@'pcluster18.stablehost.com' IDENTIFIED BY PASSWORD '*2B659C4E1171691F48C824BCC9B142981F000901';
GRANT ALL PRIVILEGES ON `knowled5\_maindb`.* TO 'knowled5_dbuser'@'pcluster18.stablehost.com';
