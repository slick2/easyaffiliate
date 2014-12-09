<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header("location:index.php?filename=adminlogin");
    die();
}
if (!isset($_SESSION['userid']) || $_SESSION['userid'] == '') {
    header("location:index.php?filename=adminlogin");
    die();
}

if ($_SESSION['acctype'] == '1') {
    $_SESSION['msg'] = "<p>Sorry, but you don't have permission to view that page.";
    header("location:index.php?filename=thankyou");
    die();
}
?>
<script language="javascript" src="js/adminuser.js"></script>
<form action="" method="post" enctype="multipart/form-data" name="adminform">
    <?php
// INSERT into adminuser.
    if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {
        $varFullname = $_REQUEST['fullname'];
        $varUsername = $_REQUEST['username'];
        $varPassword = $_REQUEST['password'];
        $salt = generate_salt();
        $varPassword = shadow($salt . $varPassword);
        $varLastLogginIP = $_SERVER['REMOTE_ADDR'];
        if (isset($_REQUEST['admin_permission']) && $_REQUEST['admin_permission'] == 2) {
            $intStatus = 2;
        } else {
            $intStatus = 1;
        }
        $varEmail = $_REQUEST['aemail'];


        if ($pdo) {
            $query = "select varUsername from tbladminuser where varUsername = ?";
            $bind = array($varUsername);
            $sameuser = select_pdo($query, $bind);
        } else {

            $varUsername = safeEscapeString($varUsername);
            // If same varUsername  is there then it will display an error message.
            $sameuser = $d->fetch("select varUsername from tbladminuser where varUsername ='$varUsername'");
        }
        $count = count($sameuser);
        if ($count > 0) {
            ?>
            <table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle" ><strong>
                            <? echo "That name has been taken .. please enter another user name.  <br> <br>
                            Please click on 'Admin user' link to try again.";
                            die();
                            ?>

                        </strong></td>
                </tr>
            </table>
            <?php
        }
        if ($pdo) {

            $query = "INSERT INTO tbladminuser ( varFullname , varUsername , varPassword ,
										varLastLogginIP , ttLastLogginDatetime , intStatus , varEmail, admin_salt )
								VALUES ( ?, ?, ?,
											?, NOW() , ?, ?, ?)";
            $bind = array($varFullname, $varUsername, $varPassword, $varLastLogginIP, $intStatus, $varEmail, $salt);
            $result = select_pdo($query, $bind);
        } else {
            $varFullname = safeEscapeString($varFullname);
            $varPassword = safeEscapeString($varPassword);
            $varUsername = safeEscapeString($varUsername);
            $varEmail = safeEscapeString($varEmail);
            $varLastLogginIP = safeEscapeString($varLastLogginIP);
            $insert = "INSERT INTO `tbladminuser` ( `varFullname`, `varUsername`, `varPassword`,
										`varLastLogginIP`, `ttLastLogginDatetime`, `intStatus`, `varEmail`, `admin_salt` )
								VALUES ( '$varFullname', '$varUsername', '$varPassword',
											'$varLastLogginIP', NOW( ) , '$intStatus', '$varEmail','$salt')";
            $sql = $d->exec($insert);
        }
        header("location:index.php?filename=adminuser");
        die();
    }

// DELETE record from adminuser
    if (isset($_REQUEST['a']) && trim($_REQUEST['a']) == 3) {
        if (isset($_REQUEST['adminid']) && trim($_REQUEST['adminid'] != "")) {

            $userid = sanitize_paranoid_string($_REQUEST['adminid']);


            if ($pdo) {
                $query = "Delete from tbladminuser where intId =?";
                $bind = array($userid);
                $del = delete_pdo($query, $bind);
            } else {

                $sql_del = "Delete from tbladminuser where intId ='" . safeEscapeString($userid) . "'";
                $del = $d->exec($sql_del);
            }
            header("location:index.php?filename=adminuser");
            die();
        }
    }

