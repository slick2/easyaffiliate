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

if (isset($_REQUEST['pageno'])) {
    $pageno = sanitize_paranoid_string($_REQUEST['pageno']);
    $pageno = (int) $pageno;
} else {
    $pageno = 1;
}
?>
<script language="javascript" src="js/author.js"></script>
<form action="" method="post" enctype="multipart/form-data" name="adminform">

    <?php
// DELETE operation of links
    if (isset($_REQUEST['a']) && trim($_REQUEST['a']) == 3) {
        if (isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != "")) {
            $authorId = sanitize_paranoid_string($_REQUEST['authorid']);
            $authorId = (int) $authorId;
            if ($pdo) {
                $query = "Delete from tbllinks where intNumber = ?";
                $bind = array($authorId);
                $result = delete_pdo($query, $bind);
            } else {
                $sql_del = "Delete from tbllinks where intNumber ='" . safeEscapeString($authorId) . "'";
                $del = $d->exec($sql_del);
            }
            header("location:index.php?filename=new_links&amp;pagno=" . $pageno);
            die();
        }
    }
// End of DELETE operation
// UPDATE operation of Links
    $Email = "";
    $Password = "";
    $FirstName = "";
    $lastName = "";
    $Address1 = "";
    $Address2 = "";
    $Zip = "";
    $City = "";
    $State = "";
    $Country = "";
    $Phone = "";
    $Fax = "";
    $action = 1;
    if ((isset($_REQUEST['a']) && trim($_REQUEST['a']) == 2) && isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {
        if (isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != "")) {
            $authorId = sanitize_paranoid_string($_REQUEST['authorid']);
            $authorId = (int) $authorId;
            if ($pdo) {
                $query = "select * from tbllinks where intNumber = ?";
                $bind = array($authorId);
                $result = select_pdo($query, $bind);
            } else {
                $sql = "select * from tbllinks where intNumber ='" . safeEscapeString($authorId) . "'";
                $result = $d->fetch($sql);
            }
            // IF there is not records in database
            if (count($result) <= 0) {
                echo " No Record Found!<br>";
                die();
            }

            // If there is records in database it will be stored in a variable
            // to identify which record is going to update.
            if ($result) {
                foreach ($result as $row) {
                    $Email = stripString($row['email']);
                    $Password = stripString($row['fname']);
                    $FirstName = stripString($row['lname']);
                    $lastName = stripString($row['site_name']);
                    $Address1 = stripString($row['site_addy']);
                    $Address2 = stripString($row['site_desc']);
                    $action = 2;
                }
            }

            // Update operation
            if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update") {
                $varEmail = convert($_REQUEST['user_email']);
                $varFirstName = convert($_REQUEST['user_fname']);
                $varlastName = convert($_REQUEST['user_lname']);
                $varsitename = convert($_REQUEST['site_name']);
                $varsiteaddy = convert($_REQUEST['site_addy']);
                $varsitedesc = convert($_REQUEST['site_desc']);
                $status = 1;
                if ($pdo) {
                    $query = "UPDATE tbllinks SET  fname  = ?, lname = ?, email = ?,
						site_name = ?, site_addy = ?, site_desc = ?, status = ? WHERE intNumber = ?";
                    $bind = array($varFirstName, $varlastName, $varEmail, $varsitename, $varsiteaddy, $varsitedesc, $status, $authorId);
                    $result = update_pdo($query, $bind);
                } else {
                    $varEmail = safeEscapeString($_REQUEST['user_email']);
                    $varFirstName = safeEscapeString($_REQUEST['user_fname']);
                    $varlastName = safeEscapeString($_REQUEST['user_lname']);
                    $varsitename = safeEscapeString($_REQUEST['site_name']);
                    $varsiteaddy = safeEscapeString($_REQUEST['site_addy']);
                    $varsitedesc = safeEscapeString($_REQUEST['site_desc']);


                    $sql_upd = "UPDATE tbllinks SET  fname  = '$varFirstName', lname = '$varlastName', email = '$varEmail',
						site_name = '$varsitename', site_addy = '$varsiteaddy', site_desc = '$varsitedesc', status = '$status' WHERE intNumber ='$authorId'";
                    $result = $d->exec($sql_upd);
                }
                $action = 1;
                header("location:index.php?filename=new_links&amp;pagno=" . $pageno);
                die();
            }
        }
    }
