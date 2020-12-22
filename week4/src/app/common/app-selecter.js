import { router, BaseComponent } from '../../framework/index';
import { appComponent } from '../app.component';
import { loginComponent } from '../pages/login-page.componnent';
import { signupComponent } from '../pages/signup-page.component';

class AppSelecter extends BaseComponent {
    constructor(config) {
        super(config)
    }

    events() {
        return [
            {
                type: 'click',
                selector: '.logIn',
                handler: (e) => this.onNavLog(e)
            },
            {
                type: 'click',
                selector: '.signUp',
                handler: (e) => this.onNavSign(e)
            }
        ]
    }

    onNavLog(event) {
        event.preventDefault()
        router.navigate('/auth/login')
    }

    onNavSign(event) {
        event.preventDefault()
        router.navigate('/auth/register')
    }
}

export const appselecter = new AppSelecter({
    selector: 'app-selecter',
    template: `
    <div>
        <div class="tab coltohide">
        <button id = "LogInId" class="logIn tablinks active">{{logIn}}</button>
        <button id = "signUpId" class="signUp tablinks">{{signUp}}</button>
        </div>
    </div>
`
}) 