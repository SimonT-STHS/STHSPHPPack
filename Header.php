<?php
/* This Webpage should never modify. If you want to modify the webpage, please modify the Menu.php webpage*/
$DatabaseFile = (string)"STHSDemo-STHS.db";
$CareerStatDatabaseFile = (string)"STHSDemo-STHSCareerStat.db";
$NewsDatabaseFile = (string)"STHSDemo-STHSNews.db";
$LangOverwrite = (boolean)FALSE;
$lang = (string)"en"; /* The $lang option must be either "en" or "fr" */
if(isset($_GET['Lang'])){$lang  = filter_var($_GET['Lang'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);$LangOverwrite=TRUE;}  /* Allow Users Language Overwrite */
If ($lang == "fr"){include 'LanguageFR.php';}else{include 'LanguageEN.php';} ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<script src="STHSMain.js"></script>
<meta name="author" content="Simon Tremblay, sths.simont.info" />
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="Decription" content="Simon Tremblay - STHS - Version : 3.2.7.7 - STHSDemo-STHS.db - STHSDemo-STHSCareerStat.db"/>
<link href="STHSMain.css" rel="stylesheet" type="text/css" />
<?php If (file_exists("STHSMain-CSSOverwrite.css") == True){echo "<link href=\"STHSMain-CSSOverwrite.css\" rel=\"stylesheet\" type=\"text/css\" />";}?>
