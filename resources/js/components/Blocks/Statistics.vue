<template>
	<transition name="slide-fade">
		<div v-if="statistics">
			<h3>Статистика</h3>
			<div id="static">
				<p>Всего женщин:<a :href="`${getRoute('search', {'find_sex' : 2, 'send' : 1} )}`">{{ statistics.total_women }}</a>({{ statistics.total_women_percent }})</p>
				<p>Всего мужчин:<a :href="`${getRoute('search', {'find_sex' : 1, 'send' : 1} )}`">{{ statistics.total_men }}</a>({{ statistics.total_men_percent }})</p>
				<p>Всего фотографий:<a :href="`${getRoute('search', {'photo' : 1, 'send' : 1} )}`">{{ statistics.total_fotos }}</a></p>
			</div>
		</div>
	</transition>
</template>

<script>
import ajax from "@/Methods/ajax.js";
import asset from "@/Methods/asset.js";
import routes from "@/Methods/routes.js";
export default {
	name: "Statistics",
	data() {
		return {
			statistics: null
		};
	},
	mounted() {
		this.getStats();
	},
	mixins: [ajax, asset, routes],
	methods: {
		getStats() {
			this.ajax(this.getRoute('statistics.get'));
			return false;
		},
		ajaxSuccess(res) {
			this.statistics = res.data;
		}
	},
}
</script>