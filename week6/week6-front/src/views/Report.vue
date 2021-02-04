<template>
  <div class="report container-fluid">
    <ReportHeader />
    <ReportBody />
  </div>
</template>


<script>
// @ is an alias to /src
import ReportHeader from "@/components/report/Report.header.vue";
import ReportBody from "@/components/report/Report.body.vue";

export default {
  name: "ReportPage",
  async beforeCreate() {
    await this.$store.dispatch("getUserData");
  },
  components: {
    ReportHeader,
    ReportBody,
  },
};
</script>