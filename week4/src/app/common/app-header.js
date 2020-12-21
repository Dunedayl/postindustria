import { router, WFMComponent } from '../../framework/index';
import { appComponent } from '../app.component';
import { loginComponent } from '../pages/login-page.componnent';
import { signupComponent } from '../pages/signup-page.component';
import { appselecter } from './app-selecter';

class AppHeader extends WFMComponent {
    constructor(config) {
        super(config)
    }
    
    events() {
        return {
            'click .selecterLang': 'onSelectLang'
        }
    }
    
    onSelectLang() {
        let lang = document.getElementById("selector").value
        if (localStorage.getItem('language') == lang) {
        } else {
            appselecter.render()
            if (document.getElementById("loginPage")) 
            {
                loginComponent.render()
            }
            if (document.getElementById("signupPage")) {
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
                <option value="eng" selected>English</option>
                <option value="ua">Українська</option>
            </select>
        </div>
`
}) 