<template>
	<transition name="slide-fade">
		<div v-if="forums">
			<div class="blFoot"></div>
			<h2>Последние темы форума</h2>
			<div class="bl">
				<!--noindex-->
				<ul>
					<li v-for="forum in forums"><a
							:href="`${getRoute('forum.topic', { '$forum_id': forum.forum_id, '$topic_id': forum.id })}`"
							rel="nofollow">{{ forum.title }}</a></li>
				</ul>
				<!--/noindex-->
			</div>
		</div>
	</transition>
</template>

<script>
import ajax from "@/Methods/ajax.js";
import asset from "@/Methods/asset.js";
import routes from "@/Methods/routes.js";
export default {
	name: "ForumTop",
	data() {
		return {
			forums: null
		};
	},
	mounted() {
		this.getForums();
	},
	mixins: [ajax, asset, routes],
	methods: {
		getForums() {
			this.ajax(this.getRoute('forum.get.top'));
			return false;
		},
		ajaxSuccess(res) {
			this.forums = res.data;
		}
	},
}
</script>