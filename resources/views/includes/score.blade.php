<?php
	if(!isset($text))$text=$score;
	if(!isset($score_full))$score_full=100;
	if($score<0)
		echo '<span class="score">'.$text.'</span>';
	else if($score==0)
		echo '<span class="score" style="color:#cc0000">'.$text.'</span>';
	else if($score>=$score_full)
		echo '<span class="score" style="color:#00cc00">'.$text.'</span>';
	else
		echo '<span class="score" style="color:#ffa500">'.$text.'</span>';
?>
