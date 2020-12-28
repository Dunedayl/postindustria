import { BaseModule } from '../framework/index'
import { appComponent } from './app.component'
import { appRoutes } from './app.routes'
import { appHeader } from './common/app-header'
import { appselecter } from './common/app-selecter'
import { loginComponent } from './pages/login-page.componnent'
import { signupComponent } from './pages/signup-page.component'


class AppBaseModule extends BaseModule {
    constructor(config) {
        super(config)
    }
}

export const appBaseModule = new AppBaseModule({
    components: [
        appHeader,
        appselecter
    ],
    bootstrap: appComponent,
    routes: appRoutes
})