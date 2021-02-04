<template>
  <div class="home container">
    <HomeHeader />
    <HomeBody />
  </div>
</template>

<script>
// @ is an alias to /src
import HomeHeader from "@/components/home/Home.header.vue";
import HomeBody from "@/components/home/Home.body.vue";

export default {
  name: "Home",
  async created() {
    await this.$store.dispatch("getUserActions");
    await this.$store.dispatch("getUserData");
    await this.$store.dispatch("getUserCurrencies");
    this.$store.commit("setToday");
  },
  components: {
    HomeHeader,
    HomeBody,
  },
};
</script>
