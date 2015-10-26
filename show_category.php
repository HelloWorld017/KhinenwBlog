<!DOCTYPE html>

<?php
    include_once "config.php";
    use khinenw\blog\Config;

    if(!isset($_GET["tag"])) die("Tag not given.");

    $tag = $_GET["tag"];

    if($tag === "") die("Wrong Tag Given.");
    if(preg_match("[^/?*:;{}\\]+", $tag)) die("Wrong Tag Given.");

    $cacheUpdate = false;
    $cache = [];


    $file = Config::$docsFolder . $tag . ".cache";


    if(!is_file($file)){
        $cacheUpdate = true;
    }else{
        $cache = json_decode(file_get_contents($file), true);
        if(time() > $cache["time"] + Config::$cacheUpdateTerm) $cacheUpdate = true;
    }

    if($cacheUpdate){
        $cache = [];
        $cache["time"] = time();
        $cache["results"] = [];

        $entries = scandir(Config::$docsFolder, SCANDIR_SORT_DESCENDING);
        foreach($entries as $entry){
            if($entry == "." && $entry == "..") continue;

            $arr = explode(".", $entry);

            if($arr[count($arr) - 1] === "meta"){
                $meta = json_decode(file_get_contents(Config::$docsFolder . $entry), true);
                if(in_array($_GET["tag"], $meta["tag"])){
                    $cache["results"][] = $entry;
                }
            }

        }
        if(count($cache["results"]) <= 0) die("Invalid Tag");

        file_put_contents($file, json_encode($cache));
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
                    <h2>#<?php echo $tag; ?></h2>
                    <?php
                        include_once "show_doclist.php";
                        if(!isset($_GET["page"])) $_GET["page"] = 0;
                        doclist($cache["results"], $_GET["page"]);
                    ?>
                </div>
            </div>
        </section>
    </body>
</html>
