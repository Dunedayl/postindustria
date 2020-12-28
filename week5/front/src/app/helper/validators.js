export function validateEmail(mail) {
	if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail)) {
		return (true)
	}
	return (false)
}

export function validateSignUpForm(firstname, lastname, email, password) {

	let valid = true

	if (document.getElementById('checkBox').checked) {
		document.getElementById("notificationCheckBox").classList.add("hidenP")
	} else {
		valid = false
		document.getElementById("notificationCheckBox").classList.remove("hidenP")
	}

	if (firstname != "") {
		document.getElementById("notificationFirstname").classList.add("hidenP")
	} else {
		valid = false
		document.getElementById("notificationFirstname").classList.remove("hidenP")
	}

	if (lastname != "") {
		document.getElementById("notificationLastName").classList.add("hidenP")
	} else {
		valid = false
		document.getElementById("notificationLastName").classList.remove("hidenP")
	}

	if (validateEmail(email)) {
		document.getElementById("notificationEmail").classList.add("hidenP")
	} else {
		valid = false
		document.getElementById("notificationEmail").classList.remove("hidenP")
	}

	if (password.length > 5) {
		document.getElementById("notificationPassword").classList.add("hidenP")
	} else {
		valid = false
		document.getElementById("notificationPassword").classList.remove("hidenP")
	}

	return valid
}

export function validateLogInForm(email, password) {

	if (email == "admin" && password == "123456") {
		localStorage.setItem('username', email)
		return true
	} else {
		document.getElementById("notificationLogin").classList.remove("hidenP")
		return false
	}
}

export function validatePhone(number) {
	let phone = /^\d{10}$/;

	if (number.match(phone)) {
		document.getElementById("notificationSendCode").classList.add("hidenP")
		return true
	} else {
		document.getElementById("notificationSendCode").classList.remove("hidenP")
		return false
	}
}