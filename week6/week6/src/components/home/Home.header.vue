<template>
  <div id="nav" class="row">
    <div class="col-3">
      <div class="text-start">
        Hello {{ $store.state.userData.firstname }}
        {{ $store.state.userData.lastname }} !
      </div>
    </div>

    <div class="col-6"></div>

    <div class="col-3">
      <router-link to="/profile">Profile</router-link> |
      <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#reportModal"
      >
        Report
      </button>
    </div>

    <div class="col">
      <img
        :src="$store.state.userData.image"
        alt="Avatar"
        class="avatar align-right"
      />
    </div>

    <div class="modal modal-centered" id="reportModal" tabindex="-1">
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
            <form id = "headerForm">
            <div class="row">
              <label for="start">Start date:</label>
              <input
                type="date"
                id="from"
                name="trip-start"
                v-model="$store.state.from"
                required
              />
            </div>
            <div class="row">
              <label for="start">End date:</label>

              <input
                type="date"
                id="to"
                name="trip-start"
                v-model="$store.state.to"
                required
              />
            </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              id="close"
            >
              Close
            </button>
            <button
              type="button"
              data-bs-dismiss="modal"
              class="btn btn-primary"
              form="headerForm"
              @click="makeReport"
            >
              Make
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>




<script>
export default {
  name: "HomeHeader",
  beforeCreate() {
    this.$store.dispatch("getUserActions");
  },
  methods: {
    async makeReport() {
      document.getElementById("close").click;
      await this.$store.dispatch("makeReport");
      this.$router.push("/report");
    },
  },
  data() {
    return {};
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