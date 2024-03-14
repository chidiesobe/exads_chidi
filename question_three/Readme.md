

Update the config.php files to match your database configuration

Note: Line 13 in the index.php file is automatically commented out, this line has been uncommented before submission and is only required during the first run of the script when the database and data are uploaded to your database.

#### Index.php
```php
require_once(__DIR__ . '/install/install.php');
```


### Database Configuration
Ensure the database configuration matches your local database configuration

```php
$SERVERNAME = "localhost";
$PORT = "your_port";
$USERNAME = "your_usernmae";
$PASSWORD = "your_password";
$DBNAME = "exads_chidi";

```
