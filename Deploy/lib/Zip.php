<?php

/**
 * Class Zip
 * Creates a ZIP file of the source directory
 * Uses zip command line utility
 * 
 */
class Zip
{
    /**
     * Create a ZIP file of directory
     * @param string $dir
     * @param string $file
     * @return string
     */
    public function create_zip($dir, $file){
        $parent_dir = dirname($dir);
        // dirname() - returns a parent directory's path
        $folder_name = basename($dir);
        // basename() - helps get the name of just the folder
        $zip_command = 'cd ' . $parent_dir . ' && zip -r ' . $file . ' ' . $folder_name;
        return $zip_command;
    }

    /**
     * Unzip ZIP file
     * @param string $zip_file
     * @param string $extract_to
     * @return array
     */
    public function unzip($zip_file, $extract_to){
        // Make a temp folder to extract the zip file to
        $temp_folder = $extract_to . 'temp_extract/';
        // Create the commands to execute
        // -p flag creates the parent directory if it doesn't exist
        $create_temp_folder = 'mkdir -p ' . $temp_folder;
        // -o flag overwrites any existing files
        // -d flag specifies the destination directory
        $unzip_command = 'unzip -o ' . $zip_file . ' -d ' . $temp_folder;
        // Move the contents of the temp folder to the destination
        $move_command = 'mv ' . $temp_folder . '*/* ' . $extract_to;
        // Remove the temp folder
        $remove_temp_folder = 'rm -r ' . $temp_folder;
        return array($create_temp_folder, $unzip_command, $move_command, $remove_temp_folder);
    }
}