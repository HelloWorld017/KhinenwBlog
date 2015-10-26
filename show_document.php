<!DOCTYPE html>

<?php
    include_once "config.php";
    use khinenw\blog\Config;

    if(!isset($_GET["id"])) die("ID not given.");
    $docId = $_GET["id"];

    $metaPath = Config::$docsFolder . $docId . ".meta";
    $countPath = Config::$docsFolder . $docId . ".count";

    if(!is_file($metaPath)) die("Invalid ID");

    $meta = json_decode(file_get_contents($metaPath), true);

    if(!is_file($countPath)) $count = 0;
    else $count = file_get_contents($countPath);

    file_put_contents($countPath, ++$count);
?>


<html lang="ko">
    <head>
        <title>Khinenw no blog : <?php echo $meta["title"]; ?></title>

        <meta charset="utf-8"/>
        <meta name="description" content="Khinenw no blog.">
        <meta name="author" content="Khinenw">
        <meta name="viewport" content="width=device-width, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="../resources/blog.css">

        <script>
            function autoResize(element){
                element.height = element.contentWindow.document.body.scrollHeight;
            }
        </script>

        <script type="text/javascript" src="/jquery-2.1.3.js"></script>

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
        <header>
            <div class="header-horiz-wrapper">
                <div class="header-vert-wrapper">
                    <h1 class="title"><a href="index.php" style="color: #FFFFFF">Welcome to Khinenw's Blog!</a></h1>
                    <h3 class="subtitle">키네누의 블로그에 오신 것을 환영합니다!<br>キネヌのブログへようこそ！</h3>
                </div>
            </div>
        </header>

        <section>
            <div class="section-wrapper">
                <div class="section-inside-wrapper">
                    <?php
                        echo "<h2 class='document-title'>"
                            . $meta["title"]
                            . "<span class='right-time'>"
                            . $meta["time"]
                            . "</span></h2><br><br>";

                        require_once "../JBBCode/Parser.php";
                        require_once "../JBBCode/KhinenwCodeDefinitionSet.php";
                        require_once "../JBBCode/BBToolkit.php";

                        use JBBCode\Parser;
                        use JBBCode\KhinenwCodeDefinitionSet;
                        use JBBCode\BBToolkit;

                        $parser = new Parser();
                        $parser->addCodeDefinitionSet(new KhinenwCodeDefinitionSet());

                        $parser->parse(BBToolkit::bracketToTag(file_get_contents(Config::$docsFolder . $docId . ".txt")));

                        echo BBToolkit::tagToBracket(BBToolkit::useNewLine($parser->getAsHTML()));
                        echo "<br><br><span class='view'>View : " . $count . "</span>";

                        echo "<br><span class='tag'>#</span>";

                        foreach($meta["tag"] as $tag){
                            echo "<a class='tag-contents' href='show_category.php?tag=" . $tag . "'>" . $tag . "</a>&nbsp;&#124;&nbsp;";
                        }

                        echo "<hr>";
                    ?>

                    <br><br>

                    <!-- Disqus import -->
                    <div id="disqus_thread"></div>
                    <script>
                        var id = <?php echo $docId?>;
                        disqus_shortname = 'khinenw';
                        disqus_identifier = id;
                        disqus_title = '<?php echo $meta["title"]?>';
                        disqus_url = "https://khinenw.tk/blog/show_document/?id=" + id;
                        if ($('head script[src="//' + disqus_shortname + '.disqus.com/embed.js"]').length == 0) {
                            (function () {
                                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                (document.getElementsByTagName('head')[0]).appendChild(dsq);
                            })();
                        }
                        if (typeof DISQUS != "undefined") {
                            DISQUS.reset({
                                reload: true,
                                config: function () {
                                    this.page.identifier = id;
                                    this.page.url = "https://khinenw.tk/blog/show_document/?id=" + id;
                                }
                            });
                        }

                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
                </div>
            </div>
        </section>
    </body>
</html>
