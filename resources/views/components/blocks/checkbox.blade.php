@foreach ($obj as $item)
	@if ($loop->first or $loop->index % 2 == 0)
	<tr>
	@endif
	    <td>
			@if ($loop->first)<input type="hidden" name="{{ $name }}" value="0">@endif
			<input type="checkbox" name="{{ $name }}" value="{{ $item->id }}" @if (!empty($item->selected)) checked="checked"@endif /></td>
		<td class="left1"><span>{{ $item->name }}</span></td>
	@if ($loop->last or $loop->index % 2 != 0)
	</tr>
	@endif
@endforeach