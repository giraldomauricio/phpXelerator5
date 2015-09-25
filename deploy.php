<?php

$server_root = "/var/chroot/home/content/74/10352174/html/mgiraldo/spocc/";

$file = 'https://github.com/giraldomauricio/phpXelerator5/archive/master.zip';
$revision = date("YMDhis");
$newfile = "/var/chroot/home/content/74/10352174/html/mgiraldo/spocc/".$revision.".zip";
echo "Downloading...<br/>";
if (!copy($file, $newfile)) {
    echo "failed to copy $file...\n";
} else {
    echo "Downloaded...<br/>";
}

echo "Extracting...<br/>";
$zip = new ZipArchive;
if ($zip->open($newfile) === TRUE) {
    $zip->extractTo('/var/chroot/home/content/74/10352174/html/mgiraldo/spocc/'.$revision."/");
    $zip->close();
    echo "Extracted...<br/>";
} else {
    echo 'Failed';
}

if(rename ('/var/chroot/home/content/74/10352174/html/mgiraldo/spocc/'.$revision."/phpXelerator5-master", '/var/chroot/home/content/74/10352174/html/mgiraldo/spocc/'.$revision."/app")) {
    echo "Extracted...<br/>";
} else {
    echo "Failed";
}

if(unlink($newfile)) {
    echo "Deleted...<br/>";
} else {
    echo "Failed...<br/>";
}