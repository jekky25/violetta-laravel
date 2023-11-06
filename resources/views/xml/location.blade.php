{!! $startStr !!}
@if (!empty ($obj))
<response>
	<countries>
		@foreach ($obj as $item)
			<country>
				<countryid>{{ $item->id }}</countryid>
				<countryname>{{ $item->name }} </countryname>
			</country>
		@endforeach
	</countries>
</response>
@endif