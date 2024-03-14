<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * This section is only required the first time you run the script. 
 * It creates the database file and populates it.
 * Feel free to delete it after the first run,
 * and removed the required section 
 * from the index.php file.
 */

require_once(__DIR__ . '/../db/config.php');
require_once('sql_scripts.php');

try {
    $tempConn = new PDO("mysql:host=$SERVERNAME;port=$PORT", $USERNAME, $PASSWORD);
    $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database already exists
    if (!databaseExists($tempConn, $DBNAME)) {
        createDatabase($tempConn, $DBNAME);

        echo "Database created successfully. <br> 
            If you delete the install.php file, and the 
            installation line has been commented out in the
            index.php file.";

        // Switch to the created database
        $tempConn->exec("USE $DBNAME");

        // Execute SQL scripts
        executeSqlScripts($tempConn, $sql_scripts);
    } else {
        // Switch to the created database
        $tempConn->exec("USE $DBNAME");

        // Execute SQL scripts
        executeSqlScripts($tempConn, $sql_scripts);
    }

    // Close tempConnection and remove the install script from index.php
    $tempConn = null;
    disableInstall();
} catch (PDOException $e) {
    echo "Error connecting to the database, ensure config values are correct: " . $e->getMessage();
    exit();
}

// Checks if database exist
function databaseExists($connection, $dbName): bool
{
    $checkDbExists = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'";
    $stmt = $connection->prepare($checkDbExists);
    $stmt->execute();

    return (bool) $stmt->fetchColumn();
}

// Create the database
function createDatabase($connection, $dbName): void
{
    $sql = "CREATE DATABASE $dbName";

    try {
        $connection->exec($sql);
    } catch (PDOException $e) {
        echo "Error creating database, ensure config values are correct: " . $e->getMessage();
        exit();
    }
}

// Execute the SQL script
function executeSqlScripts($connection, $sqlScripts): void
{
    foreach ($sqlScripts as $sqlScript) {
        try {
            $stmt = $connection->prepare($sqlScript);
            $stmt->execute();
        } catch (PDOException $e) {
            // echo "Error executing query: " . $e->getMessage() . "<br>";
            // Extract the table name from the SQL script
            preg_match('/CREATE TABLE (\w+)/', $sqlScript, $matches);
            $tableName = isset($matches[1]) ? $matches[1] : 'unknown';

            echo "Table ($tableName) already exist in database <br>";
        }
    }
    echo "<br> Query executed successfully <br>";
}

// Comment out the install script line after installation
function disableInstall(): void
{
    $scriptPath = __DIR__ . '/../index.php';

    if (file_exists($scriptPath) && is_readable($scriptPath)) {
        // Open the file for reading and writing
        $fileHandle = fopen($scriptPath, 'r+');

        if ($fileHandle !== false) {
            // Read the entire content of the file
            $scriptContent = fread($fileHandle, filesize($scriptPath));

            // Search for the specific line and comment it out
            $searchString = 'require_once(__DIR__ . \'/install/install.php\');';
            $replacement = '// ' . $searchString;
            $scriptContent = str_replace($searchString, $replacement, $scriptContent);

            // Move the file pointer to the beginning
            fseek($fileHandle, 0);

            // Write the modified content back to the file
            if (fwrite($fileHandle, $scriptContent) === false) {
                echo "Error writing to file '$scriptPath'. Check file permissions.";
            }

            // Close the file handle
            fclose($fileHandle);
        } else {
            echo "Error opening file '$scriptPath' for reading and writing.";
        }
    } else {
        // The file does not exist or is not readable
        echo "File '$scriptPath' does not exist or is not readable.";
    }
}
