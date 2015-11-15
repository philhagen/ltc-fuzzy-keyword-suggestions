<html>
    <head>
        <title>YOURLS Link Shortener: Fuzzy Keyword Suggestions</title>
        <!-- example of how to include/reference additional files that are kept in the plugin directory
        <link rel="stylesheet" media="all" href="<?php echo yourls_plugin_url(dirname( __FILE__ ) . '/my_styles.css'); ?>">
        -->
    </head>
    <body>
        <center>
            <h1 style="text-align: center;display: block;margin: 0;padding: 0;color: #202020;font-family: Helvetica;font-size: 26px;font-style: normal;font-weight: bold;line-height: 125%;letter-spacing: normal;">YOURLS Link Shortener: Fuzzy Keyword Suggestions</h1>
            <img align="center" alt="" src="<?php echo yourls_plugin_url(dirname( __FILE__ ) . '/img/yourls_logo.png' ); ?>" width="600" style="max-width: 600px;padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;">
            <p style="text-align: center;margin: 10px 0;padding: 0;font-family: Helvetica;font-size: 16px;line-height: 150%;">This site is used to provide shortened link forwarding, but you have requested a keyword/short link that doesn't exist.</p>

            <?php
                # just show a default block of text if we are NOT intercepting a typo - e.g. intercepting a request for the root YOURLS site
                # use the $keyword_supplied variable to determine if we're intercepting anything
            if (!$keyword_supplied) { ?>
                Contact the site owner/administrator of this site to learn more about their link shortener.  In the mean time, you may be interested in the following resources:<br>
                <ul>
                    <li><a href="http://yourls.org" target="_blank">YOURLS</a>: Home page for the YOURLS engine itself</li>
                    <li><a href="https://github.com/philhagen/ltc-fuzzy-keyword-suggestions" target="_blank">LTC Fuzzy Keyword Suggestions</a>: Code repository for this plugin</li>
                    <li><a href="http://lewestech.com" target="_blank">Lewes Technology Consulting</a>: Author of this plugin</li>
                </ul>
            <?php } else { ?>
                It looks like you have requested a short link that's not in the database.<br>
                <?php
                    # use the $suggested_results variable to determine if any near-matches were found
                if ($suggested_results) {
                    echo 'Here are some links that may be what you are looking for:<br>';
                    echo '<ul>';
                    # within the $query results array, you can access the following fields for each record:
                    # - keyword:  Keyword portion of the short URL
                    # - title:    Destination page title, as stored in the YOURLS database
                    # - url:      Destination page URL
                    # - lev_dist: Integer representing the levenshtein distance between the requested keyword and each candidate result
                    foreach ($query as $query_result) {
                        echo '<li><a href="' . YOURLS_SITE . '/' . $query_result->keyword . '" target="_blank" style="color: #2BAADF;font-weight: normal;text-decoration: underline;">' . $query_result->title . '</a> (Short URL: <tt>' . $query_result->keyword . ')</tt></li>';
                    }
                    echo '</ul>';
                } else {
                    echo 'We could not find any similar short URLs in the database - please send a note to the course author with the URL requested and book/page where it was found';
                }
            }
            ?>
        </center>
    </body>
</html>