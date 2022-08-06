<?php
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

function loadenv($envName, $default = "") {
    return getenv($envName) ? getenv($envName) : $default;
}


## Uncomment this to disable output compression
$wgDisableOutputCompression = true;

$wgSitename = loadenv('MEDIAWIKI_SITE_NAME', 'BrothersBrothers');
if (getenv('MEDIAWIKI_META_NAMESPACE') !== false) {
    $wgMetaNamespace = loadenv('MEDIAWIKI_META_NAMESPACE', $wgSitename);
}

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## https://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = loadenv('MEDIAWIKI_SCRIPT_PATH');
$wgArticlePath = "/$1";

## The protocol and server name to use in fully-qualified URLs
$wgServer = loadenv('MEDIAWIKI_SITE_SERVER', '//localhost');

## The URL path to static resources (images, scripts, etc.)
$wgResourceBasePath = $wgScriptPath;

## The URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogo = loadenv('MEDIAWIKI_LOGO', "$wgResourceBasePath/images/logo.png");

## UPO means: this is also a user preference option

$wgEnableEmail = filter_var(loadenv('MEDIAWIKI_ENABLE_EMAIL', false), FILTER_VALIDATE_BOOLEAN);
$wgEnableUserEmail = filter_var(loadenv('MEDIAWIKI_ENABLE_USER_EMAIL', false), FILTER_VALIDATE_BOOLEAN); # UPO

$wgEmergencyContact = loadenv('MEDIAWIKI_EMERGENCY_CONTACT', "apache@localhost");
$wgPasswordSender = loadenv('MEDIAWIKI_PASSWORD_SENDER', "apache@localhost");

# Disable Job execution on page requests (can be setup via cron jobs)
$wgJobRunRate = 0;

$wgEnotifUserTalk = false; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = true;

## Database settings
$wgDBtype = loadenv('MEDIAWIKI_DB_TYPE', "mysql");
$wgDBserver = loadenv('MEDIAWIKI_DB_HOST', "db");
$wgDBname = loadenv('MEDIAWIKI_DB_NAME', "wikidb");
$wgDBuser = loadenv('MEDIAWIKI_DB_USER', "root");
$wgDBpassword = loadenv('MEDIAWIKI_DB_PASSWORD', "mediawikipass");

# MySQL specific settings
$wgDBprefix = loadenv('MEDIAWIKI_DB_PREFIX', "");

# MySQL table options to use during installation or update
$wgDBTableOptions = loadenv('MEDIAWIKI_DB_TABLE_OPTIONS', "ENGINE=InnoDB, DEFAULT CHARSET=binary");

## Shared memory settings
$mainCache = loadenv('MEDIAWIKI_MAIN_CACHE', 'CACHE_NONE');
$wgMainCacheType = constant($mainCache) ? constant($mainCache) : $mainCache;
switch ($wgMainCacheType) {
    case CACHE_MEMCACHED:
        $wgMemCachedServers = json_decode(loadenv('MEDIAWIKI_MEMCACHED_SERVERS', '[]'));
        break;
    case 'redis':
        $wgObjectCaches['redis'] = [
            'class' => 'RedisBagOStuff',
            'servers' => [
                loadenv('MEDIAWIKI_REDIS_HOST').':'.loadenv('MEDIAWIKI_REDIS_PORT', 6379)
            ],
            'persistent' => filter_var(loadenv('MEDIAWIKI_REDIS_PERSISTENT', false), FILTER_VALIDATE_BOOLEAN)

        ];
        if (!empty($redis_pwd = loadenv('MEDIAWIKI_REDIS_PASSWORD'))) {
            $wgObjectCaches['redis']['password'] = $redis_pwd;
        }
        break;
}

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads = true;
$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";
$wgFileExtensions = array('png', 'gif', 'jpg', 'jpeg', 'doc', 'xls', 'pdf', 'ppt', 'tiff', 'bmp', 'docx', 'xlsx', 'pptx', 'ps');
#$wgGenerateThumbnailOnParse = false;

# InstantCommons allows wiki to use images from https://commons.wikimedia.org
$wgUseInstantCommons = true;

# Periodically send a pingback to https://www.mediawiki.org/ with basic data
# about this MediaWiki instance. The Wikimedia Foundation shares this data
# with MediaWiki developers to help guide future development efforts.
$wgPingback = false;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "C.UTF-8";

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
$wgCacheDirectory = loadenv('MEDIAWIKI_CACHE_DIRECTORY', false);

