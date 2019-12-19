/*$(document).ready(function() {
	$('body').yloj_highlight();
});*/
var verdict_list=["OK","Accepted","Wrong Answer","Time Limit Exceeded","Memory Limit Exceeded","Runtime Error","Presentation Error","Partially Correct","Skipped","Compile Error","Compiler Time Limit Exceeded","Spj Error","Judgement Failed","Data Error","Waiting","Compiling","Running","Submitted"];
Echo.channel('Submission')
.listen('.submission.update', (e) => {
	xsub=e.message;
	if('result' in xsub){
		$('#sub'+xsub['id']+" #result a").html(
((xsub['result']<=1)?"<b class='text-success'>":(
(xsub['result']<=8)?"<b class='text-danger'>":(
(xsub['result']<=11)?"<b style='color:#696969'>":(
(xsub['result']<=13)?"<b style='color:#2F4F4F'>":(
(xsub['result']==16)?"<b style='color:#0033CC'>":
"<b>")))))
			+verdict_list[xsub['result']]+
			(xsub['result']==16 && xsub['data_id']?" on Test "+xsub['data_id']:"")+
			"</b>");
	}
	if('score' in xsub){
		if(xsub['score']==-1){
			$('#sub'+xsub['id']+" #score a").html("<b>/</b>");
		}else{
			$('#sub'+xsub['id']+" #score a").html(
				"<b "+(xsub['score']==0?"class='text-danger'":(xsub['score']>=100?"class='text-success'":"style='color:#ffa500'"))+">"+xsub['score']+"</b>");
		}
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

