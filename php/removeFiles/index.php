<?php

// Remove file
$file = 'path/tmp/file.pdf';
unlink($file);


// Remove all file in directory
function removeFilesInDirectory()
{
    $files = 'path/tmp/*';

    foreach (glob($files) as $file) {
        unlink($file);
    }
}


// Remove all file PDF in directory
function removeFilesPDFInDirectory()
{
    $files = 'path/tmp/*.pdf';

    foreach (glob($files) as $file) {
        unlink($file);
    }
}