# Site language code, should be one of the list in ./languages/data/Names.php
$wgLanguageCode = loadenv('MEDIAWIKI_LANGUAGE', "en");

$wgSecretKey = loadenv('MEDIAWIKI_SECRET_KEY', "fde4af77bfe31f20dbf5e1d2f872ae5017bcc5a39fdca06f92a1b7cfd44e9db5");

# Changing this will log out all existing sessions.
$wgAuthenticationTokenVersion = "1";

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
$wgUpgradeKey = loadenv('MEDIAWIKI_UPGRADE_KEY', null);

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = loadenv('MEDIAWIKI_RIGHTS_PAGE'); # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = loadenv('MEDIAWIKI_RIGHTS_URL');
$wgRightsText = loadenv('MEDIAWIKI_RIGHTS_TEXT');
$wgRightsIcon = loadenv('MEDIAWIKI_RIGHTS_ICON');

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'vector', 'monobook':
$wgDefaultSkin = loadenv('MEDIAWIKI_DEFAULT_SKIN', "chameleon");

$egChameleonLayoutFile=__DIR__ . '/skins/chameleon/layouts/fixedhead.xml';

#Allow full HTML on pages
$wgRawHtml = true;

enableSemantics( 'brothersbrothers.net' );

// It is used on the top page of the UBC Wiki
$wgAllowSlowParserFunctions = true;

// enable categories for upload dialog
$wgUploadDialog = [
    'fields' => [
        'description' => true,
        'date' => true,
        'categories' => true,
    ],
    'licensemessages' => [
        'local' => 'generic-local',
        'foreign' => 'generic-foreign',
    ],
    'comment' => '',
    'format' => [
        'filepage' => '$DESCRIPTION',
        'description' => '$TEXT',
        'ownwork' => '',
        'license' => '',
        'uncategorized' => '',
    ],
];

# disable upload dialog
$wgForeignUploadTargets = [];

$wgUploadPath = loadenv('MEDIAWIKI_UPLOAD_PATH', "$wgScriptPath/images");

$wgFileExtensions = array_merge( $wgFileExtensions,
    array( 'doc', 'xls', 'docx', 'xlsx', 'pdf', 'ppt', 'pptx', 'jpg',
        'tiff', 'odt', 'odg', 'ods', 'odp', 'mp3', 'swf', 'zip', 'xml', 'svg'
));

# don't forget to change PHP and Nginx setting
$wgMaxUploadSize = 1024 * 1024 * 20;
$wgUseCopyrightUpload = "true";

$wgAllowSiteCSSOnRestrictedPages = filter_var(loadenv('MEDIAWIKI_ALLOW_SITE_CSS_ON_RESTRICTED_PAGES', false), FILTER_VALIDATE_BOOLEAN);

$wgGroupPermissions['*']['edit'] = filter_var(loadenv('MEDIAWIKI_ALLOW_ANONYMOUS_EDIT', false), FILTER_VALIDATE_BOOLEAN);

$wgReadOnly = loadenv('MEDIAWIKI_READONLY', null);

$wgLocalisationCacheConf = array(
    'class' => 'LocalisationCache',
    'store' => loadenv('MEDIAWIKI_LOCALISATION_CACHE_STORE', 'detect'),
    'storeClass' => false,
    'storeDirectory' => false,
    'manualRecache' => filter_var(loadenv('MEDIAWIKI_LOCALISATION_CACHE_MANUALRECACHE', false), FILTER_VALIDATE_BOOLEAN),
);

$wgEnableBotPasswords = filter_var(loadenv('MEDIAWIKI_ENABLE_BOT_PASSWORDS', true), FILTER_VALIDATE_BOOLEAN);

@include('CustomExtensions.php');

