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
<script language="javascript">

    function confirmsubmit()
    {
        var condition = true;
        if (document.adminform.country.value == 0)
        {
            alert("Please enter country name.");
            if (condition == true)
            {
                document.adminform.country.focus();
            }
            condition = false;
            return false;
        }
    }
</script>

<?php
// INSERT operation of country
if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {
    $varCountry = safeEscapeString($_REQUEST['country']);

    if ($pdo) {
        $query = "INSERT INTO tblcountry ( varCountry ) VALUES (?)";
        $bind = array($varCountry);
        $result = insert_pdo($query, $bind);
    } else {
        $sql = "INSERT INTO tblcountry ( varCountry ) VALUES ('$varCountry')";
        $result = $d->fetch($sql);
    }
    header("location:index.php?filename=country");
}
// End of INSERT operation
// DELETE operation of country
if (isset($_REQUEST['a']) && trim($_REQUEST['a']) == 3) {
    if (isset($_REQUEST['countryid']) && trim($_REQUEST['countryid'] != "")) {
        $countryId = sanitize_paranoid_string($_REQUEST['countryid']);
        $countryId = (int) $countryId;
        if ($pdo) {
            $query = "Delete from tblcountry where intId = ?";
            $bind = array($countryId);
            $result = delete_pdo($query, $bind);
        } else {

            $sql_del = "Delete from tblcountry where intId ='$countryId'";
            $del = $d->exec($sql_del);
        }
        header("location:index.php?filename=country");
    }
}
// End of DELETE operation
// UPDATE operation of country
$Country = "";
$action = 1;
if ((isset($_REQUEST['a']) && trim($_REQUEST['a']) == 2) && (!(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit"))) {
    if (isset($_REQUEST['countryid']) && trim($_REQUEST['countryid'] != "")) {
        $countryId = sanitize_paranoid_string($_REQUEST['countryid']);
        $countryId = (int) $countryId;

        if ($pdo) {
            $query = "select * from tblcountry where intId = ?";
            $bind = array($countryId);
            $result = select_pdo($query, $bind);
        } else {
            $sql = "select * from tblcountry where intId ='$countryId'";
            $result = $d->fetch($sql);
        }
        // IF there is no records in database
        if (count($result) <= 0) {
            echo " No Record Found!<br>";
            die();
        }

        // If there is records in database it will be store in a variable
        // to identify which record is going to update.
        if ($result) {
            foreach ($result as $row) {
                $Country = stripString($row['varCountry']);
                $action = 2;
            }
        }

        // Update operation
        if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update") {
            $varCountry = safeEscapeString($_REQUEST['country']);

            if ($pdo) {
                $query = "UPDATE tblcountry SET  varCountry = ? where intId =?";
                $bind = array($varCountry, $countryId);
                $result = update_pdo($query, $bind);
            } else {
                $sql_upd = "UPDATE tblcountry SET  varCountry = '$varCountry' where intId ='$countryId'";
                $result = $d->exec($sql_upd);
            }
            $action = 1;
            header("location:index.php?filename=country");
        }
    }
}
// End Of UPDATE operation

if (isset($_REQUEST['script'])) {
    if (trim($_REQUEST['script']) == 'addcountry' || trim($_REQUEST['script']) == 'editcountry') {
        ?>
        <br><form action="" method="post" enctype="multipart/form-data" name="adminform">
            <table  border="0" align="center" cellpadding="1" cellspacing="1">
                <tr>
                    <td class="line_top"><div align="center">Add/Edit Country</div></td>
                </tr>
                <tr>
                    <td><table width="100%"  border="0" cellspacing="1" cellpadding="1" class="greyborder">
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td><input name="country" type="text" id="country" value="<?php echo $Country; ?>"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2"><div align="center">
                                        <input name="Submit" type="submit" value="<?php
                                        if ($action == 2) {
                                            echo "Update";
                                        } else {
                                            echo "Submit";
                                        }
                                        ?>" onClick="return confirmsubmit();">
                                    </div></td>
                            </tr>
                        </table></td>
                </tr>
            </table>
            <?php
        }
    } else {
        ?>
        <br>
        <table border="0" align="center" cellpadding="1" cellspacing="5" width='95%'>
            <tr>
                <td height="20">
                    <table border="0" align="center" cellpadding="1" cellspacing="0" width='40%'>
                        <tr align="center" class="line_top">
                            <td width="65%" >Country</td>
                            <td width="100%" align="right"><a class="link" href="index.php?filename=country&script=addcountry"><font color='#53A8BF'>Add New</font></a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <td >
                <table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder"  width='40%'>
                    <tr >
                        <td>Country</td>
                        <td><div align="center">Edit</div></td>
                        <td><div align="center">Delete</div></td>
                    </tr>


                    <?php
                    /*                     * ************************************
                      PAGING CODE START
                     * ************************************ */

                    //define('ROW_PER_PAGE',10);
                    $row_per_page = 25;
                    $tablename = "tblcountry";
                    $per_page_keywords = "";
                    $per_page_sorts = "";
                    include("system/paging.inc.php");


                    /*                     * ************************************
                      PAGING CODE ENDING
                     * ************************************ */
                    if ($pdo) {
                        $limit = ($page_no * $row_per_page);
                        $query = "select * from tblcountry ORDER BY varCountry Limit ?,?";
                        $bind = array($limit, $row_per_page);
                        $sql = select_pdo($query, $bind);
                    } else {

                        $sql_select = "select * from tblcountry ORDER BY varCountry Limit " . ($page_no * $row_per_page) . "," . $row_per_page;
                        $sql = $d->fetch($sql_select);
                    }

                    if ($sql) {
                        $i = 0;
                        foreach ($sql as $row) {
                            $i = $i + 1;
                            ?>
                            <tr class="<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>" onMouseOver="this.className = 'Hrhover';"  onMouseOut="this.className = '<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>';">
                                <td><?php echo stripString($row['varCountry']); ?></td>
                                <td align="center"><a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&script=editcountry&a=2&countryid=<?php echo $row['intId']; ?>"> <img src="images/edit.png" alt="Edit" border="0"> </a></td>
                                <td align="center"><a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&a=3&countryid=<?php echo $row['intId']; ?>" onClick="return confirm('Are you sure to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3" ><div align="center">
                                    <?php
                                    // query line==== Limit ".($page_no*$row_per_page).",".$row_per_page;
                                    // PAGING FUNCTION FOR PAGE NUMBER DISPLAYED
                                    pagindet_atbotttom_page($div_page_no, $page_no, $req_querystr, $total_db_rec, $row_per_page);
                                    ?>
                                </div></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
            </tr>
        </table>
    </form>
    <?php
}
