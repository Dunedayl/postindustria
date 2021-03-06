import axios from 'axios'


axios.defaults.baseURL = "http://localhost/";
axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
axios.defaults.headers.common['Accept'] = 'application/json';

axios.interceptors.request.use(
	(config) => {
		config.headers['Authorization'] = 'Bearer ' + localStorage.getItem('token');
		return config;
	}
);