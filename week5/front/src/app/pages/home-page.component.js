import { BaseComponent } from '../../framework/index'

class HomeComponent extends BaseComponent {

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
    <h1 class = "hclass">Hello, {% username %} !</h1>`,
});