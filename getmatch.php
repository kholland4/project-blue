<?php
require "util.php";
require "prefs.php";
require "match.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<?php
header("Content-Type: text/json");

function curveFunc($x) {
  return 0.01 * pow(($x - 55), 2) + 20;
}

if($MATCH_ALGO == "weighted") {
  $EXP = 1.5;

  $target = prefs_get_arr($conn, $userid);
  $people = match_get_raw($conn, $userid);
  $scores = array();
  foreach($people as $person) {
    $scoresRaw = array();
    foreach($target as $t_p => $t_l) {
      $found = false;
      foreach($person["prefs"] as $p_p => $p_l) {
        if($t_p == $p_p) { //found matching preference
          $maxRange = ($PREF_LEVEL_MAX - $PREF_LEVEL_MIN + 1);
          $p_l = min(max($p_l, $PREF_LEVEL_MIN), $PREF_LEVEL_MAX); //constrain
          $t_l = min(max($t_l, $PREF_LEVEL_MIN), $PREF_LEVEL_MAX); //constrain
          $s = $maxRange - abs($p_l - $t_l); //calculate the match difference (higher is better)
          $s = pow($s, $EXP) / pow($maxRange, $EXP); //logarithmetic weighting and convert to percent
          array_push($scoresRaw, array("matchLevel" => $s, "importance" => $t_l, "pref" => $t_p)); //TODO: logaritmetic importance
          $found = true;
        }
      }
      if(!$found) {
        array_push($scoresRaw, array("matchLevel" => 0.0, "importance" => $t_l, "pref" => $t_p));
      }
    }
    
    $score = 0;
    $totalWeight = 0;
    foreach($scoresRaw as $s) {
      $score += $s["matchLevel"] * $s["importance"];
      $totalWeight += $s["importance"];
    }
    $score /= $totalWeight;
    array_push($scores, array("person" => $person, "score" => $score, "detail" => $scoresRaw));
  }

  //TODO: sort scores
  /*foreach($scores as $score) {
    echo $score["person"]["username"] . " - " . $score["score"] . "\n";
    foreach($score["detail"] as $d) {
      echo "  " . str_pad(round($d["matchLevel"] * 100, 2) . "%", 6) . " " . str_pad($d["importance"], 2) . " " . $prefData[$d["pref"]]["name"] . "\n";
    }
  }*/
  echo json_encode($scores);
} else if($MATCH_ALGO == "flat") {
  /*Any user has to select at least 10 interests. In order to have a match and have other user appear on your feed and you appear on their's, at least 40% of your selected intererests have to be in the other users list of selected interest. In order to accomodate for differences in number of things selected accross different users. In order to match the algorithm has to use as reference the 40% of the user that has the lest amount of things selected.   
I as a user with 10 interests selected would match with someone with 40 interests selected if he has in his list at least 4 things that I also have */
  $target = prefs_get_arr($conn, $userid);
  $people = match_get_raw($conn, $userid);
  $scores = array();
  foreach($people as $person) {
    $scoresRaw = array();
    foreach($target as $t_p => $t_l) {
      foreach($person["prefs"] as $p_p => $p_l) {
        if($t_p == $p_p) { //found matching preference
          array_push($scoresRaw, array("matchLevel" => 1.0, "importance" => $t_l, "pref" => $t_p));
        }
      }
    }
    
    /*$score = 0;
    if(count($target) > 0) {
      $score = count($scoresRaw) / count($target);
    }
    $scoreAlt = 0;
    if(count($person["prefs"]) > 0) {
      $scoreAlt = count($scoresRaw) / count($person["prefs"]);
    }*/
    
    if(count($target) == 0 || count($person["prefs"]) == 0) {
      continue;
    }
    
    $amount = count($scoresRaw);
    
    $targetOne = curveFunc(count($target)) * 0.01 * count($target);
    $targetTwo = curveFunc(count($person["prefs"])) * 0.01 * count($person["prefs"]);
    
    $scoreOne = (($amount - $targetOne) / count($target)) + 0.5; //FIXME
    $scoreTwo = (($amount - $targetTwo) / count($person["prefs"])) + 0.5; //FIXME
    
    if($scoreOne >= 0.5 || $scoreTwo >= 0.5 || array_key_exists("all", $_GET)) {
      array_push($scores, array("person" => $person, "score" => $scoreOne, "matchCount" => $amount, "detail" => $scoresRaw));
    }
  }
  echo json_encode($scores);
}
?>
