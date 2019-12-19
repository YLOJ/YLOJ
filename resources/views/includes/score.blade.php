<?php
	if(!isset($text))$text=$score;
	if(!isset($score_full))$score_full=100;
	if($text==='/')
		echo '<b>'.$text.'</b>';
	else if($score<=0)
		echo '<b class="text-danger">'.$text.'</b>';
	else if($score>=$score_full)
		echo '<b class="text-success">'.$text.'</b>';
	else
		echo '<b style="color:#ffa500">'.$text.'</b>';
?>
