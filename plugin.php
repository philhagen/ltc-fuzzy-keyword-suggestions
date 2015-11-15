<?php
/*
Plugin Name: Fuzzy Keyword Suggestions
Plugin URI: https://github.com/philhagen/ltc-fuzzy-keyword-suggestions
Description: This plugin performs a levenshtein string lookup for any short url which does not appear in the database, then presents a list of possible matches
Version: 1.0
Author: Phil Hagen
Author URI: http://lewestech.com
*/

# no direct calls to this script allowed
if( !defined( 'YOURLS_ABSPATH' ) ) die();

# handle activation - determine if there is a LEVENSHTEIN() function available
# TODO: this runs with every page load.  Need to wrap this in a conditional that only runs once upon activation.
global $ydb;
$query = $ydb->get_results("SELECT LEVENSHTEIN('teststring1', 'teststring2') AS lev_dist");
if (!$query) {
    $levenshtein_test_pass = FALSE;
} else {
    $lev_test_dist = $query[0]->lev_dist;
    if ($lev_test_dist == 1) {
        $levenshtein_test_pass = TRUE;
    } else {
        $levenshtein_test_pass = FALSE;
    }
}

# if the test fails (due to lack of a levenshtein() function or incorrect results), return a message.
# this output is captured by yourls and prevents module activation
if (!$levenshtein_test_pass) {
?>
    ERROR: MySQL "LEVENSHTEIN()" function not present or could not be verified.<br>
    See the following pages for potential solutions:
    <ul><li><a href="https://github.com/jmcejuela/Levenshtein-MySQL-UDF" target="_blank">GitHub user jmcejuela's User-Defined Function (UDF)</a></li>
    <li><a href="http://stackoverflow.com/questions/12617348/mysql-levenshtein" target="_blank">Create a function within MySQL</a><br>
    (Also see the <tt>mysql_levenshtein_function.sql</tt> file included with this plugin.)</li></ul>
    <strong>Note</strong>: UDF module has shown 500-1000x faster performance than the SQL function.
    Plugin failed to activate.<br>
<?php
}
# TODO: end code that needs only-run-on-activation conditional

yourls_add_action( 'redirect_keyword_not_found', 'ltc_fuzzy_suggest' );
yourls_add_action( 'loader_failed', 'ltc_fuzzy_suggest' );
function ltc_fuzzy_suggest($ltc_request) {
    if ($_SERVER['REQUEST_URI'] == '/') {
        # homepage redirect
        $keyword_supplied = FALSE;

    } else {
        $keyword_supplied = TRUE;
        header('X-PHP-Response-Code: 404', true, 404);

        global $ydb;
        $table_url = YOURLS_DB_TABLE_URL;

        $ltc_keyword = yourls_sanitize_keyword($ltc_request[0]);

        $query = $ydb->get_results("SELECT keyword, url, title, LEVENSHTEIN('$ltc_keyword', keyword) AS `lev_dist` FROM `$table_url` HAVING `lev_dist` < 3 ORDER BY `lev_dist` DESC");
        if ($query) {
            $suggested_results = TRUE;

        } else {
            $suggested_results = FALSE;
        }
    }

    $default_template_file = yourls_plugin_url(dirname( __FILE__ ) . '/template-default.php');
    $custom_template_file = yourls_plugin_url(dirname( __FILE__ ) . '/template.php');
    if (file_exists($custom_template_file)) {
        require($custom_template_file);
    } else {
        require($default_template_file);
    }

    die();
}

?>