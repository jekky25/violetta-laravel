@extends('layouts.frame')
@section('main_body')
<h1 class="mTit">Удаление анкеты</h1>
@if (!empty($is_del))
<p class="pad3">Ваша анкета удалена. Но мы надеемся, что Вы еще вернетесь.</p>
@else
<p class="pad3">Вы уверены, что хотите удалить анкету?</p>
<p class="pad3"></p>
<table class="voprTabl">
	<tr>
		<td><a href="{$smarty.const.SITE_URL}index.php?mod=registration&op=edit&subop=del_ank&frame=1&otsil=1" class="bgBut9"></a></td>
		<td><a href="javascript://" onClick="hideW(); return false;" class="bgBut10"></a></td>
	</tr>
</table>
<script type="text/javascript">
function hideW() {
	$(document).ready(function() {
		parent.$('#mask').hide();
		parent.$('#prodblock').hide();
	});	
	return false;
}
</script>
@endif
@overwrite