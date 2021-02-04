<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <h3>Actions:</h3>
      </div>
      <div class="col-9"></div>
      <div class="col">
        <button
          type="button"
          class="btn btn-primary"
          data-bs-toggle="modal"
          data-bs-target="#modal"
        >
          Add Action
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col" width="5%">#</th>
              <th scope="col" width="15%">Action</th>
              <th scope="col" width="5%">Sum</th>
              <th scope="col" width="5%">Currency</th>
              <th scope="col" width="40%">Description</th>
              <th scope="col" width="5%">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(action, index) in $store.state.userActions"
              :key="index"
            >
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ action.action }}</td>
              <td>{{ action.sum }}</td>
              <td>{{ action.currency }}</td>
              <td>{{ action.info }}</td>
              <td>{{ action.date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="modal modal-centered" id="modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Make Report</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-4">
                <select
                  class="form-select"
                  aria-label="Default select example"
                  v-model="$store.state.homeSelector"
                  @change="
                    $store.commit(
                      'getExchangeRate',
                      this.$store.state.exchangeSelectedCurrency
                    )
                  "
                >
                  <option value="" disabled selected>Select</option>
                  <option value="1">Income</option>
                  <option value="2">Exchange</option>
                  <option value="3">Force Exchange</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <form
                  name="income"
                  v-if="$store.state.homeSelector == 1"
                  id="income"
                  @submit.prevent="this.$store.dispatch('setIncome'), close"
                >
                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label">Sum</label>
                    <div class="col-sm">
                      <input
                        type="number"
                        id="Sum"
                        v-model="$store.state.incomeSum"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label"
                      >Currency</label
                    >
                    <div class="col-sm">
                      <select
                        required
                        class="custom-select my-1 mr-sm-2"
                        id="currency"
                        v-model="this.$store.state.selected"
                        @change="
                          $store.commit(
                            'getExchangeRate',
                            this.$store.state.selected.currency
                          )
                        "
                      >
                        <option
                          v-for="(currency, index) in this.$store.state
                            .avalibleCurrencies"
                          :key="index"
                          :value="currency"
                        >
                          {{ currency.currency }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="date" class="col-sm col-form-label"
                      >Income Date</label
                    >
                    <div class="col-sm">
                      <input
                        type="date"
                        id="date"
                        v-model="this.$store.state.incomeDate"
                        :max="this.$store.state.maxDate"
                        required
                      />
                    </div>
                  </div>
                  <div
                    v-if="
                      this.$store.state.selected != 'UAH' && ifCurrency() == 1
                    "
                    class="form-group row"
                  >
                    <label for="Sum" class="col-sm col-form-label"
                      >Force {{ this.$store.state.selected.currency }} Exchange
                      rate</label
                    >
                    <div class="col-sm">
                      <input
                        type="number"
                        id="rate"
                        disabled
                        v-model="this.$store.state.exchangeRate"
                        required
                      />
                    </div>
                  </div>
                  <div
                    v-if="
                      this.$store.state.selected != 'UAH' && ifCurrency() == 1
                    "
                    class="form-group row"
                  >
                    <label for="date" class="col-sm col-form-label"
                      >Force exchange Date</label
                    >
                    <div class="col-sm">
                      <input
                        type="date"
                        id="ForceExchangeIncomeDate"
                        v-model="this.$store.state.exdate"
                        @change="
                          $store.commit(
                            'getExchangeRate',
                            this.$store.state.selected.currency
                          )
                        "
                        :max="this.$store.state.maxDate"
                        required
                      />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                      id="close"
                      form="income"
                    >
                      Close
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary"
                      v-on:click="this.close"
                    >
                      Make
                    </button>
                  </div>
                  <label for="Exchangedate" class="col-sm col-form-label">{{
                    this.$store.state.incomeLabel
                  }}</label>
                </form>

                <form
                  name="exchange"
                  v-if="$store.state.homeSelector == 2"
                  @submit.prevent="this.$store.dispatch('makeExchange')"
                >
                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label">Sum</label>
                    <div class="col-sm">
                      <input
                        type="number"
                        id="Sum"
                        v-model="$store.state.exchangeSum"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label"
                      >Currency</label
                    >

                    <div class="col-sm">
                      <select
                        required
                        class="custom-select my-1 mr-sm-2"
                        id="currency"
                        v-model="this.$store.state.exchangeSelectedCurrency"
                      >
                        <option
                          v-for="(currency, index) in this.$store.state.userData
                            .currencies"
                          :key="index"
                          :value="currency.currency"
                        >
                          {{ currency.currency }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label"
                      >{{ this.$store.state.exchangeSelectedCurrency }} Exchange
                      rate</label
                    >
                    <div class="col-sm">
                      <input
                        type="number"
                        id="exchangeRate"
                        disabled
                        v-model="this.$store.state.exchangeRate"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Exchangedate" class="col-sm col-form-label"
                      >{{ this.$store.state.exchangeSelectedCurrency }} Exchange
                      date</label
                    >
                    <div class="col-sm">
                      <input
                        type="date"
                        id="exchangeDate"
                        v-model="this.$store.state.exdate"
                        @change="
                          $store.commit(
                            'getExchangeRate',
                            this.$store.state.exchangeSelectedCurrency
                          )
                        "
                        :max="this.$store.state.maxDate"
                        required
                      />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                      id="closeMainModal"
                      form="income"
                    >
                      Close
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary"
                      v-on:click="this.close"
                    >
                      Make
                    </button>
                  </div>
                  <label for="Exchangedate" class="col-sm col-form-label">{{
                    this.$store.state.exchangeLabel
                  }}</label>
                </form>

                <form
                  name="exchange"
                  v-if="$store.state.homeSelector == 3"
                  @submit.prevent="this.$store.dispatch('makeForceExchange')"
                >
                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label">Sum</label>
                    <div class="col-sm">
                      <input
                        type="number"
                        id="Sum"
                        v-model="$store.state.exchangeSum"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label"
                      >Currency</label
                    >

                    <div class="col-sm">
                      <select
                        required
                        class="custom-select my-1 mr-sm-2"
                        id="currency"
                        v-model="this.$store.state.exchangeSelectedCurrency"
                      >
                        <option
                          v-for="(currency, index) in this.$store.state.userData
                            .currencies"
                          :key="index"
                          :value="currency.currency"
                        >
                          {{ currency.currency }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="Sum" class="col-sm col-form-label"
                      >{{ this.$store.state.exchangeSelectedCurrency }} Force
                      exchange rate</label
                    >
                    <div class="col-sm">
                      <input
                        type="number"
                        id="exchangeRate"
                        disabled
                        v-model="this.$store.state.exchangeRate"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Exchangedate" class="col-sm col-form-label"
                      >{{ this.$store.state.exchangeSelectedCurrency }} Force
                      exchange date</label
                    >
                    <div class="col-sm">
                      <input
                        type="date"
                        id="exchangeDate"
                        v-model="this.$store.state.incomeDate"
                        @change="
                          $store.commit(
                            'getExchangeRate',
                            this.$store.state.exchangeSelectedCurrency
                          )
                        "
                        :max="this.$store.state.maxDate"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="date" class="col-sm col-form-label"
                      >Date of income to force exchange</label
                    >
                    <div class="col-sm">
                      <input
                        type="date"
                        id="dateForceExchange"
                        v-model="this.$store.state.exdate"
                        :max="this.$store.state.maxDate"
                        required
                      />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                      id="close"
                      form="income"
                    >
                      Close
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary"
                      v-on:click="this.close"
                    >
                      Make
                    </button>
                  </div>
                  <label for="Exchangedate" class="col-sm col-form-label">
                    {{ this.$store.state.exchangeLabel }}</label
                  >
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal modal-centered" id="notificationModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Operations Performed</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <ul>
              <li v-for="(user, index) in this.$store.state.label" :key="index">
                {{ user }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HomeBody",
  beforeCreate() {},
  components: {},
  methods: {
    close() {},
    ifCurrency() {
      try {
        return this.$store.state.userData.currencies.find(
          (x) => x.currency == this.$store.state.selected.currency
        ).force_exchange;
      } catch {
        return 0;
      }
    },
  },
  data() {
    return {
      hiden: true,
      userData: 1,
    };
  },
};
</script>
