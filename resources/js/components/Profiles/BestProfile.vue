<template>
	<div>
		<h2 v-if="sex == 2">Лучшая девушка </h2>
		<h2 v-else>Лучший парень </h2>
		<transition name="slide-fade">
			<div v-if="profile" class="bl">
				<h3>{{ profile.name }}, {{ profile.age }} {{ profile.age_type }}</h3>
				<div class="foto">
					<a :href="`${getRoute('ank.id', {'id' : profile.id} )}`" rel="nofollow">
						<img :alt="`${profile.name}, ${profile.age} ${profile.age_type}, ${profile.city ? profile.city.name : ''}`" class="b-lazy loaded" :src="`${profile.photo ? asset('fotos_new/' + profile.photo.id ) : ''}.jpg`"></a></div>
				<p class="links1"><a :href="`${getRoute('ank.id', {'id' : profile.id} )}`" rel="nofollow">смотреть анкету</a></p>
				<p class="links1"><a v-if="sex == 2" :href="`${getRoute('bestankets.sex', {'sex' : 'women'} )}`">лучшие девушки</a>
				<a v-else :href="`${getRoute('bestankets.sex', {'sex' : 'men'} )}`">лучшие парни</a>
				</p>
			</div>
		</transition>
		<div class="blFoot"></div>
	</div>
</template>

<script>
import ajax from "@/Methods/ajax.js";
import asset from "@/Methods/asset.js";
import routes from "@/Methods/routes.js";
export default {
	name: "BestProfile",
	props: ['sex', 'route'],
	data() {
		return {
			profile: null
		};
	},
	mounted() { 
		this.getProfile();
	},
	mixins: [ajax, asset, routes],
	methods: {
		getProfile() {
			this.ajax(this.route);
			return false;
		},
		ajaxSuccess(res)
		{
			this.profile = res.data;
		}
	},
}
</script>