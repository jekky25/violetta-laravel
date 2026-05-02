<template>
	<table>
		<tr>
			<td><p>Рейтинг:</p></td>
			<td class="wth4">
				<div class="div-rating2">
					<ul class="div-rating">
						<li class="current-rating" :style="`width:${user.rating_str}px;`">&nbsp;</li>
						<template v-if="authUser !== null && localEvaluationed !== true">
							<li><a href="#" @click.prevent="sendVote(1)" title="Очень плохо" class="r1-unit rater">Очень плохо</a></li>
							<li><a href="#" @click.prevent="sendVote(2)" title='Плохо' class="r2-unit rater">Плохо</a></li>
							<li><a href="#" @click.prevent="sendVote(3)" title='Средне' class="r3-unit rater">Средне</a></li>
							<li><a href="#" @click.prevent="sendVote(4)" title='Хорошо' class="r4-unit rater">Хорошо</a></li>
							<li><a href="#" @click.prevent="sendVote(5)" title='Отлично' class="r5-unit rater">Отлично</a></li>
						</template>
					</ul>
				</div>
			</td>
		</tr>
	</table>
</template>

<script>
import routes from "@/Methods/routes.js";
export default {
	name: "Rating",
	props: {
		user: Object,
		authUser:  {
   			type: Object,
			default: null
  		},
		evaluationed: {
   			type: Boolean,
			default: false
  		}
	},
	data() {
		return {
			localEvaluationed: this.evaluationed,
	        res: null,
    	    errors: null
		};
	},
	mixins: [routes],
	mounted() {

	},
	methods: {
		sendVote(vote)
		{
			axios.post(this.getRoute('evaulation.store', {id: this.user.id}), { vote: vote })
				.then(res => {
					this.res = res.data;
					if (this.res.success === true)
					{
						this.localEvaluationed = true;
					}
				})
				.catch(res => {
					this.errors = res.data;
				});
		}
	}
}
</script>