<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="sparkle.gg is a Minecraft name history tool where you can query current Minecraft account names, and get their name history and capes as a result.">
    <meta name="keywords" content="Minecraft, Minecraft OG accounts, Minecraft name history, Minecraft cape checker, sparkle.gg, sparkle, sparkle minecraft, sparkle minecraft tools, minecraft tools">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="sparkle.gg" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://sparkle.gg" />
    <meta property="og:image" content="http://sparkle.gg/sparkles.png" />
    <meta property="og:description" content="sparkle.gg is a Minecraft name history tool where you can query current Minecraft account names, and get their name history and capes as a result." />
    <meta name="theme-color" content="#6baa75">
    <link rel="stylesheet" href="svg/style.css">
    
    <link rel="icon" type="image/png" sizes="512x512" href="/sparkles.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <title>sparkle</title>
</head>

<body>

    <div class="container mt-4 navcont" style="margin-top: 0% !important;">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#"><i class="fal fa-sparkles"></i> sparkle.gg</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">home <span class="sr-only">(current)</span></a>
                    </li>
                    
                    
                </ul>
                <span class="navbar-text">
                    https://github.com/8liam
                </span>
            </div>
        </nav>
        <hr style="margin-top:0rem!important;">
    </div>

    <div class="container mt-4">

        <div class="box">
            <div class="row" style="width: 100%;">
                <div class="col-md-6 infobg bg">
                    <h1>SPARKLE.GG</h1>
                </div>

                <div class="col-md-6 info">

                    <p>
                    <h1>Minecraft Account History Tracking Tool</h1>
                    </p>

                </div>
            </div>
        </div>
        <hr>


        <div class="box">
            <div class="row" style="width: 100%;">
                <div class=" col-md-6 infobg bg">
                    <h1>Minecraft Account History</h1>
                    <div id="inputs">
                        <form method="POST">
                            <input name="username" type="text" id="input" placeholder="Minecraft Username"></input>
                            <button id="mcsearch" name="search" type="submit">Search</button>
                        </form>

                    </div>
                </div>


                <div class="col-md-6 info" id="history">
                    <?php

                    //$timezone_name = timezone_name_from_abbr("", $_COOKIE["offset"] * 60, false);
                    //date_default_timezone_set($timezone_name);
                    date_default_timezone_set('Australia/Queensland');
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                    error_reporting(E_ALL);
                    function curle($url)
                    {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


                        $data = curl_exec($ch);
                        curl_close($ch);
                        return $data;
                    }

                    if (isset($_POST["search"])) {
                        $username = $_POST["username"];
                        if (!empty($username)) {


                            $uuid_url = 'https://api.mojang.com/users/profiles/minecraft/' . $username;
                            // if status not 404 do below
                            $uuid_json = json_decode(curle($uuid_url), 1);

                            if (!empty($uuid_json)) {
                                $current = $uuid_json["name"];
                                echo "<h1>" . $current . "</h1>";
                                $uuid = $uuid_json["id"];

                                echo "<div class='row' style='width: 100%;'><div class='col-md-5'><p><b>UUID</b></p></div>";
                                echo "<div id='uuid' class='col-md-7'><p>" . $uuid . "</p></div>";

                                $mojang_url = 'https://api.mojang.com/user/profiles/' . $uuid . '/names';
                                $mojang_json = json_decode(curle($mojang_url), 1);

                                if (!isset($mojang_json["error"])) {
                                    $namechanges = count($mojang_json); //count namechanges
                                    $original = $mojang_json[0]["name"];
                                    echo "<div class='col-md-5'><p><b>Original Username</b></p></div>";
                                    echo "<div class='col-md-7'><p>" . $original . "</p></div></div>";
                                    if ($namechanges > 1) {
                                        $namechange = "<h1>Name Changes</h1><div class='row' style='width: 100%;'>";
                                        echo $namechange;


                                        foreach ($mojang_json as $mojang_data) {
                                            $name = "<div class='col-md-6'><b><p>" . $mojang_data["name"] . "</p></b></div>";

                                            if (isset($mojang_data["changedToAt"])) {
                                                $epoch = $mojang_data["changedToAt"] / 1000;
                                                $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
                                                $timestamp = "<div id='date' class='col-md-6'><p>" . $dt->format('d-m-Y h:i:s A ') . "</p></div>";
                                                echo $name; // name variable
                                                echo $timestamp; //timestamp variable
                                            }
                                        }
                                        $closerow = "</div>";
                                        echo $closerow;
                                    }
                                }
                                $cape_url = 'https://sessionserver.mojang.com/session/minecraft/profile/' . $uuid;
                                $cape_json = json_decode(curle($cape_url), 1);
                                $encrypted = $cape_json["properties"][0]["value"];
                                $decrypted = base64_decode($encrypted);



                                $capecheck = json_decode($decrypted, true);

                                $textures = $capecheck["textures"];

                                if (isset($textures["CAPE"])) {
                                    echo "<h1>Capes</h1>";
                                    echo "<div class='cape'>";
                                    $cape_img = $textures["CAPE"]["url"];
                                    //echo '"'.$cape_img.'";';
                                    include("capes/capevars.php");
                                    echo "</div>";
                                }
                                $optifinecape_url = 'https://api.capes.dev/load/' . $current . '/optifine';
                                $optifinecape_json = json_decode(curle($optifinecape_url), 1);


                                $optifinecheck = $optifinecape_json["exists"];

                                if ($optifinecheck == true) {
                                    echo "<h1>Optifine Cape</h1>";
                                    echo "<div class='cape'>";
                                    $optifinecape_img = $optifinecape_json["imageUrls"]["still"]["front"];
                                    echo "<img src='" . $optifinecape_img . "' id='cape' draggable='false' title='Optifine' style='image-rendering: pixelated;'></img>";
                                    echo "</div>";


                                    $optifinecape_url = $optifinecape_json["capeUrl"];
                                    //echo '<div id="mySkinContainer"><iframe src="https://minerender.org/embed/skin/?skin=inventivetalent&shadow=true" frameborder="0"></iframe></div>';

                                }
                            } else {
                                $error_header = "<h1>Invalid Username</h1>";
                                echo $error_header;
                            }
                        } else {
                            $error_header = "<h1>Search Field Empty</h1>";
                            echo $error_header;
                        }
                    }
                    ?>

                </div>
            </div>


        </div>
        <hr>




    </div>

    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://sparkle.gg/js/load.js"></script>
</body>

</html>
