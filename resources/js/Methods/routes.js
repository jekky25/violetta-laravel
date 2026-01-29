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
				let dataNotFoundened = new Object();
				if (this.__routes === null) this.getRoutes();

				let __route = this.__routes[name];
				if (__route.lastIndexOf('.html') < 0) __route += '/';

				if (data === null) return this.asset(__route);
				
				for (var key in data) {
					if (__route.indexOf(key) >= 0) {
						__route = __route.replace('{' + key + '}', data[key]);
					} else {
						dataNotFoundened[key] = data[key];
					}
				}
				__route = this.getRouteBehindSlash(__route, dataNotFoundened);

				return this.asset(__route);
			},
			getRouteBehindSlash(__route, dataNotFoundened)
			{
				if (Object.keys(dataNotFoundened).length === 0) return __route;
				
				__route += '?';
				let i = 0;
				for (var key in dataNotFoundened) {
					if (i > 0) __route += '&';
					__route += key + '=' + dataNotFoundened[key];
					i++;
				}
				return __route;
			}
		}
	}