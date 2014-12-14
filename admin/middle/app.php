<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header("location:index.php?filename=adminlogin");
    die();
}

// If member is not login or session is not set
if (!isset($_SESSION['userid']) || $_SESSION['userid'] == '') {
    header("location:index.php?filename=adminlogin");
    die();
}
?>
<center>
    <table border="0" align="center" cellpadding="10" cellspacing="2">
        <tr>
            <td>
                <font size="2">You may edit keywords for each category by choosing each category from the dropdown list. <font color="red">**Important**</font>
                &nbsp;&nbsp;If you <b>add new categories</b>, be sure to add keywords for each new category before approving articles by keywords!<br>
                &nbsp;&nbsp;You can edit forbidden Keywords at the bottom of the dropdown menu list.</font>
            </td>
        <tr>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="300" id="AutoNumber1">
        <tr>
            <td width="100%" valign="top">
                <table BORDER="0" CELLPADDING="0" CELLSPACING="0">
                    <tr>
                        <td>
                            <form name="guideform">
                                <select name="guidelinks" onChange="window.location = document.guideform.guidelinks.options[document.guideform.guidelinks.selectedIndex].value">
                                    <OPTION SELECTED value="">-------Pick a category to add keywords-------

                                        <?php
                                        if ($pdo) {
                                            $query = "SELECT * FROM tblcategories";
                                            $result2 = select_pdo($query);
                                        } else {
                                            $findcat = "SELECT * FROM tblcategories";
                                            $result2 = $d->fetch($findcat);
                                        }
                                        $num_rows2 = count($result2);
                                        if ($num_rows2 > 0) {
                                            foreach ($result2 as $row2) {
                                                $cat[$row2[intID]] = $row2[varCategory];

                                                print "<OPTION value=index.php?filename=app&add=" . $row2[intID] . ">" . $row2[varCategory] . "\n";
                                            }
                                        } else {
                                            print "No keywords in DB";
                                            exit;
                                        }

                                        //$cat[-1]="Forbidden keywords";
                                        print "<OPTION value=index.php?filename=app&add=-1>Forbidden keywords\n";
                                        ?>
                                </select>
                            </form>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php
        if ($_GET['add'] != '') {
            $add = stripslashes($_GET['add']);
        } elseif ($_POST['add'] != '') {
            $add = stripslashes($_POST['add']);
        }

        if ($_GET['operation'] != '') {
            $keywords = stripslashes($_GET['keywords']);
            $operation = stripslashes($_GET['operation']);
        } elseif ($_POST['operation'] != '') {
            $keywords = stripslashes($_POST['keywords']);
            $operation = stripslashes(safeEscapeString($_POST['operation']));
        }


        if (isset($keywords)) {
            if ($operation == "update") {
                if ($pdo) {
                    $query = "UPDATE keywords SET keywords = ? WHERE category_id= ?";
                    $bind = array($keywords, $add);
                    $updresult = update_pdo($query, $bind);
                } else {

                    $keywords = safeEscapeString($keywords);
                    $add = safeEscapeString($add);
                    $updatestr = "UPDATE keywords SET keywords='" . trim($keywords) . "' WHERE category_id='$add'";
                    $updresult = $d->exec($updatestr);
                }
                if ($updresult === false) {
                    print "<p>DB NOT updated!!!</p><br>";
                } else {
                    print "<p>Keywords Added!</p><br>";
                }
            }
            if ($operation == "add") {
                if ($pdo) {
                    $query = "INSERT INTO keywords (category_id, keywords) VALUES (?,?)";
                    $bind = array($add, $keywords);
                    $updresult = insert_pdo($query, $bind);
                } else {
                    $updatestr = "INSERT INTO keywords (category_id, keywords) VALUES ('$add', '$keywords)')";
                    $updresult = $d->exec($updatestr);
                }
                if ($updresult === false) {
                    print "<p>DB NOT updated!!!</p><br>";
                } else {
                    print "<p>Keywords Added!</p><br>";
                }
            }
        }


        if (isset($add)) {
            if ($pdo) {
                $query = "SELECT * FROM keywords WHERE category_id= ?";
                $bind = array($add);
                $result1 = select_pdo($query, $bind);
            } else {
                $findkwds = "SELECT * FROM keywords WHERE category_id='$add'";
                $result1 = $d->fetch($findkwds);
            }
            $num_rows1 = count($result1);
            if ($num_rows1 > 0) {
                $kwds = $result1[0]['keywords'];
            }
            ?>
            <tr>
                <td width="100%" valign="top">
                    <table BORDER="0" CELLPADDING="0" CELLSPACING="0">
                        <tr>
                            <td valign="top">
                                <form action="<?php echo "index.php?filename=app"; ?>" method="POST">
                                    <input type="hidden" name="add" value="<?php print $add; ?>" />
                                    <input type="hidden" name="operation" value="<?php echo isset($kwds) ? 'update' : 'add' ?>" />
                                    <p><font size="2"><b>You are in the "<?php print $cat[$add]; ?>" category now!</b><br />Enter your word or phrase separated by a comma and a space after the comma.
                                        <br />
                                        (Example: article, writing, keyword, search engine optimization)</font>
                                    </p>
                                    <textarea name="keywords" cols="50" rows="10"><? print $kwds;?></textarea> <br />
                                    <input name="Add/Update" type="submit" id="addupdate" value="Add/Update" />

                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td width="100%" valign="top">
        <center>
            <a href="index.php?filename=approve">Click here to approve all articles by your keywords</a>
        </center>
        <br />
        <br />
        </td>
        </tr>
    </table>
</center>
