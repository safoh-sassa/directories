<?php
include("DBconnection.php");

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

date_default_timezone_set('Europe/Prague');

$directory = $_POST["dir"];
if (is_dir($directory)) {
    $fileList = scandir($directory);
    $dirName = basename($directory);
    // Extract file names, sizes and last modified dates
    $fileNames = $sizes = $lastModifiedDates = '';

    foreach ($fileList as $file) {
        if (is_file($directory . '/' . $file)) {
            $fileNames .= $file . ', ';
            $sizes .= filesize_formatted($directory . '/' . $file) . ', ';
            $lastModifiedDates .= date("d/m/Y H:i:s", filemtime($directory . '/' . $file)) . ', ';
        }
    }

    $fileNames = rtrim($fileNames, ", ");
    $sizes = rtrim($sizes, ", ");
    $lastModifiedDates = rtrim($lastModifiedDates, ", ");

    $sql = "SELECT * FROM directories WHERE dirName='$directory'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id = $row["id"];
        $sql = "UPDATE directories SET files='$fileNames', size='$sizes', lastModified='$lastModifiedDates' WHERE id=$id";
    } else {
        $sql = "INSERT INTO directories (dirName, files, size, lastModified) VALUES ('$directory', '$fileNames', '$sizes', '$lastModifiedDates')";
    }

    if (mysqli_query($conn, $sql)) {
        $sql = "SELECT * FROM directories WHERE dirName= '" . $directory . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode($row);
        }
    }
} else
    echo 0;

mysqli_close($conn);
