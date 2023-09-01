<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
date_default_timezone_set('Asia/Hong_Kong');
require("configdbconnect.php");
$configuration = $globleConnectDB;
defined('BASEPATH') OR exit('No direct script access allowed');
define('PANELVERSION', "V0.9.5.18");

//Project inforamtion
define('AUTOSKU', True);
define('SKU_PREFIX', 'JL');
define('DEFAULT_IMAGE', 'image/default_image.jpg');
define('GLOBAL_CURRENCY', $configuration['currency']);
define('SITE_NAME', $configuration['site_name']);
define('SITE_LOGO', $configuration['site_logo']);
define('SITE_URL', $configuration['site_url']);
define('PRODUCT_IMAGE_BASE', $configuration['product_images_url']);
define('PRODUCT_FOLDER', $configuration['product_folders']);
define('product_folders', $configuration['product_folders']);

define('product_image_base', $configuration['product_images_url']);

//Email Setting
define('EMAIL_SENDER', $configuration['email_sender']);
define('EMAIL_SENDER_NAME', $configuration['email_sender_name']);
define('EMAIL_BCC', $configuration['email_bcc']);

define('SEO_TITLE', $configuration['seo_title']);
define('SEO_DESC', $configuration['seo_desc']);

define('HEADERCSS', $globleConnectTheme['style_css']);

$baselink = 'http://' . $_SERVER['SERVER_NAME'];

if (strpos($baselink, '192.168')) {
    $islocal = true;
    $baselinkmainsite = 'http://' . $_SERVER['SERVER_NAME'] . $configuration['localpath'] . "/index.php/";
    ;
} elseif (strpos($baselink, 'localhost')) {
    $islocal = true;
    $baselinkmainsite = 'http://' . $_SERVER['SERVER_NAME'] . $configuration['localpath'] . "/index.php/";
} else {
    $baselinkmainsite = $configuration['site_url'];
}
define('MAIN_WEBSITE', $baselinkmainsite);

defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

