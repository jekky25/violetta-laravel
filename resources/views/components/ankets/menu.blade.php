<ul id="menuReg" class="clear">
	@if (Route:: currentRouteName() == 'ank.id')
	<li class="menuRegAct">Основное</li>
	@else
	<li><a href="{{route('ank.id', $userData->id)}}">Основное</a></li>
	@endif
	@if (Route:: currentRouteName() == 'ank.full.id')
	<li class="menuRegAct">Подробно</li>
	@else
	<li><a href="{{route('ank.full.id', $userData->id)}}">Подробно</a></li>
	@endif
	@if ($userData->photos_count > 0)
		@if (Route:: currentRouteName() == 'ank.photo.id')
		<li class="menuRegAct">Фотоальбом ({{ $userData->photos_count }} фото)</li>
		@else
		<li><a href="{{route('ank.photo.id', $userData->id)}}">Фотоальбом ({{ $userData->photos_count }} фото)</a></li>
		@endif		
	@endif
	@if ($userData->number_diary > 0)
	@if (Route:: currentRouteName() 	== 'ank.diary.id'
		 || Route:: currentRouteName() 	== 'ank.diary.edit.id'
		 || Route:: currentRouteName() 	== 'ank.diary.comments'
		 || Route:: currentRouteName() 	== 'ank.diary.comment.edit.id')
		<li class="menuRegAct">Дневник ({{ $userData->number_diary_str }})</li>
		@else
		<li><a href="{{route('ank.diary.id', $userData->id)}}">Дневник ({{ $userData->number_diary_str }})</a></li>
		@endif
    @endif
</ul>