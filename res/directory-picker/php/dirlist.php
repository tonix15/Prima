<?php
$get = array_merge($_GET, $_POST);

$path = preg_replace('/\\\\/', "/", $get["path"]);
if(!is_dir($path)) { $path = $_SERVER["DOCUMENT_ROOT"]; }

$select_files = $get["pick_files"] ? true : false;

if(preg_match('/\.\./', $path)) {
    $items = explode("/", $path);
    $tmp = array();
    foreach($items as $item) {
        if($item=="..") { array_pop($tmp); }
		else{ $tmp[] = $item; }
    }
    $path = implode("/", $tmp);
}

$data = array(
    "base" => $path,
    "children" => array()
);
if($path != $_SERVER["DOCUMENT_ROOT"]) {
    $data["children"][] = array(
        "type" => "dir-up",
        "name" => ".."
    );
}

if($dir = opendir($path)) {
    while($f = readdir($dir)) {
        if(!preg_match('/^\.+$/', $f) && ($select_files || is_dir($path . "/" . $f))) {
            $data["children"][] = array(
                "type" => is_dir("$path/$f") ? "dir" : substr($f, strrpos($f, ".")+1),
                "name" => $f
            );
        }
    }
}

echo json_encode($data);
?>
