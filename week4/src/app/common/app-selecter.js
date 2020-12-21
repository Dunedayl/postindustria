import { router, WFMComponent } from '../../framework/index';
import { appComponent } from '../app.component';
import { loginComponent } from '../pages/login-page.componnent';
import { signupComponent } from '../pages/signup-page.component';

class AppSelecter extends WFMComponent {
    constructor(config) {
        super(config)
    }

    events() {
        return {
            'click .logIn': 'onNavLog',
            'click .signUp': 'onNavSign',
        }
    }

    onNavLog(event) {
        this.el.querySelectorAll('.tablinks').forEach(e => e.classList.remove('active'))
        event.target.classList.add('active')
        event.preventDefault()
        router.navigate('/auth/login')
    }

    onNavSign(event) {
        this.el.querySelectorAll('.tablinks').forEach(e => e.classList.remove('active'))
        event.target.classList.add('active')
        event.preventDefault()
        router.navigate('/auth/register')
    }
}

export const appselecter = new AppSelecter({
    selector: 'app-selecter',
    template: `
    <div>
        <div class="tab coltohide">
        <button id = "LogInpId" class="logIn tablinks active">{{logIn}}</button>
        <button id = "signUpId" class="signUp tablinks">{{signUp}}</button>
        </div>
    </div>
`
}) 