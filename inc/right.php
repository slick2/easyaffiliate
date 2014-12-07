<?php
if (!defined('AFFREE')) {
    die('You cannot access this page directly!');
}
?>
<div class="box_top">
    <h2>Navigation</h2>
</div>
<div class="box">
    <p><a href='signup.php' <?php
        if ($page == 'signup') {
            echo 'class="location"';
        }
        ?>>Signup</a></p>
    <p><a href='learnmore.php' <?php
        if ($page == 'learnmore') {
            echo 'class="location"';
        }
        ?>>Learn More</a></p>
    <p><a href='terms.php' <?php
          if ($page == 'terms') {
              echo 'class="location"';
          }
          ?>>Terms Of Service</a></p>
    <p><a href='top_authors.php' <?php
        if ($page == 'top') {
            echo 'class="location"';
        }
        ?>>Top Articles</a></p>
    <p><a href='aboutus.php' <?php
        if ($page == 'about') {
            echo 'class="location"';
        }
        ?>>About Us</a></p>
    <p><a href='privacy.php' <?php
if ($page == 'privacy') {
    echo 'class="location"';
}
        ?>>Privacy Policy</a></p>
    <p><a href='rss.php'>RSS</a></p>

</div>
<div class="box_bottom"></div>

    <?php
    if ($page != 'articledetail') {
        ?>
    <div class="box_top">
        <h2>Featured Authors</h2>
    </div>
    <div class="box">
        <?php
/////////// Featured Authors Code /////////////
        if ($pdo) {
            $query = '';
            $query = "SELECT intId,authPhoto,varFirstName,varlastName,dtRegisteredDate,varCity,varState
          FROM tblauthor WHERE intStatus= 1 ORDER BY RAND() LIMIT 5";
            $myquery = select_pdo($query, "", "feat_auths.af", 3600);
        } else {

            $myresults = '';
            $myresults = "SELECT intId,authPhoto,varFirstName,varlastName,dtRegisteredDate,varCity,varState
              FROM tblauthor WHERE intStatus= 1 ORDER BY RAND() LIMIT 5";
            $myquery = $d->fetch($myresults, "daily", "feat_auths.af");
        }
        foreach ($myquery as $row) {
            ?>
            <p>
        <?php
        if ($row['authPhoto'] > "") {
            echo "<img src='author/" . $row['authPhoto'] . "' style='border:1px solid;color: black;' width='44' height='40' alt='Article Friendly Author Photo'>";
        } else {
            echo "<img src='images/male_mem.jpg' style='border:1px solid;color: black' width='44' height='40' alt='Article Friendly Author Photo'>";
        }
        $name = stripslashes(htmlentities($row['varFirstName'] . " " . $row['varlastName'], ENT_QUOTES, "UTF-8"));
        $city = stripslashes(htmlentities($row['varCity'], ENT_QUOTES, "UTF-8"));
        $state = stripslashes(htmlentities($row['varState'], ENT_QUOTES, "UTF-8"));
        ?>
            </p>
            <p>
                <b><?php echo $name; ?></b><br>
                Joined :<?php echo $row['dtRegisteredDate']; ?><br>
                City : <?php echo $city; ?><br>
                State : <?php echo $state; ?><br>

                <a  href="authordetail.php?autid=<?php echo htmlentities($row['intId']) ?>&amp;script=browse" style="color:blue;">View My Articles</a><br><br>
            </p>
        <?php
    }
    ?>
    </div>
    <div class="box_bottom"></div>

    <?php
} else {
    ?>

    <div class="box_top">
        <h2>Actions</h2>
    </div>
    <div class="box">
        <p><a href="printart.php?artname=<?= $artname ?>&act=print" target=_blank rel="nofollow" class="a_right">
                <img src="images/printer.gif" width="16" height="16" border="0"> Print This Article</a></p>
        <p>&nbsp;</p>
        <p><a href="" onclick='window.external.AddFavorite(location.href, document.title);'>
                <img src="images/addtofavorites.gif" width="16" height="16" border="0"> Add To Favorites</a> </p>
        <p>&nbsp;</p>
        <p><form action="tell_a_friend.php" method="post"><div align="center"><input type="submit" class="a_right" value="Email a Friend"></div>
    <?php
    $pass = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
    ?>
            <input type="hidden" name="art" value="<?php echo $pass ?>">
        </form>
    </p>
    </div>
    <?php
}
if (isset($_SESSION['uid']) && trim($_SESSION['uid']) != "") {
    ?>

    <div class="box_bottom"></div>
    <div class="box_top">
        <h2>Author Links</h2>
    </div>
    <div class="box">
        <p>My Settings</font></p>
        <p><a href="editaccount.php" class="a_right"> Edit Account</a> </p>
        <p><a href="changepass.php" class="a_right">  Change Password</a></p>
        <p><a href="myarticle.php" class="a_right"> My Articles</a></p>
        <p><a href="check.php" class="a_right"> Upload a Photo</a></p>
        <p><a href="thankyou.php?logout=yes" class="a_right"> LOG OUT</a></p>
    </div>
    <div class="box_bottom"></div>
    <?php
}
?>