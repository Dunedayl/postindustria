import { WFMComponent } from '../../framework/index'
import { router } from '../../framework/tools/router'

class HomeComponent extends WFMComponent {

	constructor(config) {
		super(config)
	}


	afterInit() {
		document.querySelectorAll('.coltohide').forEach(e => e.classList.add('hidenDiv'))
	}

}
export const homeComponent = new HomeComponent({
	selector: "app-home-page",
	template: `
    <h1 class = "hclass">Hello, {{ username }} !</h1>`,
});