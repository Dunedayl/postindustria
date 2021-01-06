import { BaseComponent } from '../../framework/index'
import { router } from '../../framework/tools/router'


class HomeComponent extends BaseComponent {

	constructor(config) {
		super(config)
	}

	events() {
		return [
			{
				type: 'click',
				selector: '.sendbtn',
				handler: (e) => this.onClickLogOut(e)
			}
		]
	}

	onClickLogOut() {
		axios({
			method: 'post',
			withCredentials: true,
			url: 'http://restapi.loc/logout'
		}).then((response) => {
			router.navigate('')
		}).catch(err => {
			console.log("catch");
			console.log(err);
		}).finally(() => {
		});
	}

	afterInit() {
		console.log("ReqStart")
		let firstname
		let lastname

		axios({
			method: 'get',
			withCredentials: true,
			url: 'http://restapi.loc/home',
		}).then((response) => {
			console.log(response.data);
			firstname = response.data.firstname
			lastname = response.data.lastname
			console.log(firstname);
			console.log(lastname);
			let username = "Hello " + firstname + " " + lastname + "!"
			document.getElementById("username").innerHTML = username;
		}).catch(err => {
			console.log("catch");
			console.log(err);
			router.navigate('')
		}).finally(() => {
		});

		document.querySelectorAll('.coltohide').forEach(e => e.classList.add('hidenDiv'))
	}

}
export const homeComponent = new HomeComponent({
	selector: "app-home-page",
	template: `
	<h1 id = "username" class = "username"></h1>
	<button type="submit" class="waves-effect waves-light btn sendbtn">{{LogOut}}</button>
`,
});