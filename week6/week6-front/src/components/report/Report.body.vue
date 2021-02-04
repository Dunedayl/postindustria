<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <p>Report from {{ $store.state.from }} to {{ $store.state.to }}</p>

        <h4>Total Income {{ $store.state.report.totalIncome }} UAH</h4>
        <h4>Tax UAH {{ $store.state.report.tax }}</h4>

        <h6>Income in UAH {{ $store.state.report.incomeUah }} UAH</h6>
        <h6>
          Income from force exchange
          {{ $store.state.report.incomeForceExchange }} UAH
        </h6>
        <h6>
          Income from rate difference
          {{ $store.state.report.incomeRateDifference }} UAH
        </h6>

        <div
          v-for="(currency, index) in this.$store.state.report
            .incomeAnotherCurrencies"
          :key="index"
        >
          <h6>Income from {{ index }} {{ currency }} UAH</h6>
        </div>
      </div>

      <div class="col"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ReportBody",
};
</script>