// End Of UPDATE operation
// change status Approve or Not Approve
    if (isset($_REQUEST['s']) && trim($_REQUEST['s']) == 0) {
        if (isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != "")) {
            $id = sanitize_paranoid_string($_REQUEST['authorid']);
            $id = (int) $id;

            if ($pdo) {
                $query = "update tbllinks set status = ? where intNumber = ?";
                $bind = array(1, $id);
                $result = update_pdo($query, $bind);
            } else {
                $update = $d->exec("update tbllinks set status = '1' where intNumber = '" . safeEscapeString($id) . "'");
            }
        }
    }
    if (isset($_REQUEST['s']) && trim($_REQUEST['s']) == 1) {
        if (isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != "")) {
            $id = sanitize_paranoid_string($_REQUEST['authorid']);
            $id = (int) $id;

            if ($pdo) {
                $query = "update tbllinks set status = ? where intNumber = ?";
                $bind = array(0, $id);
                $result = update_pdo($query, $bind);
            } else {
                $update = $d->exec("update tbllinks set status = '0' where intNumber = '" . safeEscapeString($id) . "'");
            }
        }
    }

// INSERT operation of link
    if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {
        $varEmail = convert($_REQUEST['user_email']);
        $varFirstName = convert($_REQUEST['user_fname']);
        $varlastName = convert($_REQUEST['user_lname']);
        $varsitename = convert($_REQUEST['site_name']);
        $varsiteaddy = convert($_REQUEST['site_addy']);
        $varsitedesc = convert($_REQUEST['site_desc']);

        if ($pdo) {
            $query = "INSERT INTO  tbllinks ( email, fname, lname, site_name,
					site_addy, site_desc, status)
					VALUES ( ?, ?, ?, ?, ?, ?, ?)";
            $bind = array($varEmail, $varFirstName, $varlastName, $varsitename, $varsiteaddy, $varsitedesc, 1);
            $result = insert_pdo($query, $bind);
        } else {

            $varEmail = safeEscapeString($_REQUEST['user_email']);
            $varFirstName = safeEscapeString($_REQUEST['user_fname']);
            $varlastName = safeEscapeString($_REQUEST['user_lname']);
            $varsitename = safeEscapeString($_REQUEST['site_name']);
            $varsiteaddy = safeEscapeString($_REQUEST['site_addy']);
            $varsitedesc = safeEscapeString($_REQUEST['site_desc']);


            $sql = "INSERT INTO tbllinks ( email, fname, lname, site_name,
					site_addy, site_desc, status)
					VALUES ( '$varEmail', '$varFirstName', '$varlastName', '$varsitename',
					'$varsiteaddy', '$varsitedesc', '1'";
            $result = $d->exec($sql);
        }
        $_SESSION['msg'] = "<p style='padding-left:25px;'>Your New Link Was Added!</p>";
        header("location:index.php?filename=new_links");
        die();
    }
