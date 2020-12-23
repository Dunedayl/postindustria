import { locale } from "../../app/helper/language";
import { default_locale } from "../../config";
import { languageSetter } from "../tools/languageSetter";


export class BaseComponent {

	constructor(config) {
		this.template = config.template;
		this.selector = config.selector;
		this.element = null
	}

	render() {

		this.element = document.querySelector(this.selector)
		if (!this.element) throw new Error(`BaseComponent with selector ${this.selector} wasn't found`)

		let localization = languageSetter.getlanguage()

		//Render lokalization and variables in template 
		this.element.innerHTML = this.fillTemplate(this.compileTemplate(this.template, localization))
		this._initEvents()
	}

	//Init on click events in views
	_initEvents() {
		const events = this.events();

		Object.values(events).forEach(key => {
			const elements = document.querySelectorAll(key.selector);
			elements.forEach(element => {
				element.addEventListener(key.type, key.handler.bind(this))
			})
		});
	}

	//Replace vars in template with language
	compileTemplate(template, localization) {

		let regex = /\{{(.*?)}}/g

		template = template.replace(regex, (str, d) => {
			let key = d.trim()
			return localization[key]
		})
		return template
	}

	//Fill template with data from local storage
	fillTemplate(template) {
		let regex = /\{%(.*?)%}/g

		template = template.replace(regex, (str, d) => {
			let key = d.trim()
			let username = localStorage.getItem(key)
			return username
		})
		return template
	}

	onInit() {
		// Events before form render
	}

	afterInit() {
		// Events after form render
	}

	events() {
		// Events of component
		return []
	}
}

