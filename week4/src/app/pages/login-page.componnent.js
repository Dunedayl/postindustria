import { BaseComponent } from '../../framework/index'
import { router } from '../../framework/tools/router'
import { validateLogInForm, validateEmail, validatePhone } from '../helper/validators'

class LoginComponent extends BaseComponent {
    constructor(config) {
        super(config)
    }

    events() {
        return [
            {
                type: 'click',
                selector: '.loginbtn',
                handler: (e) => this.onClickLogin(e)
            },
            {
                type: 'click',
                selector: '.sendbtn',
                handler: (e) => this.onClickSend(e)
            },
            {
                type: 'click',
                selector: '.close',
                handler: (e) => this.spanClick(e)
            },
            {
                type: 'click',
                selector: '.forgotPass',
                handler: (e) => this.forgotPassClick(e)
            },
            {
                type: 'click',
                selector: '.sendEmail',
                handler: (e) => this.sendEmailClick(e)
            }
        ]
    }

    afterInit() {
        document.querySelectorAll('.tablinks').forEach(e => e.classList.remove('active'))
        document.getElementById("LogInId").classList.add('active')
        // Unhide hiden item's
        document.querySelectorAll('.coltohide').forEach(e => e.classList.remove('hidenDiv'))
    }

    onClickLogin() {

        let email = this.element.querySelector('#email').value;
        let password = this.element.querySelector('#psw').value;
        
        let valid = validateLogInForm(email, password)

        if (valid) {
            router.navigate('/home')
        }
    }

    onClickSend(event) {

        let number = this.element.querySelector('#phone').value;

        let valid = validatePhone(number)

        if (valid) {
            let modal = document.getElementById("myModal");
            modal.style.display = "block";
        }
    }

    spanClick(event) {
        document.querySelectorAll('.modal').forEach(e => e.style.display = "none");
    }

    forgotPassClick(event) {
        let modal = document.getElementById("email-modal");
        modal.style.display = "block";
    }

    sendEmailClick(event) {
        let email = this.element.querySelector('#emailVerification').value;

        if (validateEmail(email)) {
            this.spanClick()
            let modal = document.getElementById("succesEmail");
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
                    <span class="close">&times;</span>
                    <p>{{ ModalPhone }}</p>
                </div>
            </div>

            <div id="succesEmail" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
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