<?php
include_once '../config/database.php';
include_once '../objects/video.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare video object
$videos = new Video($db);

$ID = $_POST["id"];
$USR = $_POST["email"];
$CRD = $_POST["credit"];
$SRT = $_POST["to_watch"];

?>

<html>
<!--

  __          _     _                            _             
 / _| ___  __| | __| |_   _ _ ____   _____ _ __ | |_ ___  _ __ 
| |_ / _ \/ _` |/ _` | | | | '_ \ \ / / _ \ '_ \| __/ _ \| '__|
|  _|  __/ (_| | (_| | |_| | | | \ V /  __/ | | | || (_) | |   
|_|  \___|\__,_|\__,_|\__, |_| |_|\_/ \___|_| |_|\__\___/|_|   
                      |___/                                    

-->

    <head>
        <meta name="viewport" content="width=device-width">
        <title>Matisse TV</title>
        <!-- <title>Babbiscia TV</title> -->
        <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js">
        </script>
        <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/clappr-chromecast-plugin@latest/dist/clappr-chromecast-plugin.min.js">
        </script>
        <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>

        <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    </head>

    <body bgcolor="#000000">
        <center>
            <table><tr><td>
            <img src="https://feddynventor.ddns.net:31713/images/logodef.png" width="400" height="150">
            <!-- <h1 style="color:white;"> TV a babbiscia </h1> -->
            </td><td>
            <table><tr><td>
                <font color="#FFFFFF" face="Open Sans"> <h2> <i>Benvenuto</i> <?php echo substr($USR, 0, strpos($USR, "@")); ?> </h2></font> </td><td>
                <form id="home" action="../../index.php" method="post"> <input type="submit" value="LOGOUT"> </form> </td></tr> <tr><td>
                <font color="#FFFFFF" face="Open Sans"> <h3> <i>Hai a disposizione</i> <?php echo $CRD ?> crediti </h3></font> </td><td align='right'>
                <form id="my_form" action="app.php" method="post">
                    <input type="hidden" name="email" value="<?php echo $USR; ?>">
                    <input type="hidden" name="id" value="<?php echo $ID; ?>"
                    <input type="hidden" name="credit" value="<?php echo $CRD; ?>">
                    <input type="hidden" name="to_watch" value="<?php echo -1; ?>">
                    <input type="submit" name="submission_button" value="HOME">
                </form>
            </td></tr> </table>
            </td></tr></table>
            <form id="my_form" action="buy.php" method="post">
                <input type="hidden" name="id" value="<?php echo $ID; ?>">
                <input type="hidden" name="credit" readonly="readonly" value="<?php echo $CRD; ?>">
                <input type="hidden" name="user" value="<?php echo $USR; ?>">

                    <?php
			$present = 0;
                        if ($SRT == -1) {
                            echo "<font color='#FFFFFF' face='Open Sans'>Seleziona evento: <select name='video'>";
                            $stmt = $videos->list();
                            while ($row = $stmt->fetch()) {
                                $present = 1;
				echo "<option value=".$row['id'].">". $row['nome']." = ".$row['cost'] ." c</option>";
                                // echo "<form><button type='submit' onclick='buy(".$row['id'].")'>".$row['nome']." =".$row['cost']."cc </button></form> \n";
                            }
                            echo "</select>";
			    if ($present == 1){
			    	echo "<input type='submit' name='submit' value='Acquista/Guarda' />";
			    } else {
				echo "<br><p>Non c'è nulla :( maledetto coglionavirus</p>";
			    }
                        }
                    ?>
            </form>
            <br>
            <?php
            if ($SRT != -1){
                $stmt = $videos->isLive($SRT);
                if($stmt->rowCount() > 0){
                    $row = $stmt->fetch();
                    if ($row['air'] == 1){
                        echo "<div id='player'></div>";
                    } elseif ($row['air'] == 0){
                        echo "<font color='#FFFFFF'><u>La diretta non è ancora iniziata!</u></font>";
                    }
                }
            }
            ?>
            <br><img src="https://feddynventor.ddns.net:31713/images/logo1footer.png" width="200" height="48">
        </center>
        
        <script>
            var player = new Clappr.Player({
            source: 'https://feddynventor.ddns.net:31713/hls/<?php echo $ID?>Z<?php echo $SRT?>.m3u8',
            autoPlay: true,
            plugins: [ChromecastPlugin],
            chromecast: {
                appId: '9DFB77C0',
                media: {
                    title: 'Matisse TV',
                    subtitle: 'powered by feddynventor'
                }
            },
//            watermark: "https://10.8.0.1:31713/images/logodefpic.png", position: 'top-left',
            mute: false,
            height: 563,
            width: 1000,
            parentId: "#player"
            });
        </script>

    </body>

</html>