# some sensible defaults

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'VisualEditor') !== false) {
    # VisualEditor
    # ref: https://www.mediawiki.org/wiki/Extension:VisualEditor
    wfLoadExtension( 'VisualEditor' );
    $wgGroupPermissions['*']['writeapi'] = true;
    // Optional: Set VisualEditor as the default for anonymous users
    // otherwise they will have to switch to VE
    // $wgDefaultUserOptions['visualeditor-editor'] = "visualeditor";

    // Don't allow users to disable it
    $wgHiddenPrefs[] = 'visualeditor-enable';

    // OPTIONAL: Enable VisualEditor's experimental code features
    #$wgDefaultUserOptions['visualeditor-enable-experimental'] = 1;

    # Enabling other Namespaces
    #$wgVisualEditorAvailableNamespaces = [
    #    NS_MAIN => true,
    #    NS_USER => true,
    #    102 => true,
    #    "_merge_strategy" => "array_plus"
    #];

    # https://www.mediawiki.org/wiki/Parsoid#Linking_a_developer_checkout_of_Parsoid
    $PARSOID_INSTALL_DIR = 'vendor/wikimedia/parsoid'; # bundled copy
    wfLoadExtension( 'Parsoid', "$PARSOID_INSTALL_DIR/extension.json" );
    // $wgVirtualRestConfig['modules']['parsoid'] = array(
    //     // URL to the Parsoid instance
    //     'url' => getenv('PARSOID_URL') ? getenv('PARSOID_URL') : 'http://localhost:8000',
    //     // Parsoid "domain" (optional)
    //     'domain' => getenv('PARSOID_DOMAIN') ? getenv('PARSOID_DOMAIN') : 'localhost',
    //     // Parsoid "prefix" (optional)
    //     'prefix' => getenv('PARSOID_PREFIX') ? getenv('PARSOID_PREFIX') : 'localhost'
    // );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'Arrays') !== false) {
    require_once "$IP/extensions/Arrays/Arrays.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'Bootstrap') !== false) {
    require_once "$IP/extensions/Bootstrap/Bootstrap.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'DataTransfer') !== false) {
    wfLoadExtension( 'DataTransfer' );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'HeaderTabs') !== false) {
    wfLoadExtension( 'HeaderTabs' );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'HTMLets') !== false) {
    require_once "$IP/extensions/HTMLets/HTMLets.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'ImageMap') !== false) {
    wfLoadExtension( 'ImageMap' );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'Maps') !== false) {
    require_once "$IP/extensions/Maps/Maps.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'PageForms') !== false) {
    wfLoadExtension( 'PageForms' );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'PageSchemas') !== false) {
    require_once "$IP/extensions/PageSchemas/PageSchemas.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'PageForms') !== false) {
    wfLoadExtension( 'ParserFunctions' );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'Scribunto') !== false) {
    wfLoadExtension( 'Scribunto' );
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticCite') !== false) {
    require_once "$IP/extensions/SemanticCite/SemanticCite.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticCompoundQueries') !== false) {
    require_once "$IP/extensions/SemanticCompoundQueries/SemanticCompoundQueries.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticExtraSpecialProperties') !== false) {
    require_once "$IP/extensions/SemanticExtraSpecialProperties/SemanticExtraSpecialProperties.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticInterlanguageLinks') !== false) {
    require_once "$IP/extensions/SemanticInterlanguageLinks/SemanticInterlanguageLinks.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticMediaWiki') !== false) {
    require_once "$IP/extensions/SemanticMediaWiki/SemanticMediaWiki.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticMetaTags') !== false) {
    require_once "$IP/extensions/SemanticInterMetaTags/SemanticMetaTags.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticResultFormats') !== false) {
    require_once "$IP/extensions/SemanticResultFormats/SemanticResultFormats.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'SemanticScribunto') !== false) {
    require_once "$IP/extensions/SemanticScribunto/SemanticScribunto.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'Validator') !== false) {
    require_once "$IP/extensions/Validator/Validator.php";
}

if (getenv('MEDIAWIKI_EXTENSIONS') && strpos(getenv('MEDIAWIKI_EXTENSIONS'), 'xsl') !== false) {
    require_once "$IP/extensions/xsl/xsl.php";
}

@include('/conf/CustomSettings.php');

if (filter_var(loadenv('DEBUG', false), FILTER_VALIDATE_BOOLEAN)) {
    error_reporting(-1);
    ini_set( 'display_errors', 1  );
    $wgShowExceptionDetails = true;
    $wgCacheDirectory = false;
    $wgDebugLogFile = "/tmp/mw-debug-{$wgDBname}.log";
}

# Give Bureaucrats delete permission
$wgGroupPermissions['bureaucrat']['delete'] = true;
