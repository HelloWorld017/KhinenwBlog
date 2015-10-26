<!DOCTYPE html>
<html>
    <head>
        <title>Khinenw no blog</title>

        <meta charset="utf-8"/>
        <meta name="description" content="Khinenw no blog.">
        <meta name="author" content="Khinenw">
        <meta name="viewport" content="width=device-width, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="/resources/blog.css">
        <link rel="shortcut icon" href="/resources/favicon.ico"/>

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
                    <?php
                        include_once "show_doclist.php";
                        include_once "config.php";
                        use khinenw\blog\Config;

                        if(!isset($_GET["page"])) $_GET["page"] = 0;

                        $dirs = scandir(Config::$docsFolder, SCANDIR_SORT_DESCENDING);

                        $entries = [];

                        doclist(
                            array_filter($dirs, function($var){
                                if($var === "." || $var === "..") return false;

                                $arr = explode(".", $var);
                                return ($arr[count($arr) - 1] === "meta");
                            }),

                            $_GET["page"]
                        );
                    ?>
                </div>
            </div>
        </section>
    </body>
</html>