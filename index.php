<?php
/**
 * WePing - Let multiple users post to your social sites using ping.fm
 *
 */

require_once('config.php');
$CONF['api_key'] = '695f8bc472032e929a67520eb6694bd9';
error_reporting(E_ALL ^ E_NOTICE);

require_once('HTTPDigest.php');
$HTTPDigest =& new HTTPDigest();
$HTTPDigest->passwordsHashed = false;
$HTTPDigest->nonceLife = 12*60*60; //stay logged in 12 hours
$LOGIN = $HTTPDigest->authenticate($USERS);
if(!$LOGIN){
    $HTTPDigest->send();
    die('Not authenticated');
}


require_once('HTTPClient.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    <title><?php echo htmlspecialchars($CONF['title'])?> - WePing</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script language="JavaScript" type="text/javascript" src="script.js"></script>
    <link rel="shortcut icon" type="image/png" href="weping.png" />
</head>
<body>
    <div class="wrap">
        <h1 id="popup"><?php echo htmlspecialchars($CONF['title'])?></h1>
        <?php post(); ?>

        <?php postForm(); ?>
        <?php flush(); ob_flush() ?>

        <?php getLatest(); ?>

        <span id="login">/<?php echo htmlspecialchars($LOGIN)?></span>
        <a class="foot" href="http://github.com/splitbrain/WePing">WePing</a>
    </div>
</body>
</html>
<?php

function post(){
    global $LOGIN;

    if(!$_REQUEST['go']) return;
    $post = trim($_REQUEST['p']);
    if(!$post) return;

    $data = array(
        'post_method' => 'default',
        'body' => $post.' /'.$LOGIN,
    );

    $xml = apiCall('user.post',$data);
    if($xml === false){
        echo '<p class="error">Failed to post message.</p>';
    }else{
        echo '<p class="success">Message posted.</p>';
        $_REQUEST['p'] = '';

        if(!isset($_REQUEST['popup'])) sleep(5);
    }

}

function postForm(){

    echo '<form action="index.php" method="post" accept-charset="UTF-8">';
    echo '<p><textarea name="p" id="p" rows="5" cols="25">'.htmlspecialchars($_REQUEST['p']).'</textarea><br />';
    if(isset($_REQUEST['popup'])) echo '<input type="hidden" name="popup" value="1" />';
    echo '<input type="submit" name="go" value="Send to Services" /><span id="chars"></span></p>';
    echo '</form>';

}

function getLatest(){
    if(isset($_REQUEST['popup'])) return;

    $xml = apiCall('user.latest');
    if(!$xml){
        echo '<p class="error">Failed to fetch recent messages</p>';
        return;
    }

    echo '<ul>';
    foreach($xml->messages->message as $message){
        $body = base64_decode($message->content->body);
        $body = htmlspecialchars($body);
        $body = preg_replace_callback('/((https?|ftp):\/\/[\w-?&;:#~=\.\/\@]+[\w\/])/ui',
                                      'formatLink',$body);

        echo '<li>';
        echo $body;
        echo '<span>'.date('Y-m-d H:i',(int) $message->date['unix']).'</span> ';
        echo '</li>';
    }
    echo '</ul>';
}

function formatLink($match){
    global $CONF;

    $url = $match[1];
    str_replace("\\\\'","'",$url);

    // resolve short url
    if($CONF['longurl']){
        $http = new HTTPClient();
        $xml = $http->get('http://api.longurl.org/v2/expand?format=php&url='.urlencode($url));
        if($xml != false){
            $xml = unserialize($xml);

            $long = $xml['long-url'];
        }
    }

    if(!$long) $long = $url;

    $link = '<a href="'.$url.'" title="'.$long.'">'.$url.'</a>';
    return $link;
}


function apiCall($call,$data=array()){
    global $CONF;
    $http = new HTTPClient();

    $input = array('api_key' => $CONF['api_key'], 'user_app_key' => $CONF['app_key']);
    $input = array_merge($input,$data);

    $xml = $http->post('http://api.ping.fm/v1/'.$call,$input);
    if($xml === false) return $false;

    $xml = new SimpleXMLElement($xml);
    if($xml['status'] != 'OK') return false;

    return $xml;
}
