<template>
	<div>
		<h3>Последние записи в дневниках</h3>
		<transition name="slide-fade">
			<div v-if="diaries">
				<div v-for="diary in diaries">
					<div class="dnevnik">
						<h4 :class="`${diary.name_class}`">
							<a :href="`${getRoute('ank.id', {'id' : diary.user_id})}`">{{ diary.user.name }}</a>
							<p>{{ diary.create_time }}</p>
						</h4>
						<h3>
							<a :href="`${getRoute('ank.diary.id', {'id' : diary.user_id})}`" :class="`${diary.name_class}`" v-html="diary.title"></a>
						</h3>
						<div v-if="diary.picture_url" class="dnevPict">
							<a :href="`${getRoute('ank.diary.id', {'id' : diary.user_id})}`"><img :src="`${asset(diary.picture_url)}`" alt="" /></a>
						</div>
						<p class="dnevText" v-html="diary.description_brief"></p>
					</div>
					<a :href="`${getRoute('ank.diary.comments', {'id' : diary.id})}`" class="comLink">комментарии ({{ diary.comments_count }})</a>
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