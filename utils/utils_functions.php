<?php
function uploadImage($path, $image) {
    $imageName = basename($image["name"]);
    $fullPath = $path.$imageName;

    $maxKb = 500;
    $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
    $result = 0;
    $msg = "";
    
    // check if provided image is an image
    $imgSize = getimagesize($image["tmp_name"]);
    if ($imgSize == false) {
        $msg .= "File caricato non è un immagine! ";
    }

    if ($image["size"] > $maxKb * 1024) {
        $msg .= "Superato limite dimensione massima immagine. Dimensione massima è di $maxKb";
    }

    $imageFileType = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $acceptedExtensions)) {
        $msg .= "Accettate le seguenti estensioni: ".implode(",", $acceptedExtensions);
    }

    // enumerating the version (if present) of the file
    if (file_exists($fullPath)) {
        $i = 1;
        do {
            $i++;
            $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
        } while (file_exists($path.$imageName));
        $fullPath = $path.$imageName;
    }
    
    // copy from tmp position to definitive position
    if (strlen($msg) == 0) {
        if (!move_uploaded_file($image["tmp_name"], $fullPath)) {
            $msg .= "Errore nel caricamento dell'immagine";
        } else {
            $result = 1;
            $msg = $imageName;
        }
    }
    return array($result, $msg);
}

?>
