import { WFMComponent } from '../../framework/index'
import { router } from '../../framework/tools/router'

class SignupComponent extends WFMComponent {
	constructor(config) {
		super(config)
	}

	events() {
		return {
			"click .btn": "onClick"
		}
	}

	onClick({ target }) {
		
		let valid = true 
		let firstname = this.el.querySelector('#firstname').value;
		let lastname = this.el.querySelector('#lastname').value;
		let email = this.el.querySelector('#email').value;
		let password = this.el.querySelector('#psw').value;
		
		if (document.getElementById('checkBox').checked) {
			document.getElementById("notificationCheckBox").classList.add("hidenP")
		} else {
			valid = false 
			document.getElementById("notificationCheckBox").classList.remove("hidenP")
		}
		
		if (firstname != "") {
			document.getElementById("notificationFirstname").classList.add("hidenP")

		} else {
			valid = false
			document.getElementById("notificationFirstname").classList.remove("hidenP")
		}
		
		if (lastname != "") {
			document.getElementById("notificationLastName").classList.add("hidenP")

		} else {
			valid = false
			document.getElementById("notificationLastName").classList.remove("hidenP")
		}
		
		if (this.validateEmail(email)) {
			document.getElementById("notificationEmail").classList.add("hidenP")

		} else {
			valid = false
			document.getElementById("notificationEmail").classList.remove("hidenP")
		}
		
		if (password.length  > 5) {
			document.getElementById("notificationPassword").classList.add("hidenP")

		} else {
			valid = false
			document.getElementById("notificationPassword").classList.remove("hidenP")
		}
		
		if (valid) {
			localStorage.setItem('firstname', firstname);
			localStorage.setItem('lastname', lastname);
			router.navigate('/home')
		}
	}
}

export const signupComponent = new SignupComponent({
	selector: "app-signup-page",
	template: `
		<div id = "signupPage">
			
			  <div class = "my-form">
				<p>{{PleaseFill}}</p>
				<hr />
				<label for="firstname"><b>{{EnterFirstname}}</b></label>
				<input class = "my-input" type="text" placeholder="{{EnterFirstname}}" name="firstname" id="firstname" required />
				    <p class = "notification hidenP" id="notificationFirstname">{{invFirstName}}</p>
				<label for="lastname"><b>{{EnterLastName}}</b></label>
				<input class = "my-input" type="text" placeholder="{{EnterLastName}}" name="lastname" id="lastname" required />
					<p class = "notification hidenP" id="notificationLastName">{{invLastName}}</p>
				<label for="email"><b>{{EnterEmail}}</b></label>
				<input class = "my-input" type="text" placeholder="{{EnterEmail}}" name="email" id="email" required />
					<p class = "notification hidenP" id="notificationEmail">{{invEmail}}</p>
				<label for="psw"><b>{{EnterPassword}}</b></label>
				<input class = "my-input" type="password" placeholder="{{EnterPassword}}" name="psw" id="psw" required />
					<p class = "notification hidenP" id="notificationPassword">{{invPsw}}</p>
				<p>
				  <label>
					<input id = "checkBox" type="checkbox" class="filled-in" />
					<span>
					  {{Iagreeto}}
					  <a href="#">{{PrivacyPolicy}}</a>.
					  &
					  <a href="#">{{TermsofUse}}</a>.
					</span>
				  </label>
					<p class = "notification hidenP" id="notificationCheckBox">{{invCheckBox}}</p>
				</p>
				<button type="submit" class="waves-effect waves-light btn">{{GetStarted}}</button>
			  </div>
			
		</div>
	`,
});