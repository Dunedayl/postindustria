import { eng, ua } from "../../app/helper/language";


export class Component {
	
	constructor(config) {
		this.template = config.template;
		this.selector = config.selector;
		this.el = null
	}

	render() {
		this.el = document.querySelector(this.selector)
		if (!this.el) throw new Error(`Component with selector ${this.selector} wasn't found`)
		
		// Unhide hiden item's
		document.querySelectorAll('.coltohide').forEach(e => e.classList.remove('hidenDiv'))
		
		// Localization
		let lang = "eng"
		let localization = eng;
		if (document.getElementById("selector") != null) {
			lang = document.getElementById("selector").value
			localStorage.setItem('language', lang)
		}
		if (lang === "eng") localization = eng
		if (lang === "ua") localization = ua
		
		//Render variables in template 
		this.el.innerHTML = this.compileTemplate(this.template, localization)
		this._initEvents()
	}


	isUndefined(d) {
		return typeof d === 'undefined'
	}

	_initEvents() {
		if (this.isUndefined(this.events)) return
		let events = this.events()

		Object.keys(events).forEach(key => {
			let listener = key.split(' ')

			this.el
				.querySelector(listener[1])
				.addEventListener(listener[0], this[events[key]].bind(this))
		})
	}

	compileTemplate(template, localization) {

		let regex = /\{{(.*?)}}/g

		template = template.replace(regex, (str, d) => {
			let key = d.trim()
			if (key == "username") {
				let firstname = localStorage.getItem('firstname')
				let lastname = localStorage.getItem('lastname')
				let username = firstname + " " + lastname
				return username
			} 
			return localization[key]
		})
		return template
	}
	

	validateEmail(mail) {
		if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail)) {
			return (true)
		}
		return (false)
	}
}

