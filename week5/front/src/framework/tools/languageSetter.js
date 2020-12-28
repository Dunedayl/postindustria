import { locale } from "../../app/helper/language"
import { default_locale } from "../../config"

let localization = locale[default_locale]

class LanguageSetter {
	
	getlanguage() {

		let storedItem = localStorage.getItem('language')

		if (storedItem != null) {
			localization = locale[storedItem]
		}

		return localization
	}
} 

export const languageSetter = new LanguageSetter()
