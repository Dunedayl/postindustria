import { BaseComponent } from '../../framework/index'
import { router } from '../../framework/tools/router'
import { validateSignUpForm } from '../helper/validators'

class SignupComponent extends BaseComponent {
	constructor(config) {
		super(config)
	}

	events() {
		return [
			{
				type: 'click',
				selector: '.singUpButton',
				handler: (e) => this.onClick(e)
			}
		]
	}

	afterInit() {
		document.querySelectorAll('.tablinks').forEach(e => e.classList.remove('active'))
		document.getElementById("signUpId").classList.add('active')
		
		// Unhide hiden item's
		document.querySelectorAll('.coltohide').forEach(e => e.classList.remove('hidenDiv'))
	}

	onClick(event) {

		event.preventDefault()

		let firstname = this.element.querySelector('#firstname').value;
		let lastname = this.element.querySelector('#lastname').value;
		let email = this.element.querySelector('#email').value;
		let password = this.element.querySelector('#psw').value;

		let valid = validateSignUpForm(firstname, lastname, email, password)

		if (valid) {
			localStorage.setItem('username', firstname + " " + lastname)
			router.navigate('/home')
		}
	}
}

export const signupComponent = new SignupComponent({
	selector: "app-signup-page",
	template: `
	<form  class = "mySignUpForm">
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
				<input class = "my-input" type="email" placeholder="{{EnterEmail}}" name="email" id="email" required />
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
				<button type="submit" class="waves-effect waves-light btn singUpButton">{{GetStarted}}</button>
			  </div>
		</div>
	</form>
	`,
});