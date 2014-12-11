<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>
            <?php echo $title; ?>      | Article preview | articlefriendly.com
        </title>
        <?php //require_once("inc/meta.inc.php"); ?>
        <link href="css/articles.css" rel="stylesheet" type="text/css">
        <script>
            function getTitle()
            {
                return window.opener.getTitle();
            }
            function getAuthor()
            {
                return window.opener.getAuthor();
            }
            function getResources()
            {
                return window.opener.getResources();
            }
            function getAuthorLink()
            {
                return '';
            }
            function getContent()
            {
                var content = new String(window.opener.getContent());
                content = content.replace(/\n/g, "<br>");
                content = content.replace(/\s+/g, " ");
                content = content.replace(/\<p[^\>]*\>/g, "");
                content = content.replace(/\<\/p\>/g, "");
                return content;
            }
        </script>
    </head>
    <body>
        <div align="center">
            <table class="maintable" cellspacing="0" cellpadding="4" width="750">
                <tr>
                    <td height="480" valign="top">
                        <div style="float: right">
                            <a href="javascript: window.close();">Close window</a>
                        </div>            <h2>Article preview</h2>
                        <p class="articletitle">
                            <script>
                                document.write(getTitle());
                            </script>              -
                            <span style="font-weight: 400;">
                                <font color="#000080" size="1">                  By:
                                    <script>
                                        document.write(getAuthor());
                                    </script>
                                </font>
                            </span>
                        </p>
                        <p class="articletext">
                            <script>
                                document.write(getContent());
                            </script>
                        </p>
                        <p>
                            <script>
                                document.write(getResources());
                            </script>
                        </p>                       </td>
                </tr>
            </table>
    </body>
</html>