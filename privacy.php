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
$page = 'privacy';
include("system/config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta NAME="DESCRIPTION" CONTENT="">
                <meta NAME="KEYWORDS" CONTENT="">
                    <meta name="robots" content="index, follow">
                        <meta name="distribution" content="Global">
                            <meta NAME="rating" CONTENT="General">
                                <link rel="stylesheet" href="css/style.css" type="text/css" />
                                <title>
                                    <?php echo $title; ?> | Privacy
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
                                                <div class="article"><h2>Privacy Policy</h2>
                                                    <p>&nbsp;
                                                    </p>
                                                    <p>
                                                        <?php echo $title; ?> strives to offer its visitors the many advantages of Internet technology and to provide an interactive and personalized experience.  We may use Personally Identifiable Information (your name, e-mail address, street address, telephone number) subject to the terms of this privacy policy.  We will never sell, barter, or rent your email address to any unauthorized third party. Period.
                                                        <p><strong>How we gather information from users </strong>
                                                        </p>
                                                        <p>How we collect and store information depends on the page you are visiting, the activities in which you elect to participate and the services provided.    For example, you may be asked to provide information when you register for access to certain portions of our site or request certain features, such as    newsletters or when you make a purchase. You may provide information when you participate in sweepstakes and contests, message boards and chat rooms,    and other interactive areas of our site. Like most Web sites,
                                                            <?php echo $title; ?>  also collects information automatically and through the use of electronic    tools that may be transparent to our visitors. For example, we may log the name of your Internet Service Provider or use cookie technology to recognize    you and hold information from your visit. Among other things, the cookie may store your user name and password, sparing you from having to re-enter that    information each time you visit, or may control the number of times you encounter a particular advertisement while visiting our site. As we adopt    additional technology, we may also gather information through other means. In certain cases, you can choose not to provide us with information,    for example by setting your browser to refuse to accept cookies, but if you do you may be unable to access certain portions of the site or may be    asked to re-enter your user name and password, and we may not be able to customize the site's features according to your preferences.
                                                        </p>
                                                        <p><strong>What we do with the information we collect </strong>
                                                        </p>
                                                        <p>Like other Web publishers, we collect information to enhance your visit and deliver more individualized content and advertising. We respect your    privacy and do not share your information with anyone.
                                                        </p>
                                                        <p>Aggregated Information (information that does not personally identify you) may be used in many ways. For example, we may combine information about    your usage patterns with similar information obtained from other users to help enhance our site and services (e.g., to learn which pages are visited    most or what features are most attractive). Aggregated Information may occasionally be shared with our advertisers and business partners. Again, this    information does not include any Personally Identifiable Information about you or allow anyone to identify you individually.
                                                        </p>
                                                        <p>We may use Personally Identifiable Information collected on
                                                            <?php echo $title; ?> to communicate with you about your registration and customization    preferences; our Terms of Service and privacy policy; services and products offered by
                                                            <?php echo $title; ?>  and other topics we think you might find    of interest.
                                                        </p>
                                                        <p>Personally Identifiable Information collected by
                                                            <?php echo $title; ?> may also be used for other purposes, including but not limited to site    administration, troubleshooting, processing of e-commerce transactions, administration of sweepstakes and contests, and other communications with you.    Certain third parties who provide technical support for the operation of our site (our Web hosting service for example) may access such information.    We will use your information only as permitted by law. In addition, from time to time as we continue to develop our business, we may sell, buy, merge    or partner with other companies or businesses. In such transactions, user information may be among the transferred assets. We may also disclose your    information in response to a court order, at other times when we believe we are reasonably required to do so by law, in connection with the collection    of amounts you may owe to us, and/or to law enforcement authorities whenever we deem it appropriate or necessary. Please note we may not provide you    with notice prior to disclosure in such cases.
                                                        </p>
                                                        <p><strong>Affiliated sites, linked sites and advertisements </strong>
                                                        </p>
                                                        <p>
                                                            <?php echo $title; ?> expects its partners, advertisers and affiliates to respect the privacy of our users. Be aware, however, that third parties,    including our partners, advertisers, affiliates and other content providers accessible through our site, may have their own privacy and data collection    policies and practices. For example, during your visit to our site you may link to, or view as part of a frame on a
                                                            <?php echo $title; ?>  page,    certain content that is actually created or hosted by a third party. Also, through
                                                            <?php echo $title; ?>  you may be introduced to, or be able to access,    information, Web sites, features, contests or sweepstakes offered by other parties.
                                                            <?php echo $title; ?>  is not responsible for the actions or policies    of such third parties. You should check the applicable privacy policies of those third parties when providing information on a feature or page operated    by a third party.
                                                        </p>
                                                        <p>While on our site, our advertisers, promotional partners or other third parties may use cookies or other technology to attempt to identify some of    your preferences or retrieve information about you. For example, some of our advertising is served by third parties and may include cookies that enable    the advertiser to determine whether you have seen a particular advertisement before. Other features available on our site may offer services operated    by third parties and may use cookies or other technology to gather information.
                                                            <?php echo $title; ?>  does not control the use of this technology by    third parties or the resulting information, and is not responsible for any actions or policies of such third parties.
                                                        </p>
                                                        <p>You should also be aware that if you voluntarily disclose Personally Identifiable Information on message boards or in chat areas, that information    can be viewed publicly and can be collected and used by third parties without our knowledge and may result in unsolicited messages from other individuals    or third parties. Such activities are beyond the control of
                                                            <?php echo $title; ?>  and this policy.
                                                            <p><strong>Children </strong>
                                                            </p>
                                                            <p>
                                                                <?php echo $title; ?> does not knowingly collect or solicit Personally Identifiable Information from or about children under 13 except as permitted by law.       If we discover we have received any information from a child under 13 in violation of this policy, we will delete that information immediately. If you       believe
                                                                <?php echo $title; ?>  has any information from or about anyone under 13, please contact us at the address listed below.
                                                            </p>
                                                            <p><strong>Contacting Us </strong>
                                                            </p>
                                                            <p>We can be reached by emailing us.
                                                            </p>
                                                            <p><strong>Changes to this Policy </strong>
                                                            </p>
                                                            <p>
                                                                <?php echo $title; ?> reserves the right to change this policy at any time. Please check this page periodically for changes. Your continued use of our      site following the posting of changes to these terms will mean you accept those changes. Information collected prior to the time any change is posted will      be used according to the rules and laws that applied at the time the information was collected.
                                                            </p>
                                                            <p><strong>Governing law </strong>
                                                            </p>
                                                            <p>This policy and the use of this Site are governed by Washington State law. If a dispute arises under this Policy we agree to first try to resolve it      with the help of a mutually agreed-upon mediator in the following location: Seattle. Any costs and fees other than attorney fees associated with the      mediation will be shared equally by each of us.
                                                            </p>
                                                            <p>If it proves impossible to arrive at a mutually satisfactory solution through mediation, we agree to submit the dispute to binding arbitration at the      following location: Seattle, under the rules of the American Arbitration Association. Judgment upon the award rendered by the arbitration may be entered      in any court with jurisdiction to do so.
                                                            </p>
                                                            <p>This statement and the policies outlined herein are not intended to and do not create any contractual or other legal rights in or on behalf of any party.
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
                                                            ?>