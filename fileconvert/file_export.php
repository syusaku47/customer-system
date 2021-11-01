<?php

set_time_limit(0);

$dbconn = pg_connect("host=localhost port=5432 dbname=hrm_sshagiwara user=hrm_sshagiwara password=oBJ4hW9u");

print "star\n";
if (!$dbconn) {
    print("connect failed".pg_last_error());
    exit;
}

print("connect success\n");


$sql = "SELECT id FROM filecontrol";
if ( !$result = pg_query($sql)) {
    print ("connect failed : $sql\n");
    exit;
}

$ids = pg_fetch_array($result, NULL, PGSQL_NUM);
print($ids[0]."\n");


//foreach ($ids as $key => $id) {
//    print($key."番目".$id."\n") ;
//}

$sql = "SELECT file, filename, extension FROM filecontrol WHERE id = ".$ids[0];
if ( !$result = pg_query($sql)) {
    print ("connect failed : $sql\n");
    exit;
}

$files = pg_fetch_array($result, NULL, PGSQL_NUM);

//apache_setenv('no-gzip', '1');
//
//header('Content-Disposition: attachment; filename=' . basename($filename));
//header('Content-Type: application/octet-stream; name=' . basename($filename));
//header('Content-Length: ' . $file['filesize']);

mb_language("Japanese");
print($files[1]."\n");
$filename = mb_convert_encoding($files[1],"UTF-8","auto") . "." . $files[2];
$file = pg_unescape_bytea($files[0]);
file_put_contents("./file_convert/".$filename, $file);

print($filename."\n");


pg_close($dbconn);
