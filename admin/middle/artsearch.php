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
} else {
    $pageno = 1;
}
?>
<script language="javascript" src="js/article.js"></script>
<form action="" method="post" enctype="multipart/form-data" name="adminform">
    <?php

// Function for displaying categories in drop down menu
    function GetChild($ParentID, $num, $selected, $d, $check) {
        $value = "";
        $c = "";
        $d = new db(0);

        for ($i = 0; $i < $num; $i++) {
            $c = $c . "&nbsp;&nbsp;-&nbsp;&nbsp;";
        }

        if ($pdo) {
            $query = "SELECT * FROM tblcategories WHERE intParentID = ? ORDER BY varCategory ASC";
            $bind = array($ParentID);
            $RsC = select_pdo($query, $bind);
        } else {

            $query_RsC = "SELECT * FROM tblcategories WHERE intParentID = " . $ParentID . "  ORDER BY varCategory ASC";
            $RsC = $d->fetch($query_RsC);
        }
        $cnt = count($RsC);

        if ($RsC) {
            for ($i = 0; $i < $cnt; $i++) {
                if ($check == 1) {
                    if ($selected == $RsC[$i]['intID']) {
                        $value = $value . "<option value='" . $RsC[$i]['intID'] . "' selected>" . $c . stripString($RsC[$i]['varCategory']) . "</option>";
                    } else {
                        $value = $value . "<option value='" . $RsC[$i]['intID'] . "' >" . $c . stripString($RsC[$i]['varCategory']) . "</option>";
                    }
                }
                if ($check == 2) {
                    $value = $value . "," . $RsC[$i]['intID'];
                }
                $value = $value . "" . GetChild($RsC[$i]['intID'], $num + 1, $selected, $d, $check);
            }
        }
        return stripString($value);
    }

// INSERT operation of article
    if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {
        $intAuthorId = sanitize_paranoid_string($_REQUEST['author']);
        $intCategory = sanitize_paranoid_string($_REQUEST['category']);
        $varArticleTitle = convert($_REQUEST['title']);
        $textSummary = convert($_REQUEST['summery']);
        $varKeywords = convert($_REQUEST['keywords']);
        $textArticleText = convert($_REQUEST['textarea']);
        $textResource = convert($_REQUEST['resources']);



        if ($pdo) {
            $query = "INSERT INTO tblarticles ( intAuthorId , intCategory , varArticleTitle , textSummary ,
					varKeywords , textArticleText , textResource , intStatus , intHit, ttSubmitDate)
					VALUES ( ?, ?, ?, ?,
					?, ?, ?, ?, ?, NOW())";
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

            $sql = "INSERT INTO tblarticles ( intAuthorId , intCategory ,varArticleTitle , textSummary ,
					varKeywords , textArticleText , textResource , intStatus , intHit, ttSubmitDate)
					VALUES ( '$intAuthorId', '$intCategory', '$varArticleTitle', '$textSummary',
					'$varKeywords', '$textArticleText', '$textResource', '0', '0', NOW())";
            $result = $d->exec($sql);
        }

        header("location:index.php?filename=artsearch&pageno=" . $pageno);
        die();
    }
// End of INSERT operation
// DELETE operation of article
    if (isset($_REQUEST['a']) && trim($_REQUEST['a']) == 3) {
        if (isset($_REQUEST['artid']) && trim($_REQUEST['artid'] != "")) {
            $articleid = sanitize_paranoid_string($_REQUEST['artid']);

            if ($pdo) {
                $query = "Delete from tblarticles where intId = ?";
                $bind = array($articleid);
                $result = delete_pdo($query, $bind);
            } else {
                $sql_del = "Delete from tblarticles where intId ='" . safeEscapeString($articleid) . "'";
                $del = $d->exec($sql_del);
            }
            header("location:index.php?filename=artsearch&pageno=" . $pageno);
            die();
        }
    }
// End of DELETE operation
// UPDATE operation of article
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
                $query = "select * from tblarticles where intId = ?";
                $bind = array($articleid);
                $result = select_pdo($query, $bind);
            } else {
                $sql = "select * from tblarticles where intId ='" . safeEscapeString($articleid) . "'";
                $result = $d->fetch($sql);
            }

            // IF there is not records in database
            if (count($result) <= 0) {
                echo " No Record Found!<br>";
                die();
            }

            // If there is records in database it will be store in a variable
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

                    $sql_upd = "UPDATE tblarticles SET intAuthorId = '$intAuthorId', intCategory = '$intCategory',
						varArticleTitle = '$varArticleTitle', textResource = '$textResource', textSummary = '$textSummary',
						varKeywords = '$varKeywords', textArticleText = '$textArticleText',
						ttSubmitDate = NOW() WHERE intId ='$articleid'";

                    $result = $d->exec($sql_upd);
                }
                $action = 1;
                $_SESSION['msg'] = "Article Updated!";
                header("location:index.php?filename=articlesearch");
                die();
            }
            //  End of updation
        }
    }
