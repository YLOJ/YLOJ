<!DOCTYPE html>
@extends('layouts.app')

@section('content')
  <?php
    if (isset($_GET['keyword']))
      $keyword = $_GET['keyword'];
    else $keyword = "";
  ?>

    <div class="mdui-row mdui-container mdui-center">
        <div class="mdui-textfield  mdui-col-offset-xs-0 mdui-col-offset-sm-3 mdui-col-xs-10 mdui-col-sm-4">
		  <input class="mdui-textfield-input" type="text" placeholder="搜索标题" id="keyword" value={{$keyword}}>
        </div>
        <div style="padding-top:16px;" class="mdui-col-xs-2">
           <button class="mdui-btn mdui-btn-icon mdui-color-theme-accent mdui-btn-raised mdui-ripple" id="submit"><i class="mdui-icon material-icons">search</i></button>
		<script>
			$("#keyword").keyup(function(event){  
               if(event.keyCode ==13){  
					self.location.href="/problem?keyword="+$("#keyword").val()
               }  
             });
			$("#submit").click(function(){
				self.location.href="/problem?keyword="+$("#keyword").val()
			});
		</script>
	    @auth
	      @if ( Auth::user()->permission > 1 )
           <a id="add-problem" class="mdui-btn mdui-btn-icon mdui-color-theme-accent mdui-btn-raised mdui-ripple" href="/problem/add"><i class="mdui-icon material-icons">add</i></a>
<script> 
var inst = new mdui.Tooltip('#add-problem', {
  content: 'Add Problem'
});
</script>

    	  @endif
	    @endauth
        </div>
    </div>
    <br>
    @component('includes.problem_table', ['problemset' => $problemset])
    @endcomponent 

	<div style="text-align:center">
    <?php
		$links = $problemset -> links();
		if (isset($_GET['keyword'])){
        	$arr = explode('?', $links);
			echo implode('?keyword='.$keyword.'&',$arr);
		}
		else echo $links;
    ?>


    </div>
@endsection
