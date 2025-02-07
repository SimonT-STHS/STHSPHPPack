<?php include "Header.php";
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$UpdateCareerStatDBV1 = (boolean)false;
$CareerLeaderSubPrintOut = (int)1;
If (file_exists($DatabaseFile) == false){
	Goto CareerStatPlayersStatByYear;
}else{try{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Rookie = (boolean)FALSE; $PosC = (boolean)FALSE; $PosLW = (boolean)FALSE; $PosRW = (boolean)FALSE; $PosD = (boolean)FALSE;
	$Playoff = (string)"False";
	$MaximumResult = (integer)0;
	$MinimumGP = (integer)1;
	$TeamName = (string)"";
	$Year = (integer)0;	
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Rookie'])){$Rookie= TRUE;}
	if(isset($_GET['PosC'])){$PosC= TRUE;}
	if(isset($_GET['PosLW'])){$PosLW= TRUE;}
	if(isset($_GET['PosRW'])){$PosRW= TRUE;}
	if(isset($_GET['PosD'])){$PosD= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	$LeagueName = (string)"";

	include "SearchPossibleOrderField.php";
	
	foreach ($PlayersStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
	$Title = $Title . $DynamicTitleLang['CareerStatByYear'];
	If($Rookie == True){$Title = $Title . $PlayersLang['Rookie'] . " - ";}
	If($PosC == True){$Title = $Title . $PlayersLang['Center'] . " - ";}
	If($PosLW == True){$Title = $Title . $PlayersLang['LeftWing'] . " - ";}
	If($PosRW == True){$Title = $Title . $PlayersLang['RightWing'] . " - ";}
	If($PosD == True){$Title = $Title . $PlayersLang['Defenseman'] . " - ";}	
	If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
	If ($Year > 0){$Title = $Title . $Year . " - ";}
	If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
	
	$Query = "SELECT 0 As TeamThemeID, PlayerInfo.Number As Number, Player" . $TypeText . "StatCareer.*, ROUND((CAST(Player" . $TypeText . "StatCareer.G AS REAL) / (Player" . $TypeText . "StatCareer.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatCareer.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatCareer.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatCareer.FaceOffWon AS REAL) / (Player" . $TypeText . "StatCareer.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatCareer.P AS REAL) / (Player" . $TypeText . "StatCareer.SecondPlay) * 60 * 20),2) AS P20 FROM Player" . $TypeText . "StatCareer LEFT JOIN PlayerInfo ON Player" . $TypeText . "StatCareer.Name = PlayerInfo.Name WHERE Player" . $TypeText . "StatCareer.GP >= " . $MinimumGP . " AND Player" . $TypeText . "StatCareer.Playoff = \"" . $Playoff . "\"";
	
	If($Year > 0){$Query = $Query . " AND Player" . $TypeText . "StatCareer.YEAR = \"" . $Year . "\"";}
	If($TeamName != ""){$Query = $Query . " AND Player" . $TypeText . "StatCareer.TeamName = \"" . $TeamName . "\"";}
	If($Rookie == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.Rookie = \"True\"";}
	If($PosC == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosC = \"True\"";}
	If($PosLW == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosLW = \"True\"";}
	If($PosRW == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosRW = \"True\"";}
	If($PosD == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosD = \"True\"";}
	
	If ($OrderByField == "ShotsPCT" OR $OrderByField == "AMG" OR $OrderByField == "FaceoffPCT" OR $OrderByField == "P20"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Player" . $TypeText . "StatCareer." . $OrderByField;}
	$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;		
	If ($ACSQuery == TRUE){
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}
	$Query = $Query . " ,Player" . $TypeText . "StatCareer.GP ASC"; // Force Second Order to be GP
	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}

	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		$PlayerStat = $CareerStatdb->query($Query);
		
		include "SearchCareerSub.php";	
		if ($UpdateCareerStatDBV1 == TRUE){$CareerLeaderSubPrintOut = 2;}

	}	
		
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
} catch (Exception $e) {
CareerStatPlayersStatByYear:
	$LeagueName = $DatabaseNotFound;
	$PlayerStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>.STHSCareerStatPlayersStat_MainDiv{display:none}</style>";
}}
?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPAllPlayerStat_Table").tablesorter({
	showProcessing: true,
    widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
	  stickyHeaders_zIndex : 110,		
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSPlayerStat.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllPlayerStat_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div class="STHSCareerStatPlayersStat_MainDiv" style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>";?>
<div id="ReQueryDiv" style="display:none;">
<?php if($LeagueName != $DatabaseNotFound){include "SearchCareerStatPlayersStatByYear.php";}?>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
	<button class="tablesorter_Output download" type="button">Output</button>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
	<?php include "PlayersStatSub.php";?>
</tbody></table>
<br>
</div>

<?php include "Footer.php";?>
