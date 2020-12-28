import { loginComponent } from './pages/login-page.componnent'
import { signupComponent } from './pages/signup-page.component'
import { homeComponent } from './pages/home-page.component'


export const appRoutes = [
    { path: '', component: loginComponent },
    { path: '/auth/register', component: signupComponent },
    { path: '/auth/login', component: loginComponent },
    { path: '/home', component: homeComponent }
]