// End of INSERT operation

    if (isset($_REQUEST['script'])) {
        if (trim($_REQUEST['script']) == 'addauthor' || trim($_REQUEST['script']) == 'editauthor') {
            ?>
            <br />
            <table  border="0" align="center" cellpadding="1" cellspacing="1" width="725">
                <tr>
                    <td class="line_top"><div align="center"> Add/Edit Links</div></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%"  border="0" cellspacing="1" cellpadding="1" class="greyborder" width="725">
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Link : </td>
                                <td>
                                    <select name="author" id="author">
                                        <option>Select Link</option>
                                        <?php
                                        if ($pdo) {
                                            $query = "select * from tbllinks";
                                            $author = select_pdo($query);
                                        } else {
                                            $sql = "select * from tbllinks";
                                            $author = $d->fetch($sql);
                                        }
                                        foreach ($author as $row) {
                                            ?>
                                            <option value="<?= $row['intNumber']; ?>" <?php
                                            if ($row['intNumber'] == $authorId) {
                                                echo "selected";
                                            } else {
                                                echo "damn";
                                            }
                                            ?>><?= stripString($row['site_name']); ?></option>
                                                <?php }
                                                ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td align="left">First Name</td>
                                <td align="left"><input type="text" name="user_fname" value="<? echo $Password?>" MAXLENGTH="50" SIZE="40" ></td>
                            </tr>
                            <tr>
                                <td align="left">Last Name</td>
                                <td align="left"><input type="text" name="user_lname" value="<? echo $FirstName?>" MAXLENGTH="70" SIZE="40" /></td>
                            </tr>
                            <tr>
                                <td align="left">Your Email:</td>
                                <td align="left"><input type="text" name="user_email" value="<? echo $Email?>" MAXLENGTH="50" SIZE="30" /></td>
                            </tr>
                            <tr>
                                <td align="left">Sites Name</td>
                                <td align="left"><input type="text" name="site_name" value="<? echo $lastName?>" MAXLENGTH="75" SIZE="40" /></td>
                            </tr><tr>
                                <td align="left">Site Address:</td>
                                <td align="left"><input type="text" name="site_addy"  value="<? echo $Address1?>" MAXLENGTH="75" SIZE="40" /></td>
                            </tr><tr>
                                <td align="left">Site Desc:</td>
                                <td align="left"><textarea cols='50' rows='10' name='site_desc'><?php echo $Address2 ?></textarea></td>
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
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <?php
        }
    } else {
        if (isset($_SESSION['msg']) && $_SESSION['msg'] > '') {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <br><br>

        <table border="0" align="center" cellpadding="1" cellspacing="0" width='98%'>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="20">
                    <table border="0" align="center" cellpadding="1" cellspacing="0" width='75%'>
                        <tr align="center" class="line_top">
                            <td width="75%">Links List</td>
                            <td width="100%" align="right"><a class="link" href="index.php?filename=new_links&script=addauthor"><font color='#53A8BF'>Add Link</font></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <table  border="0" align="center" cellpadding="2" cellspacing="5" class="greyborder" width ='98%'>
                        <tr class='table_header_menu'>
                            <td>Name</td>
                            <td>Site Name</td>
                            <td>Site Address</td>
                            <td>Email</td>
                            <td>Status</td>
                            <td>Detail</td>
                            <td><div align="center">Edit</div></td>
                            <td><div align="center">Delete</div></td>
                        </tr>


                        <?php
                        /**                         * ************************************
                          PAGING CODE START
                         * ************************************ */
                        if ($pdo) {
                            $query = "SELECT count(*) FROM tbllinks";
                            $result = select_pdo($query);
                        } else {
                            $query = "SELECT count(*) FROM tbllinks";
                            $result = $d->fetch($query);
                        }

                        $numrows = $result[0]['count(*)'];

                        $rows_per_page = 20;
                        $lastpage = ceil($numrows / $rows_per_page);

                        $pageno = (int) $pageno;
                        if ($pageno < 1) {
                            $pageno = 1;
                        } elseif ($pageno > $lastpage) {
                            $pageno = $lastpage;
                        }


                        if ($pdo) {
                            $limit = $pageno - 1;
                            $query = "SELECT * FROM tbllinks ORDER BY status Limit ?,?";
                            $bind = array($limit, $rows_per_page);
                            $sql = select_pdo($query, $bind);
                        } else {
                            $sql_select = "SELECT * FROM tbllinks ORDER BY status Limit " . ($pageno - 1) * $rows_per_page . "," . $rows_per_page;
                            $sql = $obj_db->select($sql_select);
                        }



                        /*                         * ************************************
                          PAGING CODE ENDING
                         * ************************************ */

                        if ($sql) {
                            $i = 0;
                            foreach ($sql as $row) {
                                $i = $i + 1;
                                ?>
                                <tr class="<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>" onMouseOver="this.className = 'Hrhover';"  onMouseOut="this.className = '<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>';">
                                    <td><?php echo stripString($row['fname']) . "" . stripString($row['lname']); ?></td>
                                    <td><?php echo stripString($row['site_name']); ?></td>
                                    <td><?php echo stripString($row['site_addy']); ?></td>
                                    <td><?php echo stripString($row['email']); ?></td>
                                    <?php
                                    if ($row['status'] == 0) {
                                        $intId = $row['intNumber'];
                                        echo "<td><a class='link' href='index.php?filename=new_links&s=0&authorid=$intId'>Unapproved</a></td>";
                                    }
                                    if ($row['status'] == 1) {
                                        $intId = $row['intNumber'];
                                        echo "<td><a class='link' href='index.php?filename=new_links&s=1&authorid=$intId'>APPROVED</a></td>";
                                    }
                                    ?>

                                    <td><a class="link" href="index.php?filename=links_detail&a=4&authorid=<?php echo $row['intNumber']; ?>">Detail</a></td>
                                    <td align="center"><a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&script=editauthor&a=2&authorid=<?php echo $row['intNumber']; ?>&amp;pagno=<?php echo $pageno; ?>"> <img src="images/edit.png" alt="Edit" border="0"> </a></td>
                                    <td align="center"><a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&a=3&authorid=<?php echo $row['intNumber']; ?>&amp;pagno=<?php echo $pageno; ?>" onClick="return confirm('Are you sure you wi
                                                                sh to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
                                </tr>
                                <?php
                            }
                            ?>

                            <input type="hidden" name="pagenum" value="<?php echo $pageno ?>" />
                            <tr>
                                <td colspan="3" ><div align="center"><?php
                                        if ($pageno == 1) {
                                            echo " FIRST PREV ";
                                        } else {
                                            echo " <a href='index.php?filename=new_links&pageno=1'>FIRST</a> ";
                                            $prevpage = $pageno - 1;
                                            echo " <a href='index.php?filename=new_links&pageno=$prevpage'>PREV</a> ";
                                        }

                                        echo " ( Page $pageno of $lastpage ) ";

                                        if ($pageno == $lastpage) {
                                            echo " NEXT LAST ";
                                        } else {
                                            $nextpage = $pageno + 1;
                                            echo " <a href='index.php?filename=new_links&pageno=$nextpage'>NEXT</a> ";
                                            echo " <a href='index.php?filename=new_links&pageno=$lastpage'>LAST</a> ";
                                        }
                                        //extract($_GET);
                                        //echo $pageno;
                                        ?>

                                    </div>
                                </td>
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


