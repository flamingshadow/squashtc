<?php
	// 
	// CODED by STEPHEN GARRETT running from Tournament Control by the folks at dtkapiti.co.nz
	// v0.8
	// 
	// Load in the tournament data
	// $tournaments = json_decode(file_get_contents('testdata/tournaments.html'),true);
	// 
	$tournaments = json_decode(file_get_contents('https://dtkapiti.co.nz/apps/tournamentcontrol/scripts/android/gettournaments.php'),true);

	// 
	// Set a tournament ID if one has not been set
	// Defaults to the last updated tournament
	// 

	// Tournament ID in Tournament Array?
	function IDcheck($id,$tin)
	{
		$valid = false;
		foreach ($tin['Tournaments'] as $i)
		{
			if ($i['tournament_id']==$id)
			{
				$valid = true;
			}
		}
		return $valid;
	}

	// Comparison function
	function date_compare($element1, $element2) {
	    $datetime1 = strtotime($element1['last_update']);
	    $datetime2 = strtotime($element2['last_update']);
	    return $datetime1 - $datetime2;
	} 

	// Sets a cookie array
	function setlasttournment($i)
	{
		$cookie_name = "id";
		$cookie_value = $i;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
	}

	//
	// Validates the POST and Cookie input and checkes to see if it's in the tournament array
	//
	if (isset($_GET['id']))
	{
		if (IDcheck($_GET['id'],$tournaments))
		{
			$t_id = $_GET['id'];
		}
	}
	elseif (isset($_COOKIE["id"])) 
	{
		if (IDcheck($_COOKIE["id"],$tournaments))
		{
			$t_id = $_COOKIE["id"];
		}
	}

	//
	// Validates the POST for PLID
	//
	$postplayerid = FALSE;
	if (isset($_GET['plid']))
	{
		$postplayerid = $_GET['plid'];
	}


	// 
	// If an ID isn't set from POST or Cookie then default to last updated
	// 
	if (!isset($t_id))
	{
		usort($tournaments["Tournaments"], 'date_compare');
	 	$t_array_reversed["Tournaments"] = array_reverse($tournaments["Tournaments"]);
		$t_id = $t_array_reversed["Tournaments"][0]["tournament_id"];
	}
	
	setlasttournment ($t_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7563165518571134"
     crossorigin="anonymous"></script>
<!-- 
Disclaimer: 
I am in no way affiliated with https://tournamentcontrol.dtkapiti.co.nz/ this webpage is purely displaying available tournament information. It was designed as a way for iPhone users and others who can't use the app keep up to date. I claim no IP or ownership to the raw data in any form. 
 -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MMQZKFV99B"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MMQZKFV99B');
</script>





<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">


<meta name="title" content="Squash Tournament Control - Viewer">
<meta name="description" content="View tournaments running off Tournament Control.">
<meta name="keywords" content="Tournaments, squash, draw, player, results">
<meta name="robots" content="index, follow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="English">
<meta name="author" content="Stephen Garrett">



<!-- Fav Icons  -->
<link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">


<title>Tournament Control - Viewer</title>

<script type="text/javascript">
	// Hides and shows card containers
	function myHideShow(divname) {
		iconname = divname + 'icon';
		var x = document.getElementById(divname);
		var xi = document.getElementById(iconname);
		if (x.style.display === "none") {
			x.style.display = "block";
			xi.className = "bi bi-dash-square";
		} else {
			x.style.display = "none";
			xi.className = "bi bi-plus-square";
		}
	}
	// Hides and shows card containers
	function PlayerInfo(divname) {
		iconname = divname + 'icon';
		var x = document.getElementById(divname+"_1");
		var y = document.getElementById(divname+"_2");
		var xi = document.getElementById(iconname);
		if (x.style.display === "none") {
			x.style.display = "block";
			y.style.display = "block";
			xi.className = "bi bi-dash-square";
		} else {
			x.style.display = "none";
			y.style.display = "none";
			xi.className = "bi bi-plus-square";
		}
	}

	// Hides and shows card containers
	function DateInfo(divname) {
		iconname = divname + '_icon';
		var x = document.getElementById(divname);
		var xi = document.getElementById(iconname);
		if (x.style.display === "none") {
			x.style.display = "block";
			xi.className = "bi bi-dash-square";
		} else {
			x.style.display = "none";
			xi.className = "bi bi-plus-square";
		}
	}



</script>


<!-- Bootstrap -->
<link href="css/bootstrap-4.4.1.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap-icons.css?v=1.4.0">
<style>
	mark { 
	  background-color: #ffDD00;
	  color: black;
	}
</style>

</head>

<body>

<?php 

	//
	// Grading Adjustment
	//

	function tg($val, $min, $max) 
	{
		return ($val >= $min && $val <= $max);
	}

	function grading_men($x)
	{
		$i = intval($x);
		if (tg($i,0,79)) {$grade = "F / J4";}
		elseif (tg($i,80,99)) {$grade = "F / J3";}
		elseif (tg($i,100,124)) {$grade = "F / J2";}
		elseif (tg($i,125,149)) {$grade = "F / J1";}
		elseif (tg($i,150,209)) {$grade = "E2";}
		elseif (tg($i,210,299)) {$grade = "E1";}
		elseif (tg($i,300,429)) {$grade = "D2";}
		elseif (tg($i,430,624)) {$grade = "D1";}
		elseif (tg($i,625,999)) {$grade = "C2";}
		elseif (tg($i,1000,1599)) {$grade = "C1";}
		elseif (tg($i,1600,2799)) {$grade = "B2";}
		elseif (tg($i,2800,4999)) {$grade = "B1";}
		elseif (tg($i,5000,9999)) {$grade = "A2";}
		elseif (tg($i,10000,100000)) {$grade = "A1";}

		return $grade;
	}

	function grading_women($x)
	{
		$i = intval($x);
		if (tg($i,0,64)) {$grade = "F / J3";}
		elseif (tg($i,65,79)) {$grade = "F / J2";}
		elseif (tg($i,80,99)) {$grade = "F / J1";}

		elseif (tg($i,100,129)) {$grade = "E2";}
		elseif (tg($i,130,169)) {$grade = "E1";}
		elseif (tg($i,170,224)) {$grade = "D2";}
		elseif (tg($i,225,299)) {$grade = "D1";}
		elseif (tg($i,300,439)) {$grade = "C2";}
		elseif (tg($i,440,649)) {$grade = "C1";}
		elseif (tg($i,650,10999)) {$grade = "B2";}
		elseif (tg($i,1100,1899)) {$grade = "B1";}
		elseif (tg($i,1900,3799)) {$grade = "A2";}
		elseif (tg($i,3800,100000)) {$grade = "A1";}
		return $grade;
	}

	// 
	// Loads in the games data
 	// $current_tournament = json_decode(file_get_contents('testdata/getgames.html'),true);
	// 
	$rawjson = file_get_contents('https://dtkapiti.co.nz/apps/tournamentcontrol/scripts/android/getgames.php?id='.$t_id);
	$current_tournament = json_decode($rawjson,true);

	if ($current_tournament == null)
		{
			$json_parse1 = (preg_replace("/,{2,}/", "", $rawjson));
			$json_parse2 = (preg_replace("/}{/", "},{", $json_parse1));
			$current_tournament = json_decode($json_parse2,true);
		}

	

	// $current_tournament= json_decode(file_get_contents('https://dtkapiti.co.nz/apps/tournamentcontrol/scripts/android/getgames.php?id='.$t_id),true);

	// 
	// Sets the page info, and last update of tournament
	// 
	$pagetitle = "";
	$lastupdate = "";
	foreach ($tournaments['Tournaments'] as $i)
	{
		if ($i['tournament_id'] == $t_id)
		{
			$pagetitle = $i['name'];
			$lastupdate = $i['last_update'];

		}
	}


	// 
	// PLAYER ARRAY
	// Sets an array of players with their ID as key
	//

	foreach ($current_tournament['Players'] as $i)
	{
		$players[$i['player_id']]['name'] =$i['name'];
		$players[$i['player_id']]['seeding'] =$i['seeding'];
		$players[$i['player_id']]['draw_id'] =$i['draw_id'];
		$players[$i['player_id']]['player_id'] =$i['player_id'];
		$players[$i['player_id']]['grading_code'] =$i['grading_code'];
		if ($i['grading_code']== ""){
			$players[$i['player_id']]['grading_code'] = "NOT ASSIGNED";
		}
		$players[$i['player_id']]['points'] =$i['points'];
		if ($i['gender']=="F")
		{
			$players[$i['player_id']]['grade'] = grading_women($i['points']);
		}
		else
		{
			$players[$i['player_id']]['grade'] = grading_men($i['points']);
		}
		$players[$i['player_id']]['club'] =$i['club'];
	}
	

	// 
	// FUNCTIONS to avoid repetition
	// 
	// 
	// GAME PLAYED? Checks to see if the status is 0 or negative
	
	function played($a)
	{
		if (intval($a)<1)
		{
			return "#e8f9ea";
		}
		else
		{
			return "#f8f9fa";
		}
			
	}
	
	// 
	// WRITE LI ELEMENTS for Nav
	
	function writeli($name,$id,$u,$ct)
	{
		$insert = "";
		if ($id==$ct)
		{
			$insert =  "style='font-weight:bold'";
		}
		echo "<li class='nav-item'>";
		echo "<a class='nav-link' href='".$u."?id=".$id."' ".$insert.">[ ".$name." ]</a>";
		echo "</li>";
	}

	// CHECK IF NULL value in players
	
	function pexists($p_check,$p_in)
	{
		if (isset($p_check))
		{
			return $p_in[$p_check]['name'];
		}
		else
		{
			return "-";
		}
	}


	// Checks and returns player grading code
		function gradingcode_out($p_check,$p_in)
		{
			if (isset($p_check))
			{
				return $p_in[$p_check]['grading_code'];
			}
			else
			{
				return FALSE;
			}
		}

	// VALIDATES THE POST FOR PLAYER GRADE AND RESETS IT TO FALSE IF FALSE

	if(array_search($postplayerid, array_column($players, 'grading_code')) !== false) {
		// print $postplayerid;
	}
	else
	{
		$postplayerid = FALSE;	
	}


	// 
	// Returns extra player info
	// Player ID and Player array in
	// 

	function game_info($pid,$pin)
	{
	 	if (pexists($pid,$pin)=="-")
	 		{
	 			return;
	 		}
	 		else
	 		{
				echo "Seeding: ".$pin[$pid]['seeding']." - Grade: ".$pin[$pid]['grade']." - Points: ".$pin[$pid]['points'];
				echo " - Grading Code: ".$pin[$pid]['grading_code']." - Club: ".$pin[$pid]['club'];
	 		}
	}

	function p_info($pid,$pin)
	{
	 	if (pexists($pid,$pin)=="-")
	 		{
	 			return;
	 		}
	 		else
	 		{
				echo "(".$pin[$pid]['grade']." - ".$pin[$pid]['points'];
	 		}
	}


	//	
	// Produces the top of the collapsable box
	// 


	function topbox($div,$x)
	{
		echo "<!-- top of box of box --><div class='card shadow-sm'>";
		echo "<div class='card-header border-primary text-primary' onclick='myHideShow(\"".$x."\");'>";
		echo "<i class='bi bi-plus-square' id='".$x."icon' ></i>&nbsp;".$div."</div>";
		echo "<div class='card-body' id='".$x."' style='display:none'>";
		echo "<div class='row'><div class='col-xl-2'>";
	}

	function topbox_expanded($div,$x)
	{
		echo "<!-- top of box of box --><div class='card shadow-sm'>";
		echo "<div class='card-header border-primary text-primary' onclick='myHideShow(\"".$x."\");'>";
		echo "<i class='bi bi-dash-square' id='".$x."icon' ></i>&nbsp;".$div."</div>";
		echo "<div class='card-body' id='".$x."' style='display:block'>";
		echo "<div class='row'><div class='col-xl-2'>";
	}


	function topbox_dark($div,$x)
	{
		echo "<!-- top of box of box --><div class='card shadow-sm'>";
		echo "<div class='card-header border-primary text-white' style='background-color:#7c858d'onclick='myHideShow(\"".$x."\");'>";
		echo "<i class='bi bi-plus-square' id='".$x."icon' ></i>&nbsp;".$div."</div>";
		echo "<div class='card-body' id='".$x."' style='display:none'>";
		echo "<div class='row'><div class='col-xl-2'>";
	}

	// 
	// and the bottom
	// 

	function bottombox()
	{
		echo "</div></div></div></div><!-- bottom of box --><br>";

	}

	// 
	// Checks to see if a player in a draw game won and returns a tick
	// 
	
	function winner($a,$b)
	{
		if ($a=="1")
		{
			return "&nbsp&nbsp<i style='color:#28a745' class='bi bi-check-circle-fill'></i>&nbsp&nbsp<span style='font-size:small'>".$b."</span>";
		}	
	}
	function winner_icon($a,$b)
	{
		if ($a=="1")
		{
			return "&nbsp&nbsp<i style='color:#28a745' class='bi bi-check-circle-fill'></i>";
		}	
	}
	function winner_comment($a,$b)
	{
		if ($a=="1")
		{
			return "<span style='font-size:small'>".$b."</span>";
		}
		// return "";	
	}

?>


<!-- NAV -->
<nav class="navbar navbar-expand-lg navbar-light bg-light"><a class="navbar-brand" href="?id=<?php echo $t_id; ?>"><i class='bi bi-arrow-counterclockwise'style="font-size:x-large;"></i></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent1">
		<ul class="navbar-nav mr-auto">
		<?php 
			// Available Tournaments
			foreach ($tournaments['Tournaments'] as $i)
			{
				writeli($i['name'],$i['tournament_id'],"",$t_id);
			}
		?>
      </ul>
  </div>
</nav>

<br/>



<!-- Start of Cards -->
<div class="container">


<?php 
// 
// HEADER
// 
	echo "<div class='table-responsive'>";
	echo "<table width=100%><tr><td><h3 ><a href='?id=".$t_id."' style='color:#000;'>".$pagetitle."&nbsp;&nbsp;<i class='bi bi-arrow-counterclockwise' style='font-size:smaller;' ></i></a></h3></td>";
	echo "<td align=right><small>Last update: ".date('H:i - D d M ', strtotime($lastupdate))."</small></td></tr></table>";	
	echo "</div>";
// 
// EO HEADER
// 

	
// 
// courts
// 
topbox_dark("COURTS","courts");

$gamestatus = array ("-2"=>"Not Played","-1"=>"Played","0"=>"Just Finished","1"=>"On","2"=>"Next","3"=>"Soon","100"=>"Elsewhere","101"=>"Unknown","102"=>"Invalid","103"=>"Error");

function statuscheck($statin)
{
        $valid_values = array (-2,-1,0,1,2,3,99,100,101,102,103); 
        if (in_array($statin, $valid_values)){
                return $statin;
        }
        else
        {
                return 99;
        }

}
$courtinfo = array();
$courtids = array();
$courtstatus = array();
$courtgameinfo =array();
$soongameinfo=array();

foreach ($current_tournament["Courts"] as $c)
{
	$courtinfo[$c["court_id"]] = $c["name"];
	$courtids[] = $c["court_id"];
}


foreach ($current_tournament['Draws'] as $i)
{
	foreach($current_tournament['Games'] as $x){
		if ($x['draw_id']==$i["draw_id"])
		{
			$p1 = pexists($x['player1_id'],$players);
			$p2 = pexists($x['player2_id'],$players);
			$vs1= "<br>";
			$vs2= "<br>";
			if ($p1 == "-"){
				$p1 = "";
				$vs1= "";
			}
			if ($p2 == "-"){
				$p2 = "";
				$vs2="";
			}
			$ps1 = winner($x["p1_score"],$x["comment"]);
			$ps2 = winner($x["p2_score"],$x["comment"]);
			if (isset($x["court_id"]))
			{
				$courtstatus[$x["court_id"]][statuscheck($x["status"])] = [$x["game_id"]];
				$courtgameinfo[$x['game_id']] = $p1.winner($x["p1_score"],$x["comment"]).$vs1.$p2.winner($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:small'><strong>".$x['name']."</strong> - ".date('H:i ', strtotime($x["time"])).date('D d M ', strtotime($x["time"]))."</span>";
				
				// PUT IN FINISHED AS TIME IF ITS in JUST FINISHED
				if ($x["status"] == 0 && isset($x["finished"])){
					$courtgameinfo[$x['game_id']] = $p1.winner($x["p1_score"],$x["comment"]).$vs1.$p2.winner($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:small'><strong>".$x['name']."</strong> - Finished: ".date('H:i ', strtotime($x["finished"])).date('D d M ', strtotime($x["finished"]))."</span>";
				}
				
				// PUT IN STARTED AS TIME IF ITS in NOW
				if ($x["status"] == 1 && isset($x["started"])){
					$courtgameinfo[$x['game_id']] = $p1.winner($x["p1_score"],$x["comment"]).$vs1.$p2.winner($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:small'><strong>".$x['name']."</strong> - Started: ".date('H:i ', strtotime($x["started"])).date('D d M ', strtotime($x["started"]))."</span>";
				}
			}
			elseif (statuscheck($x["status"]) == 3)
			{
				$soongameinfo[$x['game_id']] = $p1.winner($x["p1_score"],$x["comment"]).$vs1.$p2.winner($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:small'><strong>".$x['name']."</strong> - ".date('H:i ', strtotime($x["time"])).date('D d M ', strtotime($x["time"]))."</span>";
			}
		 }	
	}
}


foreach ($courtids as $cid){
	echo "<div class='table-responsive'><table class='table' width=100%>";
	echo "<tr><th colspan=2 style='padding:10px;background-color:#28a745;color:white; border-bottom: 2px solid white;'>".$courtinfo[$cid]."</th></tr>";
	//  TOP OF COURT TABLE
	// 
	// Just Finished
		echo "<tr>";
	 	echo "<td align=center width=100px style='font-size:small;vertical-align:middle;background-color:#e8e9ea;border-bottom: 2px solid white;font-weight:bold;'>";
	 	echo "JUST FINISHED";	
 		echo "</td>";
	 	echo "<td style='vertical-align:middle'>";
		if (isset($courtstatus[$cid][0][0]))
		{
			print_r($courtgameinfo[$courtstatus[$cid][0][0]]);
		}
		echo "</td></tr>";
	// 
	// NOW
		echo "<tr>";
	 	echo "<td align=center width=100px style='font-size:small;vertical-align:middle;background-color:#bee4c7;border-bottom: 2px solid white;font-weight:bold;'>";
	 	echo "NOW";	
 		echo "</td>";
	 	echo "<td style='vertical-align:middle'>";
		if (isset($courtstatus[$cid][1][0]))
		{
			print_r($courtgameinfo[$courtstatus[$cid][1][0]]);
		}
		echo "</td></tr>";
	// 
	// NEXT
		echo "<tr>";
	 	echo "<td align=center width=100px style='font-size:small;vertical-align:middle;background-color:#ffecb4;border-bottom: 2px solid white;font-weight:bold;'>";
	 	echo "NEXT";	
 		echo "</td>";
	 	echo "<td style='vertical-align:middle'>";
		if (isset($courtstatus[$cid][2][0]))
		{
			print_r($courtgameinfo[$courtstatus[$cid][2][0]]);
		}
		echo "</td></tr>";
	// 
	// SOON
		echo "<tr>";
	 	echo "<td align=center width=100px style='font-size:small;vertical-align:middle;background-color:#fed8b8;font-weight:bold;'>";
	 	echo "SOON";	
 		echo "</td>";
	 	echo "<td style='vertical-align:middle'>";
		if (isset($courtstatus[$cid][3][0]))
		{
			print_r($courtgameinfo[$courtstatus[$cid][3][0]]);
		}
		echo "</td></tr>";
	// Bottom of Court Table
	echo "</table></div>";
}

if (count($soongameinfo)>0){
	echo "<div class='table-responsive'><table class='table' width=100%>";
	echo "<tr><th colspan=2 style='padding:10px;background-color:#28a745;color:white; border-bottom: 2px solid white;'>Unassigned</th></tr>";
	foreach ($soongameinfo as $sg)
	{
		// SOON
		echo "<tr>";
	 	echo "<td align=center width=100px style='font-size:small;vertical-align:middle;background-color:#fed8b8;font-weight:bold;border-bottom: 2px solid white;'>";
	 	echo "SOON";	
 		echo "</td>";
	 	echo "<td style='vertical-align:middle'>";
		print_r($sg);
		echo "</td></tr>";
	}
	echo "</table></div>";
}





bottombox();
// 
// End Of courts
// 









// 
// CALENDAR
// 


topbox_dark("SCHEDULE","cal");

$cal = array();
$ginfo =array();
foreach ($current_tournament['Draws'] as $i)
{
	foreach($current_tournament['Games'] as $x){
		if ($x['draw_id']==$i["draw_id"]){
			$p1 = pexists($x['player1_id'],$players);
			$p2 = pexists($x['player2_id'],$players);
			$cal[0][date('Y-m-d ', strtotime($x["time"]))][date('H:i ', strtotime($x["time"]))][] = $x['game_id'];
			$vs1= "<br>";
			$vs2= "<br>";
			if ($p1 == "-"){
				$p1 = "";
				$vs1= "";
			}
			if ($p2 == "-"){
				$p2 = "";
				$vs2="";
			}
			$p1a ="";
			$p2a = "";
			if (isset($x['player1_id'])){$p1a ="<i style='color:#7c858d' class='bi bi-person'></i> ";}
			if (isset($x['player2_id'])){$p2a ="<i style='color:#7c858d' class='bi bi-person'></i> ";}
			if ($x["p1_arrived"]==1)
			{
				 $p1a = "<i style='color:#28a745' class='bi bi-person-check-fill'></i> ";
			}
			if ($x["p2_arrived"]==1)
			{
				 $p2a = "<i style='color:#28a745' class='bi bi-person-check-fill'></i> ";
			}
			$ps1 = winner($x["p1_score"],$x["comment"]);
			$ps2 = winner($x["p2_score"],$x["comment"]);
			if (played($x['status']) == "#e8f9ea" ){
				$xcol = "#28a745";
			} else{
				$xcol = "#000000";
			}
			if (isset($x["comment"]) AND strlen($x["comment"])>0){
				// $wcomment =" - ".strlen(winner_comment($x["p1_score"],$x["comment"])).strlen(winner_comment($x["p2_score"],$x["comment"]));
				$wcomment =" - ".winner_comment($x["p1_score"],$x["comment"]).winner_comment($x["p2_score"],$x["comment"]);
			}
			else
			{
				$wcomment = "";
			}
			//Check to see if postplayerid is set and only write if one of the players is the id
			$gradeid1 = gradingcode_out($x['player1_id'],$players);
			$gradeid2 = gradingcode_out($x['player2_id'],$players);

			$ginfo[$x['game_id']] = "<span style='color:".$xcol."'>".$p1a.$p1."</span>".winner_icon($x["p1_score"],$x["comment"]).$vs1."<span style='color:".$xcol."'>".$p2a.$p2."</span>".winner_icon($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:color:#000000;'><strong style='font-size:small'>".$x['name']."</strong>".$wcomment."</span>";

			if ($gradeid1==$postplayerid)
			{
				$ginfo[$x['game_id']] = "<mark><span style='color:".$xcol."'>".$p1a.$p1."</span></mark>".winner_icon($x["p1_score"],$x["comment"]).$vs1."<span style='color:".$xcol."'>".$p2a.$p2."</span>".winner_icon($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:color:#000000;'><strong style='font-size:small'>".$x['name']."</strong>".$wcomment."</span>";
			}
			elseif ($gradeid2==$postplayerid)
			{
				$ginfo[$x['game_id']] = "<span style='color:".$xcol."'>".$p1a.$p1."</span>".winner_icon($x["p1_score"],$x["comment"]).$vs1."<mark><span style='color:".$xcol."'>".$p2a.$p2."</span></mark>".winner_icon($x["p2_score"],$x["comment"]).$vs2."<span style='font-size:color:#000000;'><strong style='font-size:small'>".$x['name']."</strong>".$wcomment."</span>";
			}
		}	
	}
}

$cal_days = (array_keys($cal[0]));
array_multisort($cal_days);
// print_r($cal_days);

date_default_timezone_set('Pacific/Auckland');
$tdy = date('Y-m-d', strtotime('now'));

foreach ($cal_days as $x)
{ 
	echo "<div class='table-responsive'><table class='table' width=100%>";
	$xnosp = str_replace(' ', '', $x);
	$tabledate = date('Y-m-d', strtotime($x));
	$dateicon ="bi bi-dash-square";
	$displaytable = "block";
	if ($tabledate < $tdy)
	{
		$dateicon ="bi bi-plus-square";
		$displaytable = "none";
	}


	echo " <thead><tr onclick='DateInfo(\"".$xnosp."\");'><th colspan=2 style='padding:10px;background-color:#28a745;color:white'>"." <i style='font-size:larger;color:#FFFFFF' class='".$dateicon."' id='".$xnosp."_icon' ></i> ".date('D d M ', strtotime($x))."</th></tr></thead>";



	$cal_times = (array_keys($cal[0][$x]));
	sort ($cal_times);
	echo "<tbody id='".$xnosp."' style='display:".$displaytable."''>";
	foreach ($cal_times as $y)
	{ 
		echo "<tr width='100%'>";
	 	echo "<td align=center width=100px style='font-size:small;vertical-align:middle;background-color:#eff9ef; font-weight:bold;'>";
	 	echo $y;	
 		echo "</td>";
	 	echo "<td style='vertical-align:middle' width=100%>";
		foreach ($cal[0][$x][$y] as $z)
		{
			// echo "<span style='color:".$gplayed[$z]."'>";
			echo $ginfo[$z]."<br>";
			// </span>
			$rule = "<hr style='border-top: 1px dotted #CCC; color:grey' >";
			if ($z == end($cal[0][$x][$y])){
				$rule ="";
			}
			echo $rule;
		}
		echo "</td></tr>";
	}
	echo "</tbody></table></div>";
}


bottombox();
	

// 
// End Of Calendar
// 


	// 
	// LIST OF PLAYERS AT THE TOP OF THE PAGE
	// 
	$titleinsert ="";
	$pid_drawid =FALSE;
	if($postplayerid !== FALSE && array_search($postplayerid, array_column($players, 'grading_code')) !== false) {
		$pidkey = array_search($postplayerid, array_column($players, 'grading_code'));
		$akeys = (array_keys($players));
		$pid_drawid=$players[$akeys[$pidkey]]["draw_id"];
		$titleinsert ="<span style='font-size:small; color:#FFF'> - ".$players[$akeys[$pidkey]]["name"]." <a href=?id=".$t_id." style='font-size:small; color:#FFF'>[clear selection]</a>";
	}



	// PLAYERS
	topbox_dark("PLAYERS".$titleinsert,"ID0");
	echo "<div class='table-responsive'><table class='table-striped' width=100% >";
		foreach ($current_tournament['Draws'] as $i){
			echo "<tr><th style='padding:10px;background-color:#ffc107'>".$i["description"]."</th></tr>";
			foreach ($players as $x){
				if (isset($x["draw_id"])&& $x["draw_id"]==$i["draw_id"])
				{
					$marked ='';
					if ($postplayerid == $x["grading_code"])
					{
						$marked = "style='background-color:#fcf8e3;'";
					}
					echo "<tr ".$marked.">";
					echo "<td style='padding:10px'>".$x["seeding"]." - ";
					echo "<a href='?id=".$t_id."&plid=".$x["grading_code"]."'>".$x["name"]."</a> <span style='font-size:x-small'>(".$x["grade"]." - ".$x["points"].")</span></td>";
					echo "</tr>";
				}
			}

		}
	echo "</table></div><br>";
	bottombox();


// 
// Creates a box for each DIV draw and puts in players and games
// 


foreach ($current_tournament['Draws'] as $i){
	
	$pid_drawid =FALSE;
	if($postplayerid !== FALSE && array_search($postplayerid, array_column($players, 'grading_code')) !== false) {
		$pidkey = array_search($postplayerid, array_column($players, 'grading_code'));
		$akeys = (array_keys($players));
		$pid_drawid=$players[$akeys[$pidkey]]["draw_id"];
	}

	if ($pid_drawid == $i["draw_id"] || $postplayerid == FALSE){
		if ($pid_drawid == $i["draw_id"]){
			topbox_expanded($i["description"],$i["draw_id"]);
			// ." <span style='font-size:small; color:#000000'>- ".$players[$akeys[$pidkey]]["name"]." <a href=?id=".$t_id." style='font-size:small; color:#F00'>[clear selection]</a> </span>"
		}
		else
		{
			topbox($i["description"],$i["draw_id"]);
		}			
		// 
		// PLAYERS
		// 

		echo "<div class='table-responsive'><table class='table-striped'width=100% >";
		echo "<tr><th style='padding:10px;background-color:#ffc107'>".$i["description"]."</th></tr>";

		foreach ($players as $x){
			if (isset($x["draw_id"])&& $x["draw_id"]==$i["draw_id"])
			{
				$marked ='';
				if ($postplayerid == $x["grading_code"])
				{
					$marked = "style='background-color:#fcf8e3;'";
				}
				echo "<tr ".$marked.">";
				echo "<td style='padding:10px'>".$x["seeding"]." - ";
				echo "<a href='?id=".$t_id."&plid=".$x["grading_code"]."'>".$x["name"]."</a> <span style='font-size:x-small'>(".$x["grade"]." - ".$x["points"].")</span></td>";
				echo "</tr>";
			}
		}
		echo "</table></div><br>";

		// 
		// GAMES
		// 

		echo "<div class='table-responsive'><table class='table' width=100%><tr>";
		echo "<tr><th colspan=3 style='padding:10px;background-color:#28a745;color:white'>".$i["name"]." - Draw</th></tr>";

		foreach($current_tournament['Games'] as $x){
			if ($x['draw_id']==$i["draw_id"]){
				$p1 = pexists($x['player1_id'],$players);
				$p2 = pexists($x['player2_id'],$players);
				$gradeid1 = gradingcode_out($x['player1_id'],$players);
				$gradeid2 = gradingcode_out($x['player2_id'],$players);
				$gm1 ="";
				$gme1 ="";
				$gm2 ="";
				$gme2 ="";
				if ($gradeid1==$postplayerid && $postplayerid!=FALSE)
				{
					$gm1 ="<mark>";
					$gme1 ="</mark>";
				}
				if ($gradeid2==$postplayerid && $postplayerid!=FALSE)
				{
					$gm2 ="<mark>";
					$gme2 ="</mark>";
				}
				echo "<tr>";
			 	echo "<td align=center width=100px style='font-size:small;background-color:".played($x['status']).";vertical-align:middle'>";
			 	echo $x["name"]."<br>";	
			 	echo date('D d M ', strtotime($x["time"]))."<br>";	
			 	echo date('H:i ', strtotime($x["time"]));	
		 		echo "</td>";
			 	echo "<td style='vertical-align:middle'>".$gm1;
			 	echo $p1.winner($x["p1_score"],$x["comment"]).$gme1."<br>";	
			 	// 
			 	// EXTRA INFO
			 	// 
			 	echo "<div id=".$x["name"]."_1 style='display:none;font-size:small;'>";
			 			game_info($x['player1_id'],$players);
			 	echo "<hr style='border-top: 1px dotted #CCC; color:grey'></div>";
				// 
				// End extra info 
			 	// 
			 	echo $gm2.$p2.winner($x["p2_score"],$x["comment"]).$gme2;	
			 	// 
			 	// EXTRA INFO
			 	// 
			 	echo "<div id=".$x["name"]."_2 style='display:none;font-size:small'>";
			 			game_info($x['player2_id'],$players);
			 	echo "</div>";
				// 
				// End extra info 
			 	// 
			 	echo "</td>";
				// EXPAND 
			 	echo "<td align=center width=50px style='vertical-align:middle;text-align:right'>";
					echo "<i style='font-size:larger;color:#28a745' class='bi bi-plus-square' id='".$x["name"]."icon' onclick='PlayerInfo(\"".$x["name"]."\");'></i>";
		 		echo "</td>";
			 	echo "</tr>";
			 }	
		}
		echo "</table></div>";
		bottombox();
	}
}



?>
</div>
<!-- Cookie pop over -->

<script type="text/javascript">
	
	function checkchecked (a){
		if (a === true){
			localStorage.cookie='0';
		}
	}

</script>



<!-- END OF CONTAINERs -->
    
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-3.4.1.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/popper.min.js"></script>
<script src="js/bootstrap-4.4.1.js"></script>
<script src="js/Chart-2.9.3.min.js"></script>

<!--FOOTER-->
<?php include("footer.php");?>
  </body>
</html>





