<?php
// Load .env file to access the DEPLOY_TOKEN
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    $expected_token = $env['DEPLOY_TOKEN'];  // Get the DEPLOY_TOKEN from the .env file
} else {
    echo 'Error: .env file not found!';
    exit;
}

// Retrieve the token from the request header
$headers = getallheaders();
$deploy_token = isset($headers['X-DEPLOY-TOKEN']) ? $headers['X-DEPLOY-TOKEN'] : null;

// Check if the token is valid
if ($deploy_token !== $expected_token) {
    echo 'Invalid deploy token!';
    exit;  
}

$zip_file = './api/vendor.zip'; // Path to the uploaded zip file
$extract_to = './api/'; // Path where you want to extract the files (vendor folder)

// Ensure the file exists
if (file_exists($zip_file)) {
    $zip = new ZipArchive;
    if ($zip->open($zip_file) === TRUE) {
        // Debugging: Print current working directory
        echo "Current working directory: " . getcwd() . "<br>";

        // Ensure the 'vendor' directory exists, if not create it
        if (!file_exists($extract_to)) {
            echo "Creating directory: $extract_to <br>";
            if (mkdir($extract_to, 0777, true)) {
                echo "Directory created successfully.<br>";
            } else {
                echo "Failed to create directory.<br>";
            }
        }

        // Extract files to the 'vendor/' folder
        $zip->extractTo($extract_to);
        $zip->close();
        
        echo 'Unzip successful!';
        
        // Optionally remove the zip file after extraction
        unlink($zip_file);
    } else {
        echo 'Failed to open zip file!';
    }
} else {
    echo 'Zip file not found!';
}
?>
