@if (!empty($multiple))
<input type="hidden" name="{{ $name }}" value="0">
@endif
<select class="select" name="{{ $name }}" 
	@if (!empty($id))id="{{ $id }}" @endif
	@if (!empty($multiple))multiple @endif
	@if (!empty($size))size="{{ $size }}" @endif 
	@if (!empty($addition)){{ $addition }} @endif
autocomplete="off">
	<option value="0">@if (!empty($fieldZero)){{ $fieldZero }} @else -не важно-@endif</option>
	@if (!empty($firstInList)){{$firstInList}}@endif
	@foreach ($obj as $id => $item)
	@if (!is_object($item))
		@if ($item > 0)
		<option value="{{ $id }}" @if ($id == $userProp) selected="selected"@endif>{{ $item }}@if (!empty($measure)) {{ $measure }}@endif</option>
		@endif
	@else
		<option value="{{ $item->id }}"@if ($item->id == $userProp) selected="selected"@endif>{{ $item->name }}@if (!empty($measure)) {{ $measure }}@endif</option>
	@endif
	@endforeach
</select>