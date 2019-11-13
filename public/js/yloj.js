function getColOfScore(score) {
	if (score<=0) {
		return ColorConverter.toStr(ColorConverter.toRGB(new HSV(0, 100, 80)));
	} else if (score>=1) {
		return ColorConverter.toStr(ColorConverter.toRGB(new HSV(120, 100, 80)));
	} else {
		return "rgb(256,165,0)";
//		return ColorConverter.toStr(ColorConverter.toRGB(new HSV(33, 100, 90)));
//		return ColorConverter.toStr(ColorConverter.toRGB(new HSV(15 + score * 90, 100, 90)));
	}
}
$.fn.yloj_highlight = function() {
	return $(this).each(function() {
		$(this).find(".score").each(function() {
			var score = parseInt($(this).data('score'));
			var maxscore = parseInt($(this).data('max'));
			if (isNaN(score)) {
				return;
			}
			if (isNaN(maxscore)) {
				$(this).css("color", getColOfScore(score / 100));
			} else {
				$(this).css("color", getColOfScore(score / maxscore));
			}
		});
	});
}

$(document).ready(function() {
	$('body').yloj_highlight();
});
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
			)	+" href=/submission/"+xsub['id']+">"
		,
		"<b>"+xsub['result']+"</b>",
		"</a>"
	].join('\n'));
	}
	if('score' in xsub){
		$('#sub'+xsub['id']+" #score .score").css("color", getColOfScore(xsub['score'] / 100));
		$('#sub'+xsub['id']+" #score .score").html(xsub['score']);
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

