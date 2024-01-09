<ul id="menuReg" class="clear">
	@if (Route:: currentRouteName() == 'ank.id')
	<li class="menuRegAct">Основное</li>
	@else
	<li><a href="{{route('ank.id', $userData->user_id)}}">Основное</a></li>
	@endif
	@if (Route:: currentRouteName() == 'ank.full.id')
	<li class="menuRegAct">Подробно</li>
	@else
	<li><a href="{{route('ank.full.id', $userData->user_id)}}">Подробно</a></li>
	@endif
	@if ($userData->user_fotos > 0)
		@if (Route:: currentRouteName() == 'ank.photo.id')
		<li class="menuRegAct">Фотоальбом ({{ $userData->user_fotos }} фото)</li>
		@else
		<li><a href="{{route('ank.photo.id', $userData->user_id)}}">Фотоальбом ({{ $userData->user_fotos }} фото)</a></li>
		@endif		
	@endif
	@if ($userData->number_diary > 0)
	@if (Route:: currentRouteName() 	== 'ank.diary.id'
		 || Route:: currentRouteName() 	== 'ank.diary.edit.id'
		 || Route:: currentRouteName() 	== 'ank.diary.comments')
		<li class="menuRegAct">Дневник ({{ $userData->number_diary_str }})</li>
		@else
		<li><a href="{{route('ank.diary.id', $userData->user_id)}}">Дневник ({{ $userData->number_diary_str }})</a></li>
		@endif
    @endif
</ul>