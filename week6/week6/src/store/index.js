import { createStore } from 'vuex'
import axios from 'axios'

export default createStore({
    state: {
        userData: {
            firstname: "",
            lastname: "",
            taxRate: "",
            notificationPeriod: "month",
            currencies: [],
            image: "https://www.w3schools.com/howto/img_avatar.png"
        },
        updateUserData: "",
        incomeDate: "",
        uploadImage: "",
        default_currency: "",
        isLogged: "false",
        image: "",
        userActions: [],
        from: "",
        to: "",
        report: "",
        tax: "",
        date: "",
        exdate: "",
        maxDate: "",
        first: "",
        last: "",
        exchangeCurrency: "",
        exchangeDate: "",
        exchangeRate: "",
        exchangeSum: "",
        avalibleCurrencies: "",
        selected: "UAH",
        exchangeSelectedCurrency: "USD",
        exchangeIncome: "",
        exchangeRateDifferenceIncome: "",
        incomeSum: "",
        incomeLabel: "",
        exchangeLabel: "",
        receiveImage: "",
        homeSelector: "",
        label: []
    },
    getters: {
    },
    mutations: {
        setToday() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd
            this.state.date = today;
            this.state.from = today;
            this.state.to = today;
            this.state.exdate = today;
            this.state.maxDate = today;
        },
        getExchangeRate(state, currency) {

            if (currency != "UAH") {
                this.state.exchangeCurrency = currency;
                this.state.exchangeDate = this.state.exdate;
                this.state.incomeDate = this.state.exdate;
                this.dispatch("getRate");
            }
        },
    },
    actions: {
        async getUserData() {

            return new Promise((resolve) => {
                axios
                    .get("api/data",)
                    .then((response) => {
                        this.state.userData.firstname = response.data.data.firstname;
                        this.state.userData.lastname = response.data.data.lastname;
                        this.state.userData.taxRate = response.data.data.tax_rate;
                        this.state.userData.notificationPeriod = response.data.data.notification_period;
                        this.state.userData.image = response.data.data.image;
                        this.state.userData.currencies = response.data.data.currencies;
                        this.state.default_currency = response.data.data.default_currency;
                        resolve(response);
                    }).catch((error) => {
                        console.log(error);
                    });
            })

        },
        getRate() {
            axios
                .get("api/rate/" + this.state.exchangeDate + "/" + this.state.exchangeCurrency)
                .then((response) => {
                    this.state.exchangeRate = response.data
                }).catch((error) => {
                    console.log(error);
                });
        },
        getUserCurrencies() {
            axios
                .get("api/currencies/")
                .then((response) => {
                    this.state.avalibleCurrencies = response.data.data
                    this.state.avalibleCurrencies.push({ 'currency': this.state.default_currency })
                }).catch((error) => {
                    console.log(error);
                });
        },
        updateUserData() {
            return new Promise((resolve) => {

                const dataURLtoFile = (dataurl, filename) => {
                    const arr = dataurl.split(',')
                    const mime = arr[0].match(/:(.*?);/)[1]
                    const bstr = atob(arr[1])
                    let n = bstr.length
                    const u8arr = new Uint8Array(n)
                    while (n) {
                        u8arr[n - 1] = bstr.charCodeAt(n - 1)
                        n -= 1 
                    }
                    return new File([u8arr], filename, { type: mime })
                }
                let data = new FormData()

                if (this.state.uploadImage != "") {
                    const file = dataURLtoFile(this.state.uploadImage)
                    data.append('image', file, file.name)
                }
                data.append('tax_rate', this.state.updateUserData.taxRate)
                data.append('firstname', this.state.updateUserData.firstname)
                data.append('lastname', this.state.updateUserData.lastname)
                data.append('notification_period', this.state.updateUserData.notificationPeriod)

                this.state.userData.currencies.forEach( (val, index) => {
                    Object.entries(val).forEach(entry => {
                        const [key, value] = entry;
                        data.append('currencies[' + index + '][' + key + ']', value)
                    });
                });

                axios
                    .post("api/data/update", data)
                    .then((response) => {
                        this.state.userData.firstname = response.data.data.firstname;
                        this.state.userData.lastname = response.data.data.lastname;
                        this.state.userData.taxRate = response.data.data.tax_rate;
                        this.state.userData.notificationPeriod = response.data.data.notification_period;
                        this.state.userData.image = response.data.data.image;
                        this.state.userData.currencies = response.data.data.currencies;
                        this.state.default_currency = response.data.data.default_currency;
                        resolve(response);
                    }).catch((error) => {
                        console.log(error);
                    });
            })
        },
        getUserActions() {
            axios
                .get("api/action/all",)
                .then((response) => {
                    console.log(response.data.data);
                    this.state.userActions = response.data.data;
                }).catch((error) => {
                    console.log(error);
                });
        },
        makeReport() {

            axios
                .get("api/action/" + this.state.from + "/" + this.state.to)
                .then((response) => {
                    this.state.report = response.data
                }).catch(function (error) {
                    console.log(error);
                }).catch((error) => {
                    console.log(error);
                });
        },
        makeExchange() {
            axios
                .post("api/action/exchange", {
                    sum: this.state.exchangeSum,
                    date: this.state.exchangeDate,
                    exchangeDate: this.state.exchangeDate,
                    currency: this.state.exchangeCurrency
                })
                .then((response) => {
                    response.data.data.forEach(element => {
                        this.state.label = []
                        this.state.label.push(element.info)
                    });
                }).catch((error) => {
                    console.log(error.response.data.error);
                    this.state.label = []
                    this.state.label.push(error.response.data.error)
                });
        },
        makeForceExchange() {
            axios
                .post("api/action/forceExchage", {
                    sum: this.state.exchangeSum,
                    date: this.state.incomeDate,
                    exchangeDate: this.state.exchangeDate,
                    currency: this.state.exchangeCurrency
                })
                .then((response) => {
                    this.state.label = []
                    response.data.data.forEach(element => {
                        this.state.label.push(element.info)
                    });
                }).catch((error) => {
                    console.log(error);
                    this.state.label = []
                    this.state.label.push(error.response.data)
                });
        },
        setIncome() {
            axios
                .post("api/action/store", {
                    action: "Income",
                    sum: this.state.incomeSum,
                    date: this.state.date,
                    exchangeDate: this.state.date,
                    currency: this.state.selected.currency
                })
                .then((response) => {
                    this.state.label = []
                    response.data.data.forEach(element => {
                        this.state.label.push(element.info)
                    });
                }).catch((error) => {
                    console.log(error);
                    this.state.label.push(error.response.data)
                });
        },
    },
    modules: {
    }
})
