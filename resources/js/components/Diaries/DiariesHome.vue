<template>
	<div>
		<h3>Последние записи в дневниках</h3>
		<transition name="slide-fade">
			<div v-if="diaries">
				<div v-for="diary in diaries">
					<div class="active-item-container">
							<div :class="`active-item-title line-${diary.name_class}`">
								<a class="active-item-name" :href="`${getRoute('ank.id', {'id' : diary.user_id})}`">{{ diary.user.name }}</a>
								<div class="active-item-right">
									<span class="active-item-time">{{ diary.create_time }}</span>
								</div>
							</div>
							<div class="active-item-body">
								<div :class="`active-item-body-title ${diary.name_class}`"><a :href="`${getRoute('ank.id', {'id' : diary.user_id})}`" v-html="diary.title"></a></div>
								<div class="active-item-content clear">
									<a v-if="diary.picture_url" class="active-item-picture" :href="`${getRoute('ank.id', {'id' : diary.user_id})}`"><img :src="`${asset(diary.picture_url)}`" alt="" /></a>
									<div v-html="diary.description_brief"></div>
								</div>
								<p class="comment"><a :href="`${getRoute('ank.diary.comments', {'id' : diary.id})}`">комментарии ({{ diary.comments_count }})</a></p>
							</div>
					</div>
				</div>
				<a class="comLink left1 all-dnev-link" :href="`${getRoute('diaries')}`">все дневники >></a>
			</div>
		</transition>
	</div>
</template>

<script>
import ajax from "@/Methods/ajax.js";
import asset from "@/Methods/asset.js";
import routes from "@/Methods/routes.js";
export default {
	name: "NewFaces",
	data() {
		return {
			diaries: null,
		};
	},
	mounted() {
		this.getDiaries();
	},
	mixins: [ajax, asset, routes],
	methods: {
		getDiaries() {
			this.ajax(this.getRoute('home.diaries'));
			return false;
		},
		ajaxSuccess(res)
		{
			this.diaries = res.data;
		}
	}
}
</script>