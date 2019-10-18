 <?php

require_once('twitteroauth/twitteroauth.php');

output_clean("                    __    __         
    ____  ____ ___  / /_  / /________ 
   / __ \/ __ `__ \/ __ \/ / ___/ __ \
  / /_/ / / / / / / /_/ / (__  ) / / /
 / .___/_/ /_/ /_/_.___/_/____/_/ /_/ 
/_/                                   ");

output_clean("");
output_clean("Social Media Society");
output_clean("v.1.3");
output_clean("© Developed by SGB Team (https://sgbteam.id)");
output_clean("________________________________ _ _ _ _ _ _");
output_clean("");
sleep(2);

$ck = getVarFromUser("Consumer Key");
        if (empty($ck)) {
            do { 
                $ck = getVarFromUser("Consumer Key"); 
            } while (empty($ck));
        }

        $cs = getVarFromUser("Consumer Secret");
        if (empty($cs)) {
            do { 
                $cs = getVarFromUser("Consumer Secret");
            } while (empty($cs));
        }
$at = getVarFromUser("Access Token");
        if (empty($at)) {
            do { 
                $at = getVarFromUser("Access Token"); 
            } while (empty($at));
        }

        $ats = getVarFromUser("Access Token Secret");
        if (empty($ats)) {
            do { 
                $ats = getVarFromUser("Access Token Secret");
            } while (empty($ats));
        }

define('CONSUMER_KEY', $ck);
define('CONSUMER_SECRET', $cs);
define('access_token', $at);
define('access_token_secret', $ats);

$jumlah = "1";
$type = "recent";

function randomline( $target )
{
    $lines = file( $target );
    return $lines[array_rand( $lines )];
}

output_clean("");
output_clean("Function:");
output_clean("1.   Following By Target");
output_clean("2.   Unfollow Not Followback");
output_clean("");

$jika = getVarFromUser("Apa Maumu?");
        if (empty($jika)) {
            do { 
                $jika = (int)getVarFromUser("Apa Maumu?");
            } while (empty($jika));
        }

output_clean("________________________________ _ _ _ _ _ _");
output_clean("");


if ($jika == 1) {
$delay = getVarFromUser("Delay (s)");
        if (empty($delay)) {
            do { 
                $delay = (int)getVarFromUser("Delay (s)");
            } while (empty($delay));
        }

// max number of iterations
$it = 10;

while($it > 0)
{

$target = randomline('target.txt');
$koneksi = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, access_token, access_token_secret);
$nasi = $koneksi->get('search/tweets', array('q' => $target,  'count' => $jumlah, 'result_type' => $type));
$statuses = $nasi->statuses;
foreach($statuses as $status)
{
$username = $status->user->screen_name;
$eksekusi = $koneksi->post('friendships/create', array('screen_name' => $username));
if($eksekusi->errors) {
echo date("H:i:s")."           Gagal \n";
}
else {
echo date("H:i:s")."    Berhasil. Follow $username \n";
}
}
	flush();
	usleep($delay*1000000);
}
} else {
$dela = (int)getVarFromUser("Delay (h)");
        if (empty($dela)) {
            do { 
                $dela = (int)getVarFromUser("Delay (h)"); 
            } while (empty($dela));
        }

output_clean("________________________________ _ _ _ _ _ _");
output_clean("");

// max number of iterations
$it = 10;

while($it > 0)
{

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, access_token, access_token_secret);
	        $followers = $connection->get("followers/ids.json?cursor=-1&");
	         $friends = $connection->get("friends/ids.json?cursor=-1&");
	          $fullfriend=batim($friends);
                   $fullfollower=batim($followers); 
 $index = 1;
    $unfollow_total=0;

$all_friends = $fullfriend['ids'];
$all_followers = $fullfollower['ids'];
foreach( $all_friends as $iFollow )
    {
    $isFollowing = in_array( $iFollow, $all_followers );
	           echo "$iFollow: ".( $isFollowing ? 'OK' : '!!!' )."\n";
    $index++;
     if( !$isFollowing )
        {
        $parameters = array( 'user_id' => $iFollow );
        $status = $connection->post('friendships/destroy', $parameters);
        $unfollow_total++;
        } if ($unfollow_total === 999) break;
    }

	flush();
	sleep($dela*3600);
}
}



                    function batim($d) {
		   if (is_object($d)) {
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {

			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}


/**
 * Get varable from user
 */
function getVarFromUser($text) {
    echo $text . ": ";
    $var = trim(fgets(STDIN));
    return $var;
}

/**
 * Output clean message to console
 */
function output_clean($message) {
    echo $message, PHP_EOL;
}

?>