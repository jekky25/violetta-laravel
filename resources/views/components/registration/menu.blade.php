<ul id="menuReg" class="clear mb-5">
	@if (Route:: currentRouteName() == 'registration.edit')
	<li class="menuRegAct">Основное</li>
	@else
	<li><a href="{{route('registration.edit')}}">Основное</a></li>
	@endif
	@if (Route:: currentRouteName() == 'registration.edit.second')
	<li class="menuRegAct">Дополнительное</li>
	@else
	<li><a href="{{route('registration.edit.second')}}">Дополнительное</a></li>
	@endif
	@if (Route:: currentRouteName() == 'registration.edit.partner')
	<li class="menuRegAct">О партнере</li>
	@else
	<li><a href="{{route('registration.edit.partner')}}">О партнере</a></li>
	@endif
	@if (Route:: currentRouteName() == 'registration.edit.photo' || Route:: currentRouteName() == 'registration.edit.photo.edit')
	<li class="menuRegAct">Фотографии</li>
	@else
	<li><a href="{{route('registration.edit.photo')}}">Фотографии</a></li>
	@endif
	@if (Route:: currentRouteName() == 'registration.edit.password')
	<li class="menuRegAct">Смена пароля</li>
	@else
	<li><a href="{{route('registration.edit.password')}}">Смена пароля</a></li>
	@endif
	@if (Route:: currentRouteName() == 'registration.edit')
	<li><a href="javascript:void(0);" 
   		class="open-modal"
   		data-url="{{route('registration.delete')}}"
		data-title="Удаление анкеты"
		data-text="Вы уверены, что хотите удалить анкету?"
		>Удалить анкету</a></li>
	@endif
</ul>