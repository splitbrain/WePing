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

// The application key. Once ping.fm finally approves the WePing application,
// you will not need to touch this. Until then you need to get your own
// API key at http://ping.fm/developers/ and put it here
$CONF['api_key'] = '695f8bc472032e929a67520eb6694bd9';

// Define your users and their passwords here. The username should be
// kept short as it is appended to each message
$USERS['ag'] = 'pass';
$USERS['dh'] = 'otherpass';

