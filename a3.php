<?php
echo ("start php script.");

    $servername = "classroom.cs.unc.edu";
    $username = "afatehi";
    $password = "";
    $database = "afatehidb";

    $conn = new mysqli($servername, $username, $password, $database);
    echo ("hello.");

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    echo ("hello conn.");
    //$dataFile = fopen("a3-test.txt", "r") or die("unable to open file");
    $dataFile = fopen("http://www.cs.unc.edu/~kmp/comp426fall17/a3-data.txt", "r") or die("unable to open file");

    if($dataFile) {
      echo ("open file.");
      while(($line = fgets($dataFile)) !== false) {
        //Create Variables from datafile
        $dataLine = explode(" ", $line);
        $First = $dataLine[0];
        $Last = $dataLine[1];
        $Name = $dataLine[2];
        $Name2 = $dataLine[3];
        $Date = $dataLine[4];
        $Type = $dataLine[5];
        if($dataLine[5] == 'passing') {
          $QBFirst = $dataLine[6];
          $QBLast = $dataLine[7];
        }
        if($Type = "fieldgoal"){
          $Points = 3;
        }else{
          $Points = 7;
        }

          //Make Teams Table
        $sql = "INSERT INTO Teams(Name) VALUES ('".$Name."')";
        //$sql = "INSERT INTO Teams(Name) VALUES ('$Name')";
        $conn->query($sql);

        $sql1 = "INSERT INTO Teams(Name) VALUES ('".$Name2."')";
        $conn->query($sql1);

        //Create Team ID
        $TeamIDQuery = "SELECT id From Teams WHERE Name = '".$Name."'";
        $result = $conn->query($TeamIDQuery);
        $row = $result -> fetch_row();
        $TeamID = $row[0];

        //oppTeam id
        $oppTeamIDQuery = "SELECT id From Teams WHERE Name = '".$Name."'";
        $result = $conn->query($oppTeamIDQuery);
        $row = $result -> fetch_row();
        $oppTeamID = $row[0];

        //Make Players Table
        $sql2 = "INSERT INTO Players(First, Last, Team) VALUES ('".$First."', '".$Last."', '".$TeamID."')";
        $conn->query($sql2);

        //Create Player ID
        $PlayerIDQuery = "SELECT id From Players WHERE First = '".$First."'";
        $result = $conn->query($PlayerIDQuery);
        $row = $result -> fetch_row();
        $PlayerID = $row[0];

        //for QB
        if($dataLine[5] == 'passing'){
        $sql3 = "INSERT INTO Players(First, Last, Team) VALUES ('".$QBFirst."', '".$QBLast."', '".$TeamID."')";
        $conn->query($sql3);
        }

        //Create QB Player ID
        $QBIDQuery = "SELECT id From Players WHERE First = '".$QBFirst."'";
        $result = $conn->query($QBIDQuery);
        $row = $result -> fetch_row();
        $QBID = $row[0];

        //Make Games Table
        $prevGameQuery = "SELECT id From Games WHERE (Team = '".$TeamID."' and Date = '".$Date."') or (OppTeam = '".$TeamID."' and Date = '".$Date."')";
        $result = $conn->query($prevGameQuery);
        if ($result->num_rows == 0) {
          // It's not there do the insertion
          $sql8 = "INSERT INTO Games(Team, OppTeam, Date) VALUES('".$TeamID."', '".$oppTeamID."', '".$Date."')";
          $conn->query($sql8);
        } else {
          $row = $result->fetch_row();
          $game_id = $row[0];
        }

        //Make Events Table
        $sql9 = "INSERT INTO Events(Player, Team, Date, Type, Points, QB) VALUES ('".$PlayerID."', '".$TeamID."', '".$Date."', '".$Type."', '".$Points."', '".$QBID."')";
        $conn->query($sql9);
        /*$sql9 = "INSERT INTO Events(Player) SELECT id FROM Players WHERE First = '".$First."'";
        $conn->query($sql9);
        $sql10 = "INSERT INTO Events(Team) SELECT id FROM Teams WHERE Name = '".$Name."'";
        $conn->query($sql10);
        $sql11 = "INSERT INTO Events(Date, Type, Points) VALUES ('".$Date."', '".$Type."', '".$Points."')";
        $conn->query($sql11);
        $sql12 = "INSERT INTO Events(QB) SELECT(id) FROM Players WHERE First = '"$First"'";
        $conn->query($sql12);*/
      }
    echo("close file.");
    fclose($dataFile);

    }else{
      echo("error opening file");
    }

    mysqli_close($conn);

?>
