<div class="checkbox-container{{ $topClass ? ' ' . $topClass : '' }}">
@if (!empty($containerTitle))<div class="checkbox-container-title"><strong>{{ $containerTitle }}</strong></div>@endif
	<div class="checkbox-container-body{{ $colsClass ? ' ' . $colsClass : '' }}">
		<input type="hidden" name="{{ $name }}" value="0">
	@foreach ($obj as $item)
		<div class="form-row{{ $colsClass ? ' ' . $colsClass : '' }}">
			<div class="checkbox-div-container"><input id="{{ $clearName}}_{{ $item->id }}" type="checkbox" name="{{ $name }}" value="{{ $item->id }}"@if (!empty($item->selected)) checked="checked"@endif /></div>
			<label for="{{ $clearName}}_{{ $item->id }}">{{ $item->name }}</label>
		</div>
	@endforeach
	</div>
</div>