$apiSet = array(
    "ourChurchese" => array(
        "table" => "our_churches",
        "imagefolder" => "our_churches",
        "image_field" => "image",
        "title" => "Our Churches",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "tvPrograms" => array(
        "table" => "tv_program",
        "imagefolder" => "tv_programs",
        "image_field" => "image",
        "title" => "TV Programs",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "bibleCollege" => array(
        "table" => "paul_bible",
        "imagefolder" => "bible_collage",
        "image_field" => "image",
        "title" => "Bible College",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "pastorsLeaders" => array(
        "table" => "pastors_images",
        "imagefolder" => "pastors",
        "image_field" => "image",
        "title" => "Pastors Leaders",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "aboutUs" => array(
        "table" => "paul_about_us",
        "imagefolder" => "about_us",
        "image_field" => "image",
        "title" => "About Us",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "todaysBlessings" => array(
        "table" => "todays_blessings",
        "imagefolder" => "blessings",
        "image_field" => "image",
        "title" => "Todays Blessings",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "donateImages" => array(
        "table" => "donate_images",
        "imagefolder" => "donate_support",
        "image_field" => "image",
        "title" => "Donations",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "paulEvent" => array(
        "table" => "paul_event",
        "imagefolder" => "events",
        "image_field" => "image",
        "title" => "Church Events",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "amazoneBooks" => array(
        "table" => "amazone_books",
        "imagefolder" => "amazone_books",
        "image_field" => "image",
        "title" => "Amazon Books",
        "pk" => "id",
        "foreign_key" => array(),
        "ignore_field" => array("created_on", "create_by", "modified_on"),
        "writable"=>true,
    ),
    "lyricsTrack" => array(
        "table" => "lyrics_tracks",
        "imagefolder" => "worship_songs/thumbs",
        "image_field" => "image",
        "title" => "Lyrics",
        "pk" => "id",
        "foreign_key" => array(),
        "foreign_key" => array(
            "foreign_key" => "lyricsId",
            "table_name" => "lyrics",
            "parent_api" => "lyrics",
            "title" => "name",
            "pk" => "id",
            "parent_api" => "lyrics"
        ),
         "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "lyrics" => array(
        "table" => "lyrics",
        "imagefolder" => "worship_songs/thumbs",
        "image_field" => "image",
        "title" => "Lyrics",
        "pk" => "id",
        "foreign_key" => array(),
        "child_api" => array(
            "child_api" => "lyricsTrack",
            "connect_button" => "View Lyrics",
            "pk" => "id"
        ),
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "worshipSongs" => array(
        "table" => "worship_songs",
        "imagefolder" => "worship_songs/thumbs",
        "image_field" => "image",
        "title" => "Worship Songs",
        "pk" => "id",
        "foreign_key" => array(
            "foreign_key" => "albumId",
            "table_name" => "paul_audio_album",
            "title" => "name",
            "pk" => "id",
            "parent_api" => "worshipSongsAlbum",
        ),
         "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "worshipSongsAlbum" => array(
        "table" => "paul_audio_album",
        "imagefolder" => "worship_songs/thumbs",
        "image_field" => "image",
        "title" => "Worship Songs Album",
        "foreign_key" => array(),
        "pk" => "id",
         "writable"=>true,
        "child_api" => array(
            "child_api" => "worshipSongs",
            "connect_button" => "View Songs",
            "pk" => "id"
        ),
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "worshipSongsAlbum" => array(
        "table" => "paul_audio_album",
        "imagefolder" => "worship_songs/thumbs",
        "image_field" => "image",
        "title" => "Worship Songs Album",
        "foreign_key" => array(),
        "pk" => "id",
         "writable"=>true,
        "child_api" => array(
            "child_api" => "worshipSongs",
            "connect_button" => "View Songs",
            "pk" => "id",
        ),
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "charityWorks" => array(
        "table" => "charity_work",
        "imagefolder" => "charity",
        "image_field" => "image",
        "title" => "Charity Works",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "young_partners" => array(
        "table" => "young_partners",
        "imagefolder" => "young_partners",
        "image_field" => "image",
        "title" => "Young Parter",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "magazine_registration" => array(
        "table" => "magazine_registration",
        "title" => "E-Magazine Registration",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "testimony" => array(
        "table" => "paul_testimony",
        "title" => "Testimony",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "prayer" => array(
        "table" => "paul_prayer",
        "title" => "Prayer",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "invite" => array(
        "table" => "paul_invite",
        "title" => "Invites",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "pastors" => array(
        "table" => "pastors",
        "title" => "Pastors Registration",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "bible_registration" => array(
        "table" => "bible_registration",
        "title" => "Bible Registration",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "youngPartners" => array(
        "table" => "young_partners",
        "imagefolder" => "young_partners",
        "image_field" => "image",
        "title" => "Young Parter",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "lifeChangingTv" => array(
        "table" => "life_changing_tv",
        "imagefolder" => "live_tv",
        "image_field" => "image",
        "title" => "Life Changing Tv",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "lifeChangingVideos" => array(
        "table" => "life_changing_videos",
        "title" => "Life Changing Videos",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "pastorsCollage" => array(
        "table" => "pastors_images",
        "imagefolder" => "pastors",
        "image_field" => "image",
        "title" => "Pastors Collage",
        "foreign_key" => array(),
        "pk" => "id",
        "writable"=>true,
        "ignore_field" => array("created_on", "create_by", "modified_on")
    ),
    "system_log" => array(
        "table" => "system_log",
        "title" => "System Log",
        "foreign_key" => array(),
        "pk" => "id",
        "ignore_field" => array()
    ),
);

define('APISET', $apiSet);

$menuList = array(
    "Reports" => array(
        "magazine_registration",
        "youngPartners",
        "testimony",
        "prayer",
        "pastors",
        "bible_registration",
        "invite",
    ),
    "WorshipSongs" => array(
        "worshipSongsAlbum",
        "lyrics"
    ),
    "DataManagement" => array(
        "amazoneBooks",
        "charityWorks",
        "donateImages",
        "ourChurchese",
        "aboutUs",
        "tvPrograms",
        "paulEvent",
        "lifeChangingTv",
        "lifeChangingVideos",
        "todaysBlessings",
        "pastorsCollage"
    )
);

define('MENULIST', $menuList);

