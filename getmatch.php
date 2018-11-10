<?php
require "util.php";
require "prefs.php";
require "match.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<?php
header("Content-Type: text/json");

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
  
}
?>
