<?php
require (__DIR__ . "/lib/Deployer.php");
/**
 * Get input from the user
 * @param $prompt
 * @return string
 */
function getInput($prompt)
{
    echo $prompt;
    $input = trim(fgets(STDIN));
    return $input;
}

/**
 * Main function
 * runs the deployer and prompts the user for input
 */
function main()
{
    $deploy = new Deployer();

    while (true) {
        echo "Select an option:\n";
        echo "1. Deploy from environment\n";
        echo "2. Deploy specific package from environment\n";
        echo "3. Rollback version\n";
        echo "4. Rollback package\n";
        echo "5. Exit\n";

        $choice = getInput("Enter your choice (1-5): ");

        switch ($choice) {
            case '1':
                $cluster = getInput("Enter the environment to deploy from (dev or qa): ");
                $deploy->deploy_from($cluster);
                break;
            case '2':
                $cluster = getInput("Enter the environment to deploy from (dev or qa): ");
                $type = getInput("Enter the package type to deploy (db, fe, dmz): ");
                $deploy->deploy_from($cluster, $type);
                break;
            case '3':
                $version_id = getInput("Enter the version ID to rollback: ");
                $deploy->rollback_version($version_id);
                break;
            case '4':
                $id = getInput("Enter the package's record id: ");
                $deploy->rollback_package($id);
                break;
            case '5':
                echo "Exiting...\n";
                exit(0);
            default:
                echo "Invalid choice. Please try again.\n";
        }
    }
}

main();
