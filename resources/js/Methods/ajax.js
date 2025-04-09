export default
	{
		methods: {
			ajax(route) {
				axios.get(route)
					.then(res => {
						this.ajaxSuccess(res);
					})
					.catch(res => {
						this.ajaxError(res);
					});
				return false;
			},
			ajaxError(res) {
				this.$data.errors += '<p>' + res.response.data.message + '</p>';
			},
			ajaxSuccess(res) { }
		}
	}