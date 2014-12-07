<?php
if (!defined('AFFREE')) {
    die('You cannot access this page directly!');
}
?>
<div class="sf_right">
    <div class="sf_search">
        <script language="JavaScript" type="text/javascript">
            function call()
            {
                var key = document.getElementById("keyword");
                window.location.href = 'search.php?q=' + key.value;
            }
        </script>
        <!-- Header search box -->
        <p><b>Search:</b> <input type="text" id="keyword" /> <input type="submit" value="Go" class="submit" onclick="return call();" /> &nbsp; Article Search Only!</p>

    </div>
    <div id="nav">
        <ul>

            <li <?php if ($page == 'home') {
    echo 'id="current"';
} ?>><a href="index.php">Home</a></li>
            <li <?php if ($page == 'submit') {
    echo 'id="current"';
} ?>><a href="submitart.php">Submit Articles</a></li>
            <li <?php if ($page == 'login') {
    echo 'id="current"';
} ?>><a href="login.php">Login</a></li>
            <li <?php if ($page == 'links') {
    echo 'id="current"';
} ?>><a href="links.php">Links</a></li>
            <li <?php if ($page == 'rssfeeds') {
    echo 'id="current"';
} ?>><a href="rssfeeds.php">RSS Feeds</a></li>
            <li <?php if ($page == 'contact') {
    echo 'id="current"';
} ?>><a href="contact.php">Contact</a></li>
        </ul>
    </div>
</div>