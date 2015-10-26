<!DOCTYPE html>

<?php
    include_once "config.php";
    use khinenw\blog\Config;

    $entries = scandir(Config::$docsFolder);

    $cacheList = [];

    foreach($entries as $entry){
        if($entry == "." && $entry == "..") continue;
        
        $arr = explode(".", $entry);

        if($arr[count($arr) - 1] === "cache"){
            $cacheList[$arr[0]] = json_decode(file_get_contents('./docs/' . $entry), true);
        }

    }

?>

<html lang="ko">
    <head>
        <title>Khinenw no blog : <?php echo $_GET["tag"]; ?></title>

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
                    <ul class="tag-list">
                        <?php
                            foreach($cacheList as $tagName => $cache){
                                $docCount = count($cache["results"]);
                                if($docCount === 0) continue;

                                echo "<li>" .
                                    "<h3><a class='tags' href='show_category.php?tag=" . $tagName . "'>#" . $tagName . "</a>" .
                                    "<span class='right-count'>" . $docCount . " Documents</span></h3>" .
                                    "</li>";
                            }

                        ?>
                    </ul>
                </div>
            </div>
        </section>
    </body>
</html>
