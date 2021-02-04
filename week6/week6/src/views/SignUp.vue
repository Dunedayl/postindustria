<template>
  <div class="auth container">
    <div class="row">
      <div class="col"></div>
      <div class="col center">
        <h4>Please Create an accaunt</h4>
        <form @submit.prevent="signUp" p>
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input
              type="email"
              id="email"
              class="form-control"
              v-model="email"
            />
            <label class="form-label" for="email">Email address</label>
          </div>

          <div class="form-outline mb-4">
            <input
              type="text"
              id="firstname"
              class="form-control"
              v-model="firstname"
            />
            <label class="form-label" for="firstname">Firstname</label>
          </div>

          <div class="form-outline mb-4">
            <input
              type="text"
              id="lastname"
              class="form-control"
              v-model="lastname"
            />
            <label class="form-label" for="lastname">Lastname</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
            <input
              type="password"
              id="password"
              class="form-control"
              v-model="password"
            />
            <label class="form-label" for="password">Password</label>
          </div>

          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col">
              <!-- Simple link -->
              <router-link to="/login">Log In</router-link>
            </div>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary btn-block">
            Sign in
          </button>
        </form>
      </div>
      <div class="col"></div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SignUp",
  beforeCreate() {},
  data() {
    return {
      email: "",
      firstname: "",
      lastname: "",
      password: "",
    };
  },
  methods: {
    async signUp() {
      axios
        .post("api/register", {
          email: this.email,
          firstname: this.firstname,
          lastname: this.lastname,
          password: this.password,
        })
        .then((response) => {
          localStorage.setItem("token", response.data.token);
          this.$router.push("/login");
        });
    },
  },
};
</script>

<style>
.center {
  margin-top: 200px;
}
</style>
