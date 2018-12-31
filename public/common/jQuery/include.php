        <?php
         if ($ua->DesignJudege() === PC_design) {
                echo "<script src='$url/API/jQuery/jquery-3.1.1.js'></script>";
                echo "<script src='$url/API/jQuery/jQuery-UI/jquery-ui.js'></script>";
                echo "<link type='text/css' rel='stylesheet' href='$url/API/jQuery/jQuery-UI/jquery-ui.css' />";
            } else if ($ua->DesignJudege() === SP_design) {
                echo "<script src='$url/API/jQuery/jquery-3.1.1.js'></script>";
                echo "<script src='$url/API/jQuery/jQuery-UI/jquery-ui.js'></script>";
                echo "<link type='text/css' rel='stylesheet' href='$url/API/jQuery/jQuery-UI/jquery-ui.css' />";
            }
