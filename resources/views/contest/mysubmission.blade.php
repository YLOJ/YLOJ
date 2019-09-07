<!DOCTYPE html>
@extends('layouts.app')
<?php
if($BAN){
	foreach ($submissionset as $id => $sub){
		$submissionset[$id]->result='Unshown';
		if($submissionset[$id]->score>0)$submissionset[$id]->score='>0';
		$submissionset[$id]->time_used='-1';
		$submissionset[$id]->memory_used='-1';
	}
}
?>
@if(!$BAN)
<script src=/js/app.js></script>
<script>
var style={};
style["Waiting"]='class="text-primary"';
style["Accepted"]='class="text-success"';
style["Data Error"]='style="color:#2F4F4F"';
style["Judgement Failed"]='style="color:#2F4F4F"';
style["Compile Error"]='style="color:#696969"';
Echo.channel('Submission')
.listen('.submission.update', (e) => {
	xsub=e.message;
	if('result' in xsub){
		$('#sub'+xsub['id']+" #result").html([
			"<a "+
			(xsub['result'] in style?
				style[xsub['result']]:
				xsub['result'].substring(0,7)=="Running"?
				'style="color:#0033CC"':'class="text-danger"'
			)	+" href="+sub['url']+">"
		,
		"<b>"+xsub['result']+"</b>",
		"</a>"
	].join('\n'));
	}
	if('score' in xsub){
		$('#sub'+xsub['id']+" #score").html([
			"<a "+
			(	xsub['score']=='-1'?
				'class="text-primary"':
				xsub['score']=='100'?
				'class="text-success"':
				xsub['score']>'0'?
				'style="color:orange"':
				'class="text-danger"'
			)	+" href="+sub['url']+">"
		,
		"<b>"+(xsub['score']=='-1'?"/":xsub['score'])+"</b>",
		"</a>"
	].join('\n'));

	}
	if('time' in xsub){
		$('#sub'+xsub['id']+" #time").html(
			xsub['time']>=0?xsub['time']+'ms':'/'
		)
	}
	if('memory' in xsub){
		$('#sub'+xsub['id']+" #memory").html(
			xsub['memory']>=0?xsub['memory']+'kb':'/'
		)
	}
});
</script>
@endif
@section('content')
  <div class="container">
    <p class="text-sm"> </p>
    <p class="text-sm"> </p>

    <div class="row">
      <div class="col">
        <table class="table table-bordered">
          @include('includes.verdict_table') 
          <?php $count = 0; ?>
          <tbody>
            @foreach ($submissionset as $sub)
              @if ($count++ % 2 == 0)
                <tr style="background-color:#F3F3F3">
              @else
                <tr>
              @endif
              @include('includes.verdict', ['sub' => $sub])
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection
