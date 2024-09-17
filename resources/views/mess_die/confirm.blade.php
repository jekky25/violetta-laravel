@extends('layouts.app')
@section('title', $title)
@section('main_body')
<!--main_begin-->
<table width="100%">
	<tr height="50" valign="middle">
		<td align="center">
			<h1 class="main_title">{{ $msgTitle }}</h1>
		</td>
	</tr>
	<tr valign="top">
		<td align="center" style="padding-left:30px; padding-right:30px;">
			<table width="100%">
				<tr height="150" valign="middle">
					<td align="center" bgcolor="#fcfbd6" style="border: #fe5804 1px solid;">
						<form name="anket" action="{{ $confirmAction }}" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							@if (!empty($hidden)){!!$hidden!!}@endif
							<span class="name">{!! $msgText !!}
								<input type="submit" name="confirm" value="Да" style="width:50px">&nbsp;&nbsp;&nbsp;
								<input type="submit" name="cancel" value="Нет" style="width:50px">
							</span>
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!--main_end-->
@overwrite