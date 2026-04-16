<div class="picturEditCont">
	<a class="dnevBodyPic2" href="{{route('ank.id', $photo->user_id)}}"><img src="{{ $photo->url }}" /></a>
	<a class="editBut3" title="редактировать" href="{{route('registration.edit.photo.edit', $photo->id)}}"></a>
	<a 
		class="delBut3 open-modal" 
		title="удалить" 
		href="javascript:void(0);"
		data-url="{{route('registration.edit.photo.delete.action', $photo->id)}}"
		data-title="Удаление фото"
		data-text="Вы уверены, что хотите удалить фото?"
	></a>
</div>