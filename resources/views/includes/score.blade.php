<?php
	if(!isset($text))$text=$score;
	if(!isset($score_full))$score_full=100;
	echo '<span class="score" data-max='.$score_full.' data-score='.$score.'>'.$text.'</span>';
?>
