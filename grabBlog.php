<?php
// mysql settings
$dbcon = getConnected('localhost','USER','PASS','DBNAME');

    function getConnected($host,$user,$pass,$db) {        
       $dbcon = new mysqli($host, $user, $pass, $db);
       if($dbcon->connect_error) 
         die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
       return $dbcon;
    }
//
$url = "WWW.YOURDOMAIN.COM/RSS/";
$content_ns = "http://purl.org/rss/1.0/modules/content/";

/* load the file */
$rss = file_get_contents("http://barrysmith.org/rss");
/* create SimpleXML object */
$xml = new SimpleXMLElement($rss);
$root=$xml->channel; /* our root element */
$count =1;
foreach($root->item as $item) { 
        
    $date = $item->pubDate;
    $title = $item->title;
    $date = date('Y-m-d', strtotime($date));
    foreach($item->children($content_ns) as $content_node) {
        
        //removing images (comment out to remove this feature)
        $content_node = preg_replace("/<img[^>]+\>/i", "", $content_node);

        $sql="INSERT INTO `blog`(`id`, `author_id`, `title`, `cat`, `date`, `content`) VALUES ('', '1', '$title', '$date', '$content_node')";
        $result = $dbcon->query($sql);
        echo 'Adding blog entry no '.$count.'...<br/>';
        $count++;
    }
    
}