<?php
/**
 * @package Article Friendly
 */
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
<script language="javascript" src="js/article.js"></script>
<form action="" method="post" enctype="multipart/form-data" name="adminform">
    <?php
    if (isset($_REQUEST['pageno'])) {
        $pageno = sanitize_paranoid_string($_REQUEST['pageno']);
    } else {
        $pageno = 1;
    }

    /**
     * Function : GetChild
     * Function for displaying categories in drop down menu
     * @param type $ParentID
     * @param type $num
     * @param type $selected
     * @param type $obj_db
     * @param type $check
     * @return string
     */
    function GetChild($ParentID, $num, $selected, $obj_db, $check) {
        $value = "";
        $c = "";
        $d = new db(0);
        for ($i = 0; $i < $num; $i++) {
            $c = $c . "&nbsp;&nbsp;-&nbsp;&nbsp;";
        }

        if ($pdo) {
            $query = "SELECT * "
                . "FROM tblcategories "
                . "WHERE intParentID = ? "
                . "ORDER BY varCategory ASC";
            $bind = array($ParentID);
            $RsC = select_pdo($query, $bind);
        } else {

            $query_RsC = "SELECT * "
                . "FROM tblcategories "
                . "WHERE intParentID = " . $ParentID
                . "  ORDER BY varCategory ASC";
            $RsC = $d->fetch($query_RsC);
        }
        $cnt = count($RsC);

        if ($RsC) {
            for ($i = 0; $i < $cnt; $i++) {
                if ($check == 1) {
                    if ($selected == $RsC[$i]['intID']) {
                        $value = $value . "<option value='" . $RsC[$i]['intID'] . "' selected>" . $c . $RsC[$i]['varCategory'] . "</option>";
                    } else {
                        $value = $value . "<option value='" . $RsC[$i]['intID'] . "' >" . $c . $RsC[$i]['varCategory'] . "</option>";
                    }
                }
                if ($check == 2) {
                    $value = $value . "," . $RsC[$i]['intID'];
                }
                $value = $value . "" . GetChild($RsC[$i]['intID'], $num + 1, $selected, $obj_db, $check);
            }
        }
        return $value;
    }

    /**
     * Insert Method
     */
    if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {
        $intAuthorId = sanitize_paranoid_string($_REQUEST['author']);
        $intCategory = sanitize_paranoid_string($_REQUEST['category']);
        $varArticleTitle = convert($_REQUEST['title']);
        $textSummary = convert($_REQUEST['summery']);
        $varKeywords = convert($_REQUEST['keywords']);
        $textArticleText = convert($_REQUEST['textarea']);
        $textResource = convert($_REQUEST['resources']);

        if ($pdo) {
            $query = "INSERT INTO tblarticles ( "
                . "intAuthorId , "
                . "intCategory , "
                . "varArticleTitle , "
                . "textSummary ,"
                . "varKeywords ,"
                . "textArticleText , "
                . "textResource , "
                . "intStatus ,"
                . "intHit, "
                . "ttSubmitDate) "
                . "VALUES ( ?, ?, ?, ?,?, ?, ?, ?, ?, NOW())";
            $bind = array($intAuthorId, $intCategory, $varArticleTitle, $textSummary, $varKeywords, $textArticleText, $textResource, 0, 0);
            $result = insert_pdo($query, $bind);
        } else {

            $intAuthorId = safeEscapeString($_REQUEST['author']);
            $intCategory = safeEscapeString($_REQUEST['category']);
            $varArticleTitle = safeEscapeString($_REQUEST['title']);
            $textSummary = safeEscapeString($_REQUEST['summery']);
            $varKeywords = safeEscapeString($_REQUEST['keywords']);
            $textArticleText = safeEscapeString($_REQUEST['textarea']);
            $textResource = safeEscapeString($_REQUEST['resources']);

            $sql = "INSERT INTO tblarticles ( "
                . "intAuthorId , "
                . "intCategory , "
                . "varArticleTitle , "
                . "textSummary ,"
                . "varKeywords , "
                . "textArticleText ,"
                . "textResource ,"
                . "intStatus ,"
                . "intHit,"
                . "ttSubmitDate)"
                . "VALUES ( "
                . "'$intAuthorId', "
                . "'$intCategory', "
                . "'$varArticleTitle', "
                . "'$textSummary', "
                . "'$varKeywords', "
                . "'$textArticleText', "
                . "'$textResource', "
                . "'0', "
                . "'0', "
                . "NOW())";
            $result = $d->exec($sql);
        }
        // refresh the cache
        $path = "../cache/scache/";
        cache_cleanup($path);
        $path = "cache/acache/";
        cache_cleanup($path);

        header("location:index.php?filename=articles&pageno=" . $pageno);
        die();
    }

    /**
     * Delete method
     */
    if (isset($_REQUEST['a']) && trim($_REQUEST['a']) == 3) {
        if (isset($_REQUEST['artid']) && trim($_REQUEST['artid'] != "")) {

            $email = safeEscapeString($_REQUEST['email']);
            $title = safeEscapeString($_REQUEST['title']);
            $fname = safeEscapeString($_REQUEST['fname']);
            $articleid = sanitize_paranoid_string($_REQUEST['artid']);
            if ($pdo) {
                $query = "DELETE FROM tblarticles WHERE intId = ?";
                $bind = array($articleid);
                $result = delete_pdo($query, $bind);
            } else {
                $sql_del = "DELETE FROM tblarticles WHERE intId ='" . safeEscapeString($articleid) . "'";
                $del = $d->exec($sql_del);
            }

            // refresh the cache
            $path = "../cache/scache/";
            cache_cleanup($path);
            $path = "cache/acache/";
            cache_cleanup($path);
            header("location:index.php?filename=deleteemail&email=" . $email . "&title=" . $title . "&fname=" . $fname);
            die();
        }
    }
    /**
     * Update method
     */
    $AuthorId = "";
    $Category = "";
    $ArticleTitle = "";
    $Summary = "";
    $Keywords = "";
    $ArticleText = "";
    $Resource = "";
    $action = 1;
    if ((isset($_REQUEST['a']) && trim($_REQUEST['a']) == 2) && (!(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit"))) {
        if (isset($_REQUEST['artid']) && trim($_REQUEST['artid'] != "")) {
            $articleid = sanitize_paranoid_string($_REQUEST['artid']);

            if ($pdo) {
                $query = "SELECT * FROM tblarticles WHERE intId = ?";
                $bind = array($articleid);
                $result = select_pdo($query, $bind);
            } else {
                $sql = "SELECT * FROM tblarticles WHERE intId ='" . safeEscapeString($articleid) . "'";
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
                    $AuthorId = $row['intAuthorId'];
                    $Category = $row['intCategory'];
                    $ArticleTitle = n2br(stripString(convert($row["varArticleTitle"])));
                    $Summary = n2br(convert(stripString($row["textSummary"])));
                    $Keywords = n2br(convert(stripString($row["varKeywords"])));
                    $ArticleText = n2br(convert(stripString($row["textArticleText"])));
                    $Resource = n2br(convert(stripString($row["textResource"])));
                    $action = 2;
                }
            }

            // Update operation
            if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update") {
                $intAuthorId = sanitize_paranoid_string($_REQUEST['author']);
                $intCategory = sanitize_paranoid_string($_REQUEST['category']);
                $varArticleTitle = n2br(convert($_REQUEST['title']));
                $textSummary = n2br(convert($_REQUEST['summery']));
                $varKeywords = n2br(convert($_REQUEST['keywords']));
                $textArticleText = n2br(convert($_REQUEST['textarea']));
                $textResource = n2br(convert($_REQUEST['resources']));
                $toemail = $_REQUEST['email'];
                $fname = $_REQUEST['fname'];
                if ($pdo) {
                    $query = "UPDATE tblarticles SET intAuthorId = ?, intCategory = ?,
					varArticleTitle = ?, textResource = ?, textSummary = ?,
					varKeywords = ?, textArticleText = ?,
					ttSubmitDate = NOW() WHERE intId = ?";
                    $bind = array($intAuthorId, $intCategory, $varArticleTitle, $textResource, $textSummary, $varKeywords, $textArticleText, $articleid);
                    $result = update_pdo($query, $bind);
                } else {

                    $intAuthorId = safeEscapeString($_REQUEST['author']);
                    $intCategory = safeEscapeString($_REQUEST['category']);
                    $varArticleTitle = safeEscapeString($_REQUEST['title']);
                    $textSummary = safeEscapeString($_REQUEST['summery']);
                    $varKeywords = safeEscapeString($_REQUEST['keywords']);
                    $textArticleText = safeEscapeString($_REQUEST['textarea']);
                    $textResource = safeEscapeString($_REQUEST['resources']);

                    $sql_upd = "UPDATE tblarticles "
                        . "SET intAuthorId = '$intAuthorId', "
                        . "intCategory = '$intCategory', "
                        . "varArticleTitle = '$varArticleTitle', "
                        . "textResource = '$textResource', "
                        . "textSummary = '$textSummary', "
                        . "varKeywords = '$varKeywords', "
                        . "textArticleText = '$textArticleText', "
                        . "ttSubmitDate = NOW() "
                        . "WHERE intId ='$articleid'";

                    $result = $d->exec($sql_upd);
                }
                // refresh the cache
                $path = "../cache/scache/";
                cache_cleanup($path);
                $path = "cache/acache/";
                cache_cleanup($path);

                $action = 1;
                /* subject */
                include("approved_email.tpl");

                /* To send HTML mail, you can set the Content-type header. */
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                /* additional headers */
                $headers .= "From: " . $fromemail . "\r\n";


                /* and now mail it */
                mail($toemail, $subject, $message, $headers);



                header("location:index.php?filename=articles&pageno=" . $pageno);
                die();
            }
            //  End of updation
        }
    }
    /**
     * Change Status method
     */
    if (isset($_REQUEST['s']) && trim($_REQUEST['s']) == 0) {
        if (isset($_REQUEST['articleid']) && trim($_REQUEST['articleid'] != "")) {

            $id = sanitize_paranoid_string($_REQUEST['articleid']);
            $titles = $_REQUEST['title'];

            if ($pdo) {
                $query = "UPDATE tblarticles SET intStatus = 1 WHERE intId= ?";
                $bind = array($id);
                $result = update_pdo($query, $bind);
            } else {
                $id = safeEscapeString($_REQUEST['articleid']);
                $update = $d->exec("UPDATE tblarticles SET intStatus = 1 WHERE intId='$id'");
            }
            $toemail = $_REQUEST['email'];
            if ($pdo) {
                $query = "SELECT * FROM tblsettings";
                $bind = array();
                $author = select_pdo($query, $bind);
            } else {
                $sqls = "SELECT * FROM tblauthor WHERE varEmail ='$toemail'";
                $author = $obj_db->select($sqls);
            }
            //if($author !=""){
            foreach ($author as $row) {

                $fname = $row['varFirstName'];

                /* subject */
                $subject = "Your Article Submission";

                /* message */
                $message = stripString("
						<html>
						<head>
						<title>Article notification From " . $title . ".</title>
						</head>
						<body>
						<table>
						<tr>
						  <td>Dear " . $fname . ",</td>
						</tr>
						<tr>
						  <td>We are pleased to inform you that your article entitled  <a href='" . $site_URL . "articledetail.php?artid=" . $id . "'>" . $titles . "</a>  has been
              approved!<br>
							</td>
						</tr>
						<tr>
						  <td><p>Thank You</p><p>Regards,</p><p>" . $owner_name . "</p></td>
						</tr>
						<tr>
						 <td>" . $title . "</td>
						</tr>
						<tr>
                          <td>
                          #########################################################<br>
                          <b>Be sure to check out the same software that powers our Article Publishing site.<br>
                          It's called Article Friendly Free and you can see it in action right here:  <a href='http://www.articlefriendly.com'>Article Friendly Article Publishing Script</a><br><br>
                          Ton's of options and at a great price too (Free)!  Free lifetime support, upgrades & addons at <a href='http://www.articlefriendly.info/forum/'>AF User Support Forum</a><br><br>

                          #########################################################<br><br>
                          </p></td>
                        </tr>
						</table>
						</body>
						</html>
						");

                /* To send HTML mail, you can set the Content-type header. */
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                /* additional headers */

                $headers .= "From: " . $fromemail . "\r\n";
                //$headers .= "";
                //echo $message;
                //die();

                /* and now mail it */
                mail($toemail, $subject, $message, $headers);
                // refresh the cache
                $path = "../cache/scache/";
                cache_cleanup($path);
                $path = "cache/acache/";
                cache_cleanup($path);
                header("location:index.php?filename=articles");
                die();
            }
        }
    }
//}

    if (isset($_REQUEST['s']) && trim($_REQUEST['s']) == 1) {
        if (isset($_REQUEST['articleid']) && trim($_REQUEST['articleid'] != "")) {

            $id = sanitize_paranoid_string($_REQUEST['articleid']);

            if ($pdo) {
                $query = "UPDATE tblarticles SET intStatus = 0 WHERE intId= ?";
                $bind = array($id);
                $result = update_pdo($query, $bind);
            } else {
                $id = safeEscapeString($_REQUEST['articleid']);
                $update = $d->exec("UPDATE tblarticles SET intStatus = 0 WHERE intId='$id'");
            }
            // refresh the cache
            $path = "../cache/scache/";
            cache_cleanup($path);
            $path = "cache/acache/";
            cache_cleanup($path);
        }
    }

    if (isset($_REQUEST['script'])) {
        if (trim($_REQUEST['script']) == 'addarticle' || trim($_REQUEST['script']) == 'editarticle') {
            $email = safeEscapeString($_REQUEST['email']);
            $title = safeEscapeString($_REQUEST['title']);
            $fname = safeEscapeString($_REQUEST['fname']);
            $articleid = sanitize_paranoid_string($_REQUEST['artid']);
            ?>
            <br>
            <table  border="0" align="center" cellpadding="1" cellspacing="1" width="98%">
                <tr>
                    <td class="line_top"><div align="center">Unapproved Articles</div></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%"  border="0" cellspacing="1" cellpadding="1" class="greyborder">
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Author : </td>
                                <td><select name="author" id="author">
                                        <option>Select Author</option>
                                        <?php
                                        if ($pdo) {
                                            $query = "SELECT * FROM tblauthor ORDER BY varlastName";
                                            $author = select_pdo($query);
                                        } else {
                                            $sql = "SELECT * FROM tblauthor ORDER BY varlastName";
                                            $author = $d->fetch($sql);
                                        }
                                        foreach ($author as $row) {
                                            ?>
                                            <option value="<?= $row['intId']; ?>" <?php
                                            if ($row['intId'] == $AuthorId) {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            }
                                            ?>><?= stripslashes($row['varFirstName']) . " " . stripslashes($row['varlastName']); ?></option>
                                                <?php }
                                                ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Category :</td>
                                <td><select name="category" id="category">
                                        <option value="0">ROOT</option>
                                        <?php
                                        if ($action == 2) {
                                            $selected = $parent_id;
                                        } else {
                                            $selected = 0;
                                        }
                                        echo GetChild(0, 0, $selected, $obj_db, 1);
                                        if ($pdo) {
                                            $query = "select * from tblcategories";
                                            $author = select_pdo($query);
                                        } else {
                                            $sql = "select * from tblcategories";
                                            $author = $d->fetch($sql);
                                        }

                                        foreach ($author as $row) {
                                            ?>
                                            <option value="<?= $row['intID']; ?>" <?php
                                            if ($row['intID'] == $Category) {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            }
                                            ?>><?= $row['varCategory']; ?></option>
                                                <?php } ?>

                                    </select></td>
                            </tr>
                            <tr>
                                <td> Article Title : </td>
                                <td><input name="title" type="text" id="title" size="90" value="<?= stripslashes($ArticleTitle); ?>"></td>
                            </tr>
                            <tr>
                                <td valign="top"> Summary : </td>
                                <td><textarea name="summery" cols="100" rows="5" id="summery"><?= stripslashes($Summary); ?></textarea></td>
                            </tr>
                            <tr>
                                <td> Keywords : </td>
                                <td><input name="keywords" type="text" id="keywords" size="100" value="<?= stripslashes($Keywords); ?>"></td>
                            </tr>
                            <tr>
                                <td valign="top"> Article Text : </td>
                                <td><textarea name="textarea" cols="100" rows="35"><?= stripslashes($ArticleText); ?></textarea></td>
                            </tr>
                            <tr>
                                <td valign="top"> Resources : </td>
                                <td><textarea name="resources" cols="100" rows="10" id="resources"><?= stripslashes($Resource); ?>
                                    </textarea></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2"><div align="center">Updating will also approve the article -&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input name="Submit" type="submit" value="<?php
                                        if ($action == 2) {
                                            echo "Update";
                                        } else {
                                            echo "Submit";
                                        }
                                        ?>" onClick="return confirmsubmit();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red"><b>DELETE?</b></font>&nbsp;&nbsp;&nbsp;&nbsp;<a class="link" href="index.php?filename=<?php echo stripslashes($_REQUEST['filename']); ?>&a=3&artid=<?php echo $articleid; ?>&email=<?php echo $email ?>&title=<?php echo $title ?>&fname=<?php echo $fname ?>" onClick="return confirm('Are you sure you wish to delete this record ?');"> <img src="images/del.jpg" alt="Delete" border="0"> </a>
                                    </div></td>
                        </table></td>
                </tr>
            </table>


            <?php
        }
    } else {
        ?>

        <br>
        <table border="0" align="center" cellpadding="1" cellspacing="0" width="98%">
            <tr>
                <td height="20">
                    <table border="0" align="center" cellpadding="1" cellspacing="0" width="98%">
                        <tr align="center" class="line_top">
                            <td width="75%">Unapproved Articles</td>
                            <td width="100%" align="right"><a class="link" href="index.php?filename=articles&script=addarticle"><font color='#53A8BF'>Add Article</font></a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <td >
                <table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder" width="98%">
                    <tr class='table_header_menu'>
                        <td>Author</td>
                        <td>Category</td>
                        <td>Title</td>
                        <td>Detail</td>
                        <td>Status</td>
                        <td><div align="center">Edit</div></td>
                        <td><div align="center">Delete</div></td>
                    </tr>


                    <?php
                    /**
                     * ************************************
                     * PAGING CODE START
                     * ************************************
                     */
                    //$rowperpage=5;
                    $tablename = "tblarticles,tblauthor,tblcategories";
                    $per_page_keywords = "tblarticles.intAuthorId = tblauthor.intId AND tblarticles.intCategory = tblcategories.intID AND tblarticles.intStatus='0'";
                    $per_page_sorts = "";
                    include("system/paging.inc.php");
                    $rows_per_page = 25;


                    /**
                     * ************************************
                     * PAGING CODE ENDING
                     * ************************************
                     */
                    if ($pdo) {
                        $limit = ($pageno - 1) * $rows_per_page;
                        $query = "SELECT tblarticles.intStatus, tblarticles.intId, tblarticles.varArticleTitle, tblauthor.varFirstName, tblauthor.varlastName, tblcategories.varCategory
                    from tblarticles,tblauthor,tblcategories
                    WHERE tblarticles.intAuthorId = tblauthor.intId AND tblarticles.intCategory = tblcategories.intID AND tblarticles.intStatus=?
                    ORDER BY ttSubmitDate DESC
                    LIMIT ?, ?";
                        $bind = array(0, $limit, $rows_per_page);
                        $sql = select_pdo($query, $bind);
                    } else {
                        $query = "SELECT tblarticles.intStatus, tblarticles.intId, tblarticles.varArticleTitle, tblauthor.varFirstName, tblauthor.varlastName, tblcategories.varCategory
                    from " . $tablename . "
                    WHERE " . $per_page_keywords . " ORDER BY ttSubmitDate DESC
                    LIMIT " . ($pageno - 1) * $rows_per_page . "," . $rows_per_page;
                        $sql = $d->fetch($query);
                    }
                    if ($sql) {
                        $i = 0;
                        foreach ($sql as $row) {
                            $i = $i + 1;
                            ?>
                            <tr class="<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>" onMouseOver="this.className = 'Hrhover';"  onMouseOut="this.className = '<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>';">
                                <td><?php echo stripslashes($row['varFirstName']) . " " . stripslashes($row['varlastName']); ?></td>
                                <td><?php echo stripslashes($row['varCategory']); ?></td>
                                <td><?php echo stripslashes($row['varArticleTitle']); ?></td>
                                <td><a class="link" href="index.php?filename=article_detail&a=4&articleid=<?php echo $row['intId']; ?>">Detail</a></td>
                                <?php
                                $authemail = $row['varEmail'];
                                $art_title = str_replace("'", "", stripslashes($row['varArticleTitle']));
                                if ($row['intStatus'] == 0) {
                                    $intId = $row['intId'];
                                    echo "<td><a class='link' href='index.php?filename=articles&s=0&articleid=$intId&email=$authemail&title=$art_title'>Hidden</a></td>";
                                }
                                if ($row['intStatus'] == 1) {
                                    $intId = $row['intId'];
                                    echo "<td><a class='link' href='index.php?filename=articles&s=1&articleid=$intId'>Visible</a></td>";
                                }
                                ?>
                                <td align="center"><a class="link" href="index.php?filename=<?php echo urlencode($_REQUEST['filename']); ?>&script=editarticle&a=2&artid=<?php echo $row['intId']; ?>&email=<?php echo stripslashes($row['varEmail']) ?>&title=<?php echo stripslashes($row['varArticleTitle']) ?>&fname=<?php echo stripslashes($row['varFirstName']) ?>"> <img src="images/edit.png" alt="Edit" border="0"> </a></td>
                                <td align="center"><a class="link" href="index.php?filename=<?php echo urlencode($_REQUEST['filename']); ?>&a=3&artid=<?php echo $row['intId']; ?>&email=<?php echo $row['varEmail'] ?>&title=<?php echo stripslashes($row['varArticleTitle']) ?>&fname=<?php echo stripslashes($row['varFirstName']) ?>" onClick="return confirm('Are you sure you wish to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <tr >
                            <td colspan="3" >
                                <div align="center">
                                    <?php
                                    pagindet_atbotttom_page($div_page_no, $page_no, $req_querystr, $total_db_rec, $row_per_page);
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
