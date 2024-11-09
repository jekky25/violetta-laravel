<div class="picturEditCont">
	<a class="dnevBodyPic2" href="{{route('ank.id', $photo->user_id)}}"><img src="{{ (new FileService)->outPicture($photo->fotos_id, $user->user_sex) }}" /></a>
	<a class="editBut3" title="редактировать" href="{{route('registration.edit.photo.edit', $photo->fotos_id)}}"></a>
	<a class="delBut3" title="удалить" href="{{route('registration.edit.photo.delete', $photo->fotos_id)}}"></a>
</div>