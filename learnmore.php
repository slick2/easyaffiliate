<?php
///////////////////// TERMS OF USE //////////////////////////
//
//  1. You must keep a link to articlefriendly.com at the bottom of at least one page on the frontend pages.
//  2. You cannot give or sell AF Free to your friends family or anyone else. Anyone that wants AF Free
//     must signup for the download at articlefriendly.com.
//  3. You may use AF Free on as many of your own sites as you wish, but not for clients or others.
//     They must signup for their own copy of AF Free also.
//  4. You may not sell or change and claim AF Free as your own.
/////////////////////////////////////////////////////////////
if (!ob_start("ob_gzhandler"))
    ob_start();

function EscapeString($string) {
    if (is_array($string)) {
        return array_map(__METHOD__, $string);
    }
    if (!empty($string) && is_string($string)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
    }
}

if (!get_magic_quotes_gpc()) {
    $_GET = array_map('EscapeString', $_GET);
    $_POST = array_map('EscapeString', $_POST);
    $_COOKIE = array_map('EscapeString', $_COOKIE);
    $_REQUEST = array_map('EscapeString', $_REQUEST);
}
define('AFFREE', 1);
$page = "learnmore";
include("system/config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta NAME="DESCRIPTION" CONTENT="" />
        <meta NAME="KEYWORDS" CONTENT="" />
        <meta name="robots" content="index, follow" />
        <meta name="distribution" content="Global" />
        <meta NAME="rating" CONTENT="General" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
        <title>
            <?php echo $title; ?> | Learn More
        </title>
    </head>
    <body>
        <div class="content">
            <div class="header_top">
            </div>
            <div class="header">
                <?php require_once(INC . '/menu.php'); ?>
                <div class="sf_left">
                    <?php require_once(INC . '/logo.php'); ?>
                </div>
            </div>
            <div class="header_bottom">
            </div>
            <div class="subheader">
                <p>
                    <?php
                    include("language.php");
                    ?>
                </p>
            </div>
            <div class="header_top">
            </div>
            <div class="left">
                <div class="left_side">
                    <?php require_once(INC . '/left.php'); ?>
                </div>
                <div class="right_side">
                    <div class="article"><h2>Learn More</h2>
                        <p>&nbsp;
                        </p>
                        <p>Welcome to
                            <?php echo $title; ?>. At
                            <?php echo $title; ?>, you're not only provided with a central location for submitting and promoting all of your articles, you also have the key to a powerful resource for website owners      and ezine/newsletter publishers who are searching for excellent content to share with others!
                        </p>
                        <p>When you set up your
                            <?php echo $title; ?> account, you can immediately begin submitting your articles. Writing and submitting articles is an extremely powerful method for promoting your websites, products,             and services. Here are just a few of the benefits you'll receive by submitting articles to
                            <?php echo $title; ?> :
                            <ul>
                                <li><strong>Skyrocket the incoming links pointing to your website - </strong>Search engines love links.  They use links as a gauge for determining how "important" your website is. Search engines view each link                pointing to your site as a "vote" and the more votes your website receives, the higher the rankings!</li>
                                <li><strong>Dramatically boost your website traffic, sales, and newsletter optins - </strong>When you submit articles to
                                    <?php echo $title; ?>, your articles are picked up by other websites, featured in newsletters, and sent out in ezines. This will create additional traffic and result in more exposure!</li>
                                <li><strong>Gain "expert" status and become recognized as an authority in your field </strong> - By publishing information packed articles, you'll soon enjoy the status of being seen as an authority on your topic. This can lead to joint ventures and many other exciting opportunities that you would have never enjoyed otherwise!</li>
                                <li><strong>Enjoy a flood of 100% free, targeted traffic for years to come </strong> - Once you submit your articles and others begin using them, you'll enjoy a flood of free traffic that will come rolling in and it won't cost you a dime. You'll never have to rely on search engines for this free traffic and you won't be able to stop it even if you try! If you are a website owner or a newsletter/ezine publisher,
                                    <?php echo $title; ?>  provides you with a dynamic resource of relevant, on topic content that you can use free of charge. Just grab the articles you want to use, leave them in their original state complete with the resource box providing credit to the author, and use as you wish!
                                    <p>
                                        <?php echo $title; ?> has many different categories of content just waiting for you to uncover! Here are some ideas for using the content in our directory to benefit you:
                                    </p>
                                </li>
                                <li>Use the articles in our directory on your website to provide your visitors with more useful content and keep them coming back for more.</li>
                                <li>Use our articles on your blog to keep it fresh and updated daily with great information.</li>
                                <li>Send our articles out to your newsletter or ezine subscribers and provide them with fresh insights on the most popular of today's topics.
                                    <p>No matter how you end up using
                                        <?php echo $title; ?>, we're sure that you'll benefit greatly and we hope that you continue using our directory for years and years to come as we continue to grow. We're                always looking for ways to improve so if you have a suggestion, don't hesitate to let us know!
                                    </p>
                                </li>

                            </ul>
                        </p>
                        <!-- End index text -->
                    </div>
                    <!-- End Content Area -->
                </div>
            </div>
            <div class="right">
                <?php require_once(INC . '/right.php'); ?>
            </div>
            <div class="header_bottom">
            </div>
            <div class="footer">
                <?php require_once(INC . '/footer.php'); ?>
            </div>
        </div>
    </body>
</html>
<?php
ob_end_flush();