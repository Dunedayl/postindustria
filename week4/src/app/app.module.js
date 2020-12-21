import { WFMModule } from '../framework/index'
import { appComponent } from './app.component'
import { appRoutes } from './app.routes'
import { appHeader } from './common/app-header'
import { appselecter } from './common/app-selecter'
import { loginComponent } from './pages/login-page.componnent'
import { signupComponent } from './pages/signup-page.component'


class AppModule extends WFMModule {
    constructor(config) {
        super(config)
    }
}

export const appModule = new AppModule({
    components: [
        appHeader,
        appselecter
    ],
    bootstrap: appComponent,
    routes: appRoutes
})