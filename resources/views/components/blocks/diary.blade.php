<tr>
		<td>
			<h4 class="{{ $item->name_class }}"><a href="{{route('ank.id', $item->user->user_id)}}">{{ $item->user->name }}</a>
				<div class="titDnev"><a href="{{route('ank.id', $item->user->user_id)}}">{!! \Illuminate\Support\Str::limit($item->title, 40, $end='...') !!}</a></div>
				<p>
				@if (!empty($user) && $user->user_id == $item->user_id)
				<a class="editBut" title="редактировать" href="{{route('ank.diary.edit.id', $item->id)}}"></a>
				<a class="delBut" title="удалить" href="{{route('ank.diary.delete.id', $item->id)}}"></a>
				@endif
				{{ $item->add_time }}</p>
			</h4>
			<div class="dnevBody clear">
	@if (!empty($item->picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ (new FileService)->outDiaryPicture($item->picture, $item->user->user_sex) }}" /></a>
	@elseif (!empty($item->foto_user_id))
				<a class="dnevBodyPic2" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ (new FileService)->outPicture($item->foto_user_id, $item->user->user_sex) }}" /></a>
	@endif
	@if (!empty($item->picture))
				<div class="mrg2">
	{!! \Illuminate\Support\Str::limit($item->description, 500, $end='...') !!}
				</div>
	@elseif (!empty($item->foto_url))
				<div class="mrg3">
	{!! \Illuminate\Support\Str::limit($item->description, 500, $end='...') !!}
				</div>
	@else
	{!! \Illuminate\Support\Str::limit($item->description, 500, $end='...') !!}
	@endif
			</div>
			<p class="dnevKomm"><a href="{{route('ank.diary.comments', $item->id)}}">комментарии ({{count ($item->comments)}})</a></p>
		</td>
</tr>