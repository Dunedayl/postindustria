import { WFMComponent } from '../../framework/index'
import { router } from '../../framework/tools/router'

class LoginComponent extends WFMComponent {
	constructor(config) {
        super(config)
	}

	events() {
		return {
            'click .loginbtn': 'onClickLogin',
            'click .sendbtn': 'onClickSend',
            'click .close': 'spanClick',
            'click .close2': 'spanClick',
            'click .close3': 'spanClick',
            'click .forgotPass': 'forgotClick',
            'click .sendEmail': 'sendEmailClick',
		}
    }

    onClickLogin() {

        let firstname = this.el.querySelector('#email').value;
        let lastname = "";
        let password = this.el.querySelector('#psw').value;
        
        if (firstname == "admin" && password == "123456") {
            localStorage.setItem('firstname', firstname);
            localStorage.setItem('lastname', lastname);
            router.navigate('/home')
        } else {
            document.getElementById("notificationLogin").classList.remove("hidenP")
        }
    }
    
    onClickSend() {
        let number = this.el.querySelector('#phone').value;
        let phone = /^\d{10}$/;
        if ((number.match(phone))) {
            document.getElementById("notificationSendCode").classList.add("hidenP")
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        } else {
            document.getElementById("notificationSendCode").classList.remove("hidenP")
        }
    }
    
    spanClick() {
        let modal = document.getElementById("email-modal");
        modal2.style.display = "none";
        
        modal = document.getElementById("myModal");
        modal.style.display = "none";
        
        modal = document.getElementById("succesEmail");
        modal.style.display = "none";
    }
    
    forgotClick() {
        var modal = document.getElementById("email-modal");
        modal.style.display = "block";
    }
    
    sendEmailClick() {
        let email = this.el.querySelector('#emailVerification').value;
        if (this.validateEmail(email)) {
            this.spanClick()
            var modal = document.getElementById("succesEmail");
                modal.style.display = "block";
        } else {
            let modal = document.getElementById("modalEmail")
            modal.classList.remove("hidenP")
        }
    }
}

export const loginComponent = new LoginComponent({
	selector: "app-login-page",
    template: ` 
        <h4 class= "coltohide">{{ TestPage }}</h4>
    <div id = "loginPage">
            <p>{{EnterYourCredentials}}</p>

            <hr>

            <label for="email"><b>{{email}}</b></label>

            <input type="text" placeholder="{{email}}" name="email" id="email" required />

            <label for="psw"><b>{{EnterPassword}}</b></label>

            <input type="password" placeholder="{{EnterPassword}}" name="psw" id="psw" required/> 
              <label style='float: right;'>
                    <a class='pink-text forgotPass'><b>{{ ForgotPasword  }}</b></a>
			    </label>

            <p class = "notification hidenP" id="notificationLogin">{{Invalidlogin}}</p>
            <button type="submit" class="waves-effect waves-light btn loginbtn">{{LogIn}}</button>

            <h4>-{{OR}}-</h4>

            <label for="phone"><b>{{PhoneNumber}}</b></label>
            <input type = "number" pattern = "pattern="\d{3}[\-]\d{3}[\-]\d{4}" placeholder={{PhoneNumber}} name="phone" id="phone" required />

            <p class = "notification hidenP" id="notificationSendCode">{{invalidPhone}}</p>
            <button type="submit" class="waves-effect waves-light btn sendbtn">{{Sendverificationcode}}</button>

            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close2">&times;</span>
                    <p>{{ ModalPhone }}</p>
                </div>
            </div>

            <div id="succesEmail" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close3">&times;</span>
                    <p>{{ ModalEmail }}</p>
                </div>
            </div>

            <div id="email-modal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <label for="email"><b>{{ PlzEndterEmail }}</b></label>
                    <input type="text" placeholder="Enter Email" name="email" id="emailVerification" required />
                    <p id = "modalEmail" class = "hidenP notification">{{ sendForgotEmail }}</p>
                    <button type="submit" class="waves-effect waves-light btn sendEmail">{{sendEmai}}</button>
                </div>
            </div>

    </div>
    `,
});