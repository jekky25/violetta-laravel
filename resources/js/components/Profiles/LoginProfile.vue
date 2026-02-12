<template>
		<transition name="slide-fade">
			<div v-if="isSuccess">
				<div v-if="user">
					<h2>Рабочее меню</h2>			
					<div class="bl AccMenu">
						<h3>{{ user.name }} !</h3>
						<ul>
							<li><a class="name_my_mess" :href="`${getRoute('privmsg')}`">Мои сообщения</a> <span :class="`${user.new_messages > 0 ? 'red_mark' : 'green_mark'}`">({{ user.new_messages }})</span></li>
							<li><a :href="`${getRoute('registration.edit')}`">Мой профиль</a></li>
							<li><a :href="`${getRoute('ank.id', { 'id' : user.id })}`">Моя анкета</a></li>
							<li><a :href="`${getRoute('registration.edit.photo')}`">Мои фото</a></li>
							<li><a :href="`${getRoute('registration.edit.diary')}`">Мой дневник</a></li>
							<li><a :href="`${getRoute('registration.edit.settings')}`">Мои настройки</a></li>
							<li><a class="inTop" :href="`${getRoute('registration.top100')}`"><template v-if="(user.photos_count > 0  &&  user.top100 > 0)">поднять анкету</template><template v-else>попасть в топ</template></a></li>
						</ul>
						<p>Последний визит:  {{ user.lastvisit_format }} </p>
						<p>Просмотров за месяц: <a v-if="(user.monthVisits > 0)" :href="`${getRoute('registration.views')}`" class="views_l"> {{ user.monthVisits }} </a><template v-else>{{ user.monthVisits }}</template>
						<span v-if="(user.monthVisitsNew > 0)" class="views_l_new"> + <a :href="`${getRoute('registration.views')}`"> {{ user.monthVisitsNew }} </a></span></p>
						<p class="logOutBut"><a :href="`${getRoute('logout')}`">Выход</a></p>
					</div>
				</div>
				<div v-else>
					<h2>Вход для пользователей</h2>
					<div class="bl logForm">
						<form name="login" action="javascript:void(null);" method="post">
							<input type="hidden" name="_token" :value="csrf">
							<div class="error-text" v-html="errors" v-if="errors"></div>
							<dl>
								<dt>Ваш логин:</dt>
								<dd><input type="text" class="input" ref="login" v-model="login" name="login" /></dd>
							</dl>
							<dl>
								<dt>Пароль:</dt>
								<dd><input type="password" class="input" ref="password" v-model="password" name="password" /></dd>
							</dl>
							<p class="pad1"><input class="button" type="submit" v-on:click="loginSend()" value="войти" /></p>
						</form>
						<p><a class="name" style="padding-right: 20px;" :href="`${getRoute('forget_pass')}`">Забыли пароль?</a></p>
						<p><a class="name" style="padding-right: 20px;" :href="`${getRoute('registration')}`">Зарегистрироваться</a></p>
					</div>
				</div>
				<div class="blFoot"></div>
			</div>
		</transition>
</template>

<script>
import ajax from "@/Methods/ajax.js";
import asset from "@/Methods/asset.js";
import routes from "@/Methods/routes.js";
export default {
	name: "LoginProfile",
	data() {
		return {
			isSuccess: false,
			user: null,
			login: null,
			password: null,
			errors: '',
			data: Object
		};
	},
	mounted() { 
		this.getAuth();
	},
	mixins: [ajax, asset, routes],
	methods: {
		getAuth() {
			this.ajax(this.getRoute('auth.get'));
			return false;
		},
		ajaxSuccess(res)
		{
			this.user = res.data;
			this.isSuccess = true;
		},
		loginSend() 
		{
			this.clearParams();
			let data = {};
			data.login		= this.$data.login;
			data.password	= this.$data.password;
			data._token		= document.querySelector('meta[name="csrf-token"]').getAttribute('content');
			this.post(this.getRoute('login.api'), data);
		},
		post(route, data) {
			axios.post(route, data)
				.then(res => {
					this.ajaxPostSuccess(res);
				})
				.catch(res => {
					this.ajaxPostError(res);
				});
			return false;
		},
		ajaxPostError(res) {
			this.$data.errors += '<p>' + res.response.data.message + '</p>';
		},
		ajaxPostSuccess(res) {
			window.location.href = this.getRoute('home');
		},
		clearParams() {
				this.$data.errors = '';
		}
	},
}
</script>