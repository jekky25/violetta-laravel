<select class="select1" name="{{ $name }}" @if (!empty($multiple))multiple @endif @if (!empty($size))size="{{ $size }}" @endif autocomplete="off">
	<option value="0">-не важно-</option>
	@foreach ($obj as $item)
	@if (!empty($type) && $type == 'I')
		<option value="{{ $item }}" @if ($item == $UserProp) selected="selected"@endif>{{ $item }}@if (!empty($measure)) {{ $measure }}@endif</option>
	@else
		<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}@if (!empty($measure)) {{ $measure }}@endif</option>
	@endif
	@endforeach
</select>