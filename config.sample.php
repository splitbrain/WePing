<?php
/**
 * This is a sample configuration, you need to copy it to config.php and
 * fill in the required values
 */


// A title - you might want to set it to your company name
$CONF['title']   = 'WePing';

// Should short URLs be resolved in the recent message view using the
// longurl.com Service? This makes the display much slower
$CONF['longurl'] = false;

// Your ping.fm APP Key. Get it at http://ping.fm/key/
$CONF['app_key'] = 'FIXME';

// Define your users and their passwords here. The username should be
// kept short as it is appended to each message
$USERS['ag'] = 'pass';
$USERS['dh'] = 'otherpass';

