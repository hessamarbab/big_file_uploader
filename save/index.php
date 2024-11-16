<?php
header('Content-type: text/plain');
$fileName = $_FILES["file"]["name"]; // The file name
$fileTmpLoc = $_FILES["file"]["tmp_name"]; // File in the PHP tmp folder
$fileSize = $_FILES["file"]["size"]; // File size in bytes
const uploads = 'uploads';
const maxsize = 1024 * 1024 * 1024 * 5;
if (hash('sha256', $_POST["password"]) != "b02c12bc14b23a84c70cdedea22e1b06ab338e636c73fc9abfdb431918017a44") {
    http_response_code(401);
    echo "UnAuthenticate";
    die;
}

if (!is_dir(uploads)) mkdir(uploads);
//echo json_encode($_FILES)."\n";
if (!$fileTmpLoc) { // if file not chosen
    http_response_code(400);
    echo "No file to upload";
    die;
}

if ($fileSize > 200000000) { // 200 MB
    http_response_code(413);
    echo "File is too large";
    die;
}

if (strlen($_POST['id']) != 13) {
    header('mark: 0');
    /*if (mime_content_type($fileTmpLoc) != 'application/zip') {
        http_response_code(415);
        die;
    };*/
    $id = uniqid('');
    $fileloc = uploads.'/'. $id . '-' . $fileName;// $id;
    move_uploaded_file($fileTmpLoc, $fileloc);

} else {
    header('mark: 1');
    $id = $_POST['id'];
    $fileloc = uploads.'/'. $id . '-' . $fileName;// $id;
    if (!file_exists( $fileloc )) {
        http_response_code(400);
        die;
    }
    if (filesize($fileloc) + filesize($fileTmpLoc) > maxsize) {
        unlink($fileloc);
        http_response_code(413);
        die;
    }
    file_put_contents( $fileloc, file_get_contents($fileTmpLoc), FILE_APPEND);

}

echo $id;
header('Referrer-Policy: origin');

?>