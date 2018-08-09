
<?php
	$filename= fopen('/home/sajith/Downloads/tmp/x1.mp4', 'rb');

// here you can chech your sessions
 
function flush_buffers(){
    ob_end_flush();
    ob_flush();
    flush();
    ob_start();
}
 
    header("Content-Type: video/mp4");
    //header("Content-Disposition: attachment; filename=video.flv");
    $fd = fopen($filename, "r");
    while(!feof($fd)) {
        echo fread($fd, 1024 * 5);
	    flush_buffers();
        }
    fclose ($fd);
    exit();
?>
