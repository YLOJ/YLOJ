<?php
	if(!isset($text))$text=$score;
	if(!isset($score_full))$score_full=100;
	if($text==='/')
		echo '<span class="score">'.$text.'</span>';
	else if($score<=0)
		echo '<span class="score text-danger">'.$text.'</span>';
	else if($score>=$score_full)
		echo '<span class="score text-success">'.$text.'</span>';
	else
		echo '<span class="score" style="color:#ffa500">'.$text.'</span>';
?>
