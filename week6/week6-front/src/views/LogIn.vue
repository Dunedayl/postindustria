<template>
  <div class="auth container">
    <div class="row">
      <div class="col"></div>
      <div class="col center">
        <h4>Please Log In</h4>
        <form @submit.prevent="logIn">
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input
              type="email"
              id="form1Example1"
              class="form-control"
              v-model="email"
            />
            <label class="form-label" for="form1Example1">Email address</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
            <input
              type="password"
              id="form1Example2"
              class="form-control"
              v-model="password"
            />
            <label class="form-label" for="form1Example2">Password</label>
          </div>

          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col">
              <!-- Simple link -->
              <router-link to="/signup">Sign Up</router-link>
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
  name: "LogIn",
  beforeCreate() {
  },
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async logIn() {
      axios
        .post("api/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          localStorage.setItem("token", response.data.token);
          this.$store.state.isLogged = 1;
          this.$router.push("/home");
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
