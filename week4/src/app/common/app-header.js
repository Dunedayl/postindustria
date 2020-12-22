import { router, BaseComponent } from '../../framework/index';
import { appComponent } from '../app.component';
import { loginComponent } from '../pages/login-page.componnent';
import { signupComponent } from '../pages/signup-page.component';
import { appselecter } from './app-selecter';

class AppHeader extends BaseComponent {
    constructor(config) {
        super(config)
    }

    events() {
        return [
            {
                type: 'click',
                selector: '.selecterLang',
                handler: (e) => this.onSelectLang(e)
            }
        ]
    }

    afterInit() {
        let storedItem = localStorage.getItem('language')
        document.getElementById("selector").value = storedItem
    }

    onSelectLang() {
        let lang = document.getElementById("selector").value
        if (localStorage.getItem('language') == lang) {
        } else {
            localStorage.setItem('language', lang)
            appselecter.render()
            if (document.getElementById("loginPage")) {
                loginComponent.render()
            }
            if (document.getElementById("signupPage")) {
                localStorage.setItem('language', lang)
                signupComponent.render()
                document.querySelectorAll('.tablinks').forEach(e => e.classList.remove('active'))
                document.getElementById("signUpId").classList.add("active")
            }
        }
    }
}

export const appHeader = new AppHeader({
    selector: 'app-header',
    template: `
        <div>
            <select class="browser-default selecterLang" id = "selector">
                <option id = "eng_US" value="eng_US" selected>English</option>
                <option id = "ua_UA" value="ua_UA">Українська</option>
            </select>
        </div>
`
}) 