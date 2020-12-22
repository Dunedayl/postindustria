import { BaseComponent } from '../framework/index';
import { router } from '../framework/tools/router'
import { loginComponent } from './pages/login-page.componnent';
import { signupComponent } from './pages/signup-page.component';


class AppComponent extends BaseComponent {
	constructor(config) {
		super(config)
	}

	events() {
		return []
	}
}

export const appComponent = new AppComponent({
	selector: 'app-root',
	template: `

        <div class="row">

            <div class="col s12">

                <div class="col s6 coltohide">
                    <img class = "coltohide" style="float: right;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Italy-0729_-_St._Peter%27s_Dome_%285115642081%29.jpg/410px-Italy-0729_-_St._Peter%27s_Dome_%285115642081%29.jpg" />
                </div>

				<div class="col s6">

					<div class="col s6">
						<app-header class = "coltohide" ></app-header>
						<app-selecter class = "coltohide" ></app-selecter>
						<router-outlet></router-outlet>
					</div>

				</div>

			</div>
        </div>
`
})