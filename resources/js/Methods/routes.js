import asset from "@/Methods/asset.js";
export default
	{
		data() {
			return {
				__routes: null,
			}
		},
		mixins: [asset],
		methods: {
			getRoutes() {
				this.__routes = window._routes || '';
				this.routeToObject();
				return this.__routes;
			},
			routeToObject()
			{
				this.__routes = JSON.parse(window.atob(this.__routes));
				return this.__routes;
			},
			getRoute(name, data = null)
			{
				if (this.__routes === null) this.getRoutes();

				let __route = this.__routes[name];
				if (__route.lastIndexOf('.html') < 0) __route += '/';

				if (data === null) return this.asset(__route);
				
				for (var key in data) {
					__route = __route.replace('{' + key + '}', data[key]);
				}
				return this.asset(__route);
			}
		}
	}