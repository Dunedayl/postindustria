<template>
  <div class="container">
    <div id="nav" class="row">
      <div class="col-3">
        <div class="text-start">
          Hello {{ $store.state.userData.firstname }}
          {{ $store.state.userData.lastname }} !
        </div>
      </div>

      <div class="col-6"></div>

      <div class="col-2 gap-3">
        <router-link to="/home">Home</router-link>
      </div>
      <div class="col gap-3">
        <img
          :src="$store.state.userData.image"
          alt="Avatar"
          class="avatar align-right"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ReportHeader",
  data() {
    return {
      
    };
  },
};
</script>

<style>
.avatar {
  vertical-align: middle;
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>