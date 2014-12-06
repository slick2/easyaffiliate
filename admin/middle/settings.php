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
<script language="javascript" src="js/setting.js" type='text/javascript'></script>

<?php
// UPDATE records of setting
$SettingId = "";
$SiteURL = "";
$SiteName = "";
$ContactEmail = "";
$Agreement = "";
$TotalArticleinHome = "";
$RelatedArticles = "";
$ownerName = "";

if ($pdo) {
    $query = "SELECT * FROM tblsettings";
    $result = select_pdo($query);
} else {
    $sql = "select * from tblsettings";
    $result = $d->fetch($sql);
}
if ($result) {
    foreach ($result as $row) {
        $SettingId = stripString($row['intId']);
        $SiteURL = stripString($row['varSiteURL']);
        $SiteName = stripString($row['varSiteName']);
        $ContactEmail = stripString($row['varContactEmail']);
        $Agreement = stripString($row['textAgreement']);
        $TotalArticleinHome = stripString($row['intTotalArticleinHome']);
        $RelatedArticles = stripString($row['intRelatedArticles']);
        $ownerName = stripString($row['ownerName']);
        $maxWords = stripString($row['maxWords']);
        $minWords = stripString($row['minWords']);
    }
}
if (isset($_REQUEST['submit']) && trim($_REQUEST['submit']) == "Update") {
    $varSiteURL = safeEscapeString($_REQUEST['url']);
    $varSiteName = safeEscapeString($_REQUEST['name']);
    $varContactEmail = safeEscapeString($_REQUEST['mail']);
    $textAgreement = safeEscapeString($_REQUEST['agreement']);
    $intTotalArticleinHome = safeEscapeString($_REQUEST['total_article']);
    $intRelatedArticles = safeEscapeString($_REQUEST['related_article']);
    $varOwnerName = safeEscapeString($_REQUEST['ownersname']);
    $maxWords = safeEscapeString($_REQUEST['maxWords']);
    $minWords = safeEscapeString($_REQUEST['minWords']);
    if ($pdo) {
        $query = "UPDATE tblsettings SET varSiteURL = ?, varSiteName = ?, ownerName = ?,
 varContactEmail = ?, textAgreement = ?, intTotalArticleinHome = ?,
 intRelatedArticles = ?, minWords = ?, maxWords = ? where intId =?";
        $bind = array($varSiteURL, $varSiteName, $varOwnerName, $varContactEmail, $textAgreement, $intTotalArticleinHome, $intRelatedArticles,
            $minWords, $maxWords, $SettingId);
        $update = update_pdo($query, $bind);
    } else {



        $update = $d->fetch("UPDATE `tblsettings` SET `varSiteURL` = '$varSiteURL', `varSiteName` = '$varSiteName', `ownerName` = '$varOwnerName',
  `varContactEmail` = '$varContactEmail', `textAgreement` = '$textAgreement', `intTotalArticleinHome` = '$intTotalArticleinHome',
  `intRelatedArticles` = '$intRelatedArticles', minWords = '$minWords', maxWords = '$maxWords' where intId ='$SettingId'");
    }
    header("location:index.php?filename=settings");
    die();
}
?>

<form action="" method="post" enctype="multipart/form-data" name="adminform">
    <br><br>
    <table align="center" cellpadding="2" cellspacing="2">
        <tr>
            <td class="line_top">
                Edit settings </td>
        </tr>
        <tr >
            <td>
                <table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder">
                    <tr>
                        <td valign="top">URL :</td>
                        <td><input name="url" type="text" id="url" size="52" value="<?= $SiteURL; ?>"><br>
                            <span class="red_note">Site URL must be like , http://www.domainname.com/ , i.e.Slash at end of the url </span></td>
                    </tr>
                    <tr>
                        <td valign="top"> Name : </td>
                        <td><input name="name" type="text" id="name" size="52" value="<?php echo $SiteName; ?>"></td>
                    </tr>
                    <tr>
                        <td valign="top"> Email : </td>
                        <td><input name="mail" type="text" id="mail" size="52" value="<?= $ContactEmail; ?>"></td>
                    </tr>
                    <tr>
                        <td> Admin Name : </td>
                        <td><input name="ownersname" type="text" id="ownersname" size="20" value="<?php echo $ownerName; ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>This is only your Adsense Publishers ID and not the adsense formatting code!</b>--> <font color="red">Example: pub-879656700987</font><br>
                            Leave blank if you donot intend to use adsense...
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"> <p>Adsense Pub ID  :</p>		      </td>
                        <td><textarea name="agreement" cols="40" rows="1" id="agreement"><?= $Agreement; ?></textarea></td>
                    </tr>
                    <tr>
                        <td> Total Article in Home : </td>
                        <td><input name="total_article" type="text" id="total_article" size="20" value="<?= $TotalArticleinHome; ?>"></td>
                    </tr>
                    <tr>
                        <td> Related Articles : </td>
                        <td><input name="related_article" type="text" id="related_article" size="20" value="<?= $RelatedArticles; ?>"></td>
                    </tr>
                    <tr>
                        <td>Max Article Words :</td>
                        <td><input name="maxWords" type="text" id="maxWords" size="20" value="<?= $maxWords; ?>"></td>
                        </td>
                    </tr>
                    <tr>
                        <td>Minimum Article Words :</td>
                        <td><input name="minWords" type="text" id="minWords" size="20" value="<?= $minWords; ?>"></td>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input name="submit" type="submit" id="Update" value="Update" onClick="return confirmsubmit();"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
