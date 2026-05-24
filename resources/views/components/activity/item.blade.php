<div class="active-item-container">
	<div class="active-item-title {{ $item->name_line_class }}">
		<a class="active-item-name" href="{{route('ank.id', $item->user->id)}}">{{ $item->user->name }}</a>
		<div class="active-item-right">
			@if (!empty($user) && $user->id == $item->user_id)
			<a class="edit" title="редактировать" href="{{route($routeEdit, $item->id)}}"></a>
			<a 
						class="delete open-modal"
						title="удалить"
						href="javascript:void(0);"
						data-url="{{route($routeDelete, $item->id)}}"
						data-title="Удаление записи"
						data-text="Вы уверены, что хотите удалить запись?"
						></a>
			@endif
			<span class="active-item-time">{{ $item->add_time }}</span>
		</div>
	</div>
	<div class="active-item-body">
		<div class="active-item-body-title {{ $item->name_class }}"><a href="{{route('ank.diary.id', $item->user->id)}}">{{ $item->title }}</a></div>
		<div class="active-item-content clear">
			@if (!empty($pictureSrc))
			<a class="active-item-picture" href="{{route('ank.id', $item->user->id)}}"><img src="{{ $pictureSrc }}" /></a>
			@endif
			{!! $item->description !!}
		</div>
		@if ($item->comments !== null)
		<p class="comment"><a href="{{route('ank.diary.comments', $item->id)}}">комментарии ({{count ($item->comments)}})</a></p>
		@endif
	</div>
</div>