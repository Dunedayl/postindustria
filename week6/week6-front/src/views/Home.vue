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
  beforeCreate() {
  },
  created () {

    this.$store.dispatch("getUserData").then(() => {
      this.$store.dispatch("getUserActions");
      this.$store.dispatch("getUserCurrencies");
      this.$store.commit("setToday");
    });
  },
  components: {
    HomeHeader,
    HomeBody,
  },
};
</script>