// End Of UODATE operation
// change status visible or hidden
    if (isset($_REQUEST['s']) && trim($_REQUEST['s']) == 0) {
        if (isset($_REQUEST['articleid']) && trim($_REQUEST['articleid'] != "")) {
            $id = sanitize_paranoid_string($_REQUEST['articleid']);
            if ($pdo) {
                $query = "update tblarticles set intStatus = ? where intId= ?";
                $bind = array(1, $id);
                $result = update_pdo($query, $bind);
            } else {
                $id = safeEscapeString($_REQUEST['articleid']);
                $update = $d->exec("update tblarticles set intStatus = 1 where intId='$id'");
            }
        }
    }
    if (isset($_REQUEST['s']) && trim($_REQUEST['s']) == 1) {
        if (isset($_REQUEST['articleid']) && trim($_REQUEST['articleid'] != "")) {
            $id = sanitize_paranoid_string($_REQUEST['articleid']);

            if ($pdo) {
                $query = "update tblarticles set intStatus = 0 where intId= ?";
                $bind = array($id);
                $result = update_pdo($query, $bind);
            } else {
                $id = safeEscapeString($_REQUEST['articleid']);
                $update = $d->exec("update tblarticles set intStatus = 0 where intId='$id'");
            }
        }
    }

    if (isset($_REQUEST['script'])) {
        if (trim($_REQUEST['script']) == 'addarticle' || trim($_REQUEST['script']) == 'editarticle') {
            ?>
            <br />
            <table  border="0" align="center" cellpadding="1" cellspacing="1" width="725">
                <tr>
                    <td><table width="100%"  border="0" cellspacing="1" cellpadding="1" class="greyborder" width="725">
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Author : </td>
                                <td>
                                    <select name="author" id="author">
                                        <option>Select Author</option>
                                        <?php
                                        if ($pdo) {
                                            $query = "select * from tblauthor ORDER BY varlastName";
                                            $author = select_pdo($query);
                                        } else {
                                            $sql = "select * from tblauthor ORDER BY varlastName";
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
                                            ?>><?= stripString($row['varFirstName']) . " " . stripString($row['varlastName']); ?></option>
                                                <?php }
                                                ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Category :</td>
                                <td>
                                    <select name="category" id="category">
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
                                            <option value="<?php echo $row['intID']; ?>" <?php
                                            if ($row['intID'] == $Category) {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            }
                                            ?>><?php echo $row['varCategory']; ?></option>
                                                <?php } ?>

                                    </select></td>
                            </tr>
                            <tr>
                                <td> Article Title : </td>
                                <td><input name="title" type="text" id="title" size="60" value="<?= $ArticleTitle; ?>"></td>
                            </tr>
                            <tr>
                                <td valign="top"> Summary : </td>
                                <td><textarea name="summery" cols="45" rows="4" id="summery"><?= $Summary; ?></textarea></td>
                            </tr>
                            <tr>
                                <td> Keywords : </td>
                                <td><input name="keywords" type="text" id="keywords" size="60" value="<?= $Keywords; ?>"></td>
                            </tr>
                            <tr>
                                <td valign="top"> Article Text : </td>
                                <td><textarea name="textarea" cols="45" rows="6"><?= $ArticleText; ?></textarea></td>
                            </tr>
                            <tr>
                                <td valign="top"> Resources : </td>
                                <td><textarea name="resources" cols="45" rows="6" id="resources"><?= $Resource; ?>
                                    </textarea></td>
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
                            <td width="75%">Search Results</td>
                            <td width="100%" align="right"><a class="link" href="index.php?filename=artsearch&script=addarticle"><font color='#53A8BF'>Add Article</font></a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <td >
                <table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder" width="98%">
                    <tr class='table_header_menu'>
                        <td><b>Author</b></td>
                        <td><b>Category</b></td>
                        <td><b>Title</b></td>
                        <td><b>Detail</b></td>
                        <td><b>Status</b></td>
                        <td><div align="center"><b>Edit</b></div></td>
                        <td><div align="center"><b>Delete</b></div></td>
                    </tr>


                    <?php
                    /*                     * ************************************
                      PAGING CODE START
                     * ************************************ */

                    if ($_POST) {
                        $pieces = safeEscapeString($_POST['author']);
                    } else {

                        $pieces = safeEscapeString($_REQUEST['search']);
                    }

                    /*                     * ************************************
                      PAGING CODE ENDING
                     * ************************************ */

//$rowperpage = 20;
                    $tablename = "tblarticles,tblauthor,tblcategories";
                    $per_page_keywords = "tblarticles.varArticleTitle LIKE '%$pieces%' AND tblarticles.intAuthorId = tblauthor.intId AND tblarticles.intCategory = tblcategories.intID";

                    if (isset($_REQUEST['pageno'])) {
                        $pageno = sanitize_paranoid_string($_REQUEST['pageno']);
                    } else {
                        $pageno = 1;
                    }
                    if ($pdo) {
                        $query = "SELECT count(*) FROM tblarticles WHERE intStatus = ?";
                        $bind = array(1);
                        $query_data = select_pdo($query, $bind);
                    } else {
                        $query = "SELECT count(*) FROM tblarticles WHERE intStatus = 1";
                        $query_data = $d->fetch($query);
                    }
                    $numrows = $query_data[0]["count(*)"];




                    $rows_per_page = 20;
                    $lastpage = ceil($numrows / $rows_per_page);

                    $pageno = (int) $pageno;
                    if ($pageno < 1) {
                        $pageno = 1;
                    } elseif ($pageno > $lastpage) {
                        $pageno = $lastpage;
                    }


                    if ($pdo) {
                        $limit = ($pageno - 1) * $rows_per_page;
                        $query = "Select tblarticles.intStatus, tblarticles.intId, tblarticles.varArticleTitle, tblauthor.varFirstName, tblauthor.varlastName, tblcategories.varCategory
					from tblarticles,tblauthor,tblcategories
					WHERE tblarticles.varArticleTitle LIKE ? AND tblarticles.intAuthorId = tblauthor.intId AND tblarticles.intCategory = tblcategories.intID
          ORDER BY ttSubmitDate DESC
					LIMIT ?, ?";
                        $bind = array("%" . $pieces . "%", $limit, $rows_per_page);
                        $sql = select_pdo($query, $bind);
                    } else {
                        $query = "Select tblarticles.intStatus, tblarticles.intId, tblarticles.varArticleTitle, tblauthor.varFirstName, tblauthor.varlastName, tblcategories.varCategory
					from " . $tablename . "
					WHERE " . $per_page_keywords . " ORDER BY ttSubmitDate DESC
					LIMIT " . ($pageno - 1) * $rows_per_page . "," . $rows_per_page;
                        $sql = $d->fetch($query);
                    }

                    /*                     * ************************************
                      PAGING CODE ENDING
                     * ************************************ */


                    if ($sql) {
                        $i = 0;
                        foreach ($sql as $row) {
                            $i = $i + 1;
                            ?>
                            <tr class="<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>" onMouseOver="this.className = 'Hrhover';"  onMouseOut="this.className = '<?php echo ($i % 2 == 0) ? "Hrnormal" : "Hralter"; ?>';">
                                <td><?php echo $row['varFirstName'] . " " . $row['varlastName']; ?></td>
                                <td><?php echo stripString($row['varCategory']); ?></td>
                                <td><?php echo stripString($row['varArticleTitle']); ?></td>
                                <td><a class="link" href="index.php?filename=article_detail&a=4&articleid=<?php echo $row['intId']; ?>">Detail</a></td>
                                <?php
                                if ($row['intStatus'] == 0) {
                                    $intId = $row['intId'];
                                    echo "<td><a class='link' href='index.php?filename=artsearch&pageno=" . $pageno . "&s=0&articleid=$intId&search=$pieces'>Hidden</a></td>";
                                }
                                if ($row['intStatus'] == 1) {
                                    $intId = $row['intId'];
                                    echo "<td><a class='link' href='index.php?filename=artsearch&pageno=" . $pageno . "&s=1&articleid=$intId&search=$pieces'>Visible</a></td>";
                                }
                                ?>
                                <td align="center"><a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&script=editarticle&a=2&artid=<?php echo $row['intId']; ?>;"> <img src="images/edit.png" alt="Edit" border="0"> </a></td>
                                <td align="center"><a class="link" href="index.php?filename=<?php echo $_REQUEST['filename']; ?>&a=3&artid=<?php echo $row['intId']; ?>&pageno=<?php echo $pageno ?>" onClick="return confirm('Are you sure you wish to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <tr >
                            <td colspan="3" ><div align="center">
                                    <?php
                                    // query line==== Limit ".($page_no*$row_per_page).",".$row_per_page;
                                    // PAGING FUNCTION FOR PAGE NUMBER DISPLAYED
                                    //pagindet_atbotttom_page($div_page_no,$page_no,$req_querystr,$total_db_rec,$row_per_page);
                                    if ($pageno == 1) {
                                        echo " FIRST PREV ";
                                    } else {
                                        echo " <a href='index.php?filename=artsearch&pageno=1&search=$pieces'>FIRST</a> ";
                                        $prevpage = $pageno - 1;
                                        echo " <a href='index.php?filename=artsearch&pageno=$prevpage&search=$pieces'=>PREV</a> ";
                                    }

                                    echo " ( Page $pageno of $lastpage ) ";

                                    if ($pageno == $lastpage) {
                                        echo " NEXT LAST ";
                                    } else {
                                        $nextpage = $pageno + 1;
                                        echo " <a href='index.php?filename=artsearch&pageno=$nextpage&search=$pieces'>NEXT</a> ";
                                        echo " <a href='index.php?filename=artsearch&pageno=$lastpage&search=$pieces'>LAST</a> ";
                                    }
                                    //extract($_GET);
                                    //echo $pageno;
                                    ?>
                                    <tr >
                                        <td colspan="3" ><div align="center">

                                            </div></td>
                                    </tr>


                    </table></td>
                </tr>
            </table>
        </form>
        <?php
    }
}