// UPDATE the adminuser
    $Fullname = "";
    $Username = "";
    $Password = "";
    $Email = "";
    $action = 1;
    if ((isset($_REQUEST['a']) && trim($_REQUEST['a']) == 2) && (!(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit"))) {
        if (isset($_REQUEST['adminid']) && trim($_REQUEST['adminid']) != "") {
            $userid = sanitize_paranoid_string($_REQUEST['adminid']);


            if ($pdo) {
                $query = "select * from tbladminuser where intId =?";
                $bind = array($userid);
                $result = select_pdo($query, $bind);
            } else {
                $sql = "select * from tbladminuser where intId ='" . safeEscapeString($userid) . "'";
                $result = $d->fetch($sql);
            }
            if (count($result) <= 0) {
                echo " No Record Found!<br>";
                die();
            }
            if ($result) {
                foreach ($result as $row) {
                    $Fullname = $row['varFullname'];
                    $Username = $row['varUsername'];
                    $Password = $row['varPassword'];

                    if (isset($_REQUEST['admin_permission']) && $_REQUEST['admin_permission'] == 2) {
                        $intStatus = 2;
                    } else {
                        $intStatus = 1;
                    }
                    $Email = $row['varEmail'];
                    $action = 2;
                }
            }
            if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update") {
                $varFullname = $_REQUEST['fullname'];
                $varUsername = $_REQUEST['username'];
                $varPassword = $_REQUEST['password'];
                $salt = generate_salt();
                $varPassword = shadow($salt . $varPassword);
                $varLastLogginIP = $_SERVER['REMOTE_ADDR'];
                $varEmail = $_REQUEST['aemail'];

                if ($pdo) {
                    $query = "UPDATE tbladminuser SET  varFullname = ?, varUsername = ?, varPassword = ?,
						 varLastLogginIP = ?, intStatus = ?, varEmail = ?, admin_salt = ?
						where intId = ?";
                    $bind = array($varFullname, $varUsername, $varPassword, $varLastLogginIP, $intStatus, $varEmail, $salt, $userid);
                    $result = update_pdo($query, $bind);
                } else {

                    $varFullname = safeEscapeString($varFullname);
                    $varUsername = safeEscapeString($varUsername);
                    $varPassword = safeEscapeString($varPassword);
                    $varLastLogginIP = safeEscapeString($varLastLogginIP);
                    $varEmail = safeEscapeString($varEmail);

                    $sql_upd = "UPDATE tbladminuser SET  varFullname = '$varFullname', varUsername = '$varUsername', varPassword = '$varPassword',
						 varLastLogginIP = '$varLastLogginIP', intStatus = '$intStatus', varEmail = '$varEmail', admin_salt= '$salt'
						where intId ='$userid'";
                    $result = $d->exec($sql_upd);
                }
                $action = 1;
                header("location:index.php?filename=adminuser");
                die();
            }
        }
    }
    ?>
    <?php
    if (isset($_REQUEST['script'])) {
        if (trim($_REQUEST['script']) == 'addadmin' || trim($_REQUEST['script']) == 'editadmin') {
            ?>
            <br><br>
            <table align="center" cellpadding="2" cellspacing="2">
                <tr>
                    <td class="line_top">
                        Admin User..</td>
                </tr>
                <tr >
                    <td>
                        <table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder">
                            <tr>
                                <td>Full Name : </td>
                                <td><input name="fullname" type="text" id="fullname" value="<?= $Fullname; ?>" size="35">
                                </td>
                            </tr>
                            <tr>
                                <td>User Name : </td>
                                <td><input name="username" type="text" id="username" value="<?= $Username; ?>" size="35"></td>
                            </tr>
                            <tr>
                                <td>Password : </td>
                                <td><input name="password" type="password" id="password2" value="<?= $Password; ?>"></td>
                            </tr>
                            <tr>
                                <td>Confirm Password : </td>
                                <td><input name="cpassword" type="password" id="cpassword" value="<?= $Password; ?>"></td>
                            </tr>
                            <tr>
                                <td>Email : </td>
                                <td><input name="aemail" type="text" id="aemail" value="<?= $Email; ?>" size="35"></td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="left">Give right to admin?
                                    </div></td><td><input name="admin_permission" type="checkbox" id="admin_permission" value="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input name="Submit" type="submit" value="<?php echo ($action == 2) ? "Update" : "Submit"; ?>" onClick="return confirmsubmit();">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <?php
        }
    } else {
        ?>
        <br><br>
        <table border="0" align="center" cellpadding="1" cellspacing="0" width='75%'>
            <tr>
                <td height="20">
                    <table border="0" align="center" cellpadding="1" cellspacing="0">
                        <tr align="center" class="line_top">
                            <td width="100%">Admin User</td>
                            <td width="100%" align="right">
                                <?php
                                if (isset($_SESSION['acctype'])) {
                                    if (trim($_SESSION['acctype']) == 2) {
                                        ?>
                                        <a class="link" href="index.php?filename=adminuser&script=addadmin">New</a>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <td >
                <table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder" width='75%'>
                    <tr class='table_header_menu'>
                        <td>Full Name</td>
                        <td>User Name</td>
                        <td>Password</td>
                        <td>Last IP</td>
                        <td>E-mail</td>
                        <td><div align="center">Edit</div></td>
                        <td><div align="center">Delete</div></td>
                    </tr>
                    <?php
                    /*                     * ************************************
                      PAGING CODE START
                     * ************************************ */

                    //define('ROW_PER_PAGE',10);
                    //$rowperpage=5;
                    $tablename = "tbladminuser";
                    $per_page_keywords = "";
                    $per_page_sorts = "";
                    include("system/paging.inc.php");


                    /*                     * ************************************
                      PAGING CODE ENDING
                     * ************************************ */

                    if ($pdo) {
                        $query = "select * from tbladminuser Limit " . ($page_no * $row_per_page) . "," . $row_per_page;

                        $sql = select_pdo($query);
                    } else {
                        $sql_select = "select * from tbladminuser Limit " . ($page_no * $row_per_page) . "," . $row_per_page;
                        $sql = $d->fetch($sql_select);
                    }
                    if ($sql) {
                        $i = 0;
                        foreach ($sql as $row) {
                            $itemid = $row['intId'];
                            $i = $i + 1;
                            ?>
                            <tr class="<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>" onMouseOver="this.className = 'Hrhover';"  onMouseOut="this.className = '<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>';">
                                <td><?php echo $row['varFullname']; ?></td>
                                <td><?php echo $row['varUsername']; ?></td>
                                <td><?php echo $row['varPassword']; ?></td>
                                <td><?php echo $row['varLastLogginIP']; ?></td>
                                <td><?php echo $row['varEmail']; ?></td>
                                <td align="center">
                                    <?php
                                    if (isset($_SESSION['acctype']) && trim($_SESSION['acctype']) == 2) {
                                        ?>
                                        <a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&script=editadmin&a=2&adminid=<?php echo $row['intId']; ?>"> <img src="images/edit.png" alt="Edit" border="0"> </a> </td>
                                    <?php
                                }
                                ?>
                                <td align="center">
                                    <?php
                                    if (isset($_SESSION['acctype']) && trim($_SESSION['acctype']) == 2) {
                                        ?>
                                        <a class="link" href="index.php?filename=<?php echo urlencode($_REQUEST['filename']); ?>&a=3&adminid=<?php echo $row['intId']; ?>" onClick="return confirm('Are you sure you want to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a> </td>
                                            <?php
                                        }
                                        ?>
                            </tr>

                            <?php
                        }
                        ?>
                        <tr >
                            <td colspan="7" ><?php
                                // query line==== Limit ".($page_no*$row_per_page).",".$row_per_page;
                                // PAGING FUNCTION FOR PAGE NUMBER DISPLAYED
                                pagindet_atbotttom_page($div_page_no, $page_no, $req_querystr, $total_db_rec, $row_per_page);
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table></td>
            </tr>
        </table>
    </form>
    <?php
}

