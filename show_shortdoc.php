<!DOCTYPE html>

<?php
    include_once "config.php";
    use khinenw\blog\Config;

    if(!isset($_GET["id"])) die("ID not given.");
    $docId = $_GET["id"];

    $metaPath = Config::$docsFolder . $docId . ".meta";
    $countPath = Config::$docsFolder . $docId . ".count";

    if(!is_file($metaPath)) die("Invalid ID");
    if(!is_file($countPath)) $count = 0;
    else $count = file_get_contents($countPath);

    $meta = json_decode(file_get_contents($metaPath), true);
?>

<html lang="ko">
    <head>
        <title>Khinenw no blog</title>

        <meta charset="utf-8"/>
        <meta name="description" content="<?php echo $meta["title"]; ?>">
        <meta name="author" content="Khinenw">
        <meta name="viewport" content="width=device-width, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="../resources/blog.css">

        <!-- Syntax Highlight import -->
        <script type="text/javascript" src="/SyntaxHighlight/shCore.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushCpp.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushCSharp.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushCss.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushJava.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushJavaFX.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushJScript.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushPhp.js"></script>
        <script type="text/javascript" src="/SyntaxHighlight/shBrushXml.js"></script>

        <link href="/SyntaxHighlight/shCore.css" rel="stylesheet" type="text/css"/>
        <link href="/SyntaxHighlight/shThemeRDark.css" rel="stylesheet" type="text/css"/>

        <script type="text/javascript">
            SyntaxHighlighter.config.stripBrs = true;
            SyntaxHighlighter.all();
        </script>

    </head>

    <body>
        <section>
            <?php
                const PREVIEW_LENGTH = 50;
                echo "<h3 class='document-title'>"
                    . "<a target='_parent' href='show_document.php?id=" . $docId . "'>"
                    . $meta["title"]
                    . "</a>"
                    . "<span class='right-time'>"
                    . $meta["time"]
                    . "</span></h3><br><br>";

                require_once "../JBBCode/Parser.php";
                require_once "../JBBCode/KhinenwCodeDefinitionSet.php";
                require_once "../JBBCode/BBToolkit.php";

                use JBBCode\Parser;
                use JBBCode\KhinenwCodeDefinitionSet;
                use JBBCode\BBToolkit;

                $parser = new Parser();
                $parser->addCodeDefinitionSet(new KhinenwCodeDefinitionSet());

                $parser->parse(BBToolkit::bracketToTag(file_get_contents(Config::$docsFolder . $docId . ".txt")));

                $text = str_replace("<br>", "[br]", BBToolkit::tagToBracket(BBToolkit::useNewLine($parser->getAsHTML())));
                $text = str_replace("[br]", "<br>", preg_replace("/<.*>/", "", preg_replace("/<\\/.*>/", "", $text)));

                if(mb_strlen($text) < PREVIEW_LENGTH) echo $text;
                else echo mb_substr($text, 0, PREVIEW_LENGTH) . "...";

                echo "<br><br><a class='right-more' target='_parent' href='show_document.php?id=" . $docId . "'>Read More &gt;</a>";
                echo "<span class='view'>View : " . $count . "</span>";

                echo "<br><a class='tag' target='_parent' href='show_tags.php'>#</a>";

                foreach($meta["tag"] as $tag){
                    echo "<a class='tag-contents' target='_parent' href='show_category.php?tag=" . $tag . "'>" . $tag . "</a>&nbsp;&#124;&nbsp;";
                }

                echo "<hr>";
            ?>
        </section>
    </body>
</html>
