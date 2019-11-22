<div class="mdui-panel" mdui-panel>
  <div class="mdui-panel-item mdui-panel-item-open {{isset($main)?'mdui-hoverable':''}}">
    <div class="mdui-panel-item-header">
      <div class="mdui-panel-item-title">{{$title}}</div>
      <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
    </div>
    <div class="mdui-panel-item-body" style="padding:0 12px 0 12px">
		{{$slot}}
    </div>
  </div>
</div>
