@if (!empty($multiple))
<input type="hidden" name="{{ $name }}" value="0">
@endif
<select class="select1" name="{{ $name }}" 
	@if (!empty($id))id="{{ $id }}" @endif
	@if (!empty($multiple))multiple @endif
	@if (!empty($size))size="{{ $size }}" @endif 
	@if (!empty($addition)){{ $addition }} @endif
autocomplete="off">
	<option value="0">-не важно-</option>
	@if (!empty($firstInList)){{$firstInList}}@endif
	@foreach ($obj as $item)
	@if (!is_object($item))
		<option value="{{ $item }}" @if ($item == $userProp) selected="selected"@endif>{{ $item }}@if (!empty($measure)) {{ $measure }}@endif</option>
	@else
		<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}@if (!empty($measure)) {{ $measure }}@endif</option>
	@endif
	@endforeach
</select>