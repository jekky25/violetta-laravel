<template>
	<div>
		<transition name="slide-fade">
			<div v-if="profiles">
				<h3 class="for-pc">Новые лица на сайте знакомств</h3>
				<dl v-for="(profile, index) in profiles" :class="`${index >= 4 ? 'for-pc' : ''}`">
					<dt>
						<!--noindex-->
						<a :href="`${getRoute('ank.id', {'id' : profile.id} )}`" rel="nofollow">
							<img :alt="`${profile.name}, ${profile.age}${profile.age_type}, ${profile.city.name}`"  :src="`${asset('fotos_new/' + profile.photo.id)}.jpg`" />
						</a>
						<!--/noindex-->
					</dt>
					<dd>
						<p><!--noindex--><img v-if="profile.user_reg_is" title="на сайте" class="online" alt="на сайте" :src="`${asset('image/on_line.gif')}`" /><a :href="`${getRoute('ank.id', {'id' : profile.id} )}`"
							:class="`${profile.sex == men ? 'name_man' : 'name_woman'}`" rel="nofollow">{{ profile.name }}</a><!--/noindex-->
							<img v-if="profile.sex == men" alt="Мужчина" :src="`${asset('image/sex_men.jpg')}`" />
							<img v-else alt="Женщина" :src="`${asset('image/sex_women.jpg')}`" />
							<span>({{ profile.photos_count }} фото)</span>
							<p><span class="st1">{{ profile.age }} {{ profile.age_type }}</span>, {{ profile.city.name }}</p>
							<p><span class="st1">Ищу:</span> {{ profile.find_sex_orient }}</p>
						</p>
					</dd>
				</dl>
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
			profiles: null,
			men: null,
			women:null
		};
	},
	mounted() {
		this.men	= this.$attrs.men;
		this.women	= this.$attrs.women;
		this.getProfiles();
	},
	mixins: [ajax, asset, routes],
	methods: {
		getProfiles() {
			this.ajax(this.getRoute('newfaces.get'));
			return false;
		},
		ajaxSuccess(res)
		{
			this.profiles = res.data;
		}
	}
}
</script>