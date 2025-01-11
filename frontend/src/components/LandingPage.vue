<template>
  <div class="landing-page">
    <h1>Welcome to Our App</h1>
    <button @click="goToRegister">Register</button>
    <button @click="goToLogin">Login</button>
  </div>
</template>

<script>
export default {
  name: 'LandingPage',
  methods: {
    goToRegister() {
      this.$router.push('/register');
    },
    goToLogin() {
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>
.landing-page {
  text-align: center;
  margin-top: 50px;
}

button {
  padding: 10px;
  margin: 10px;
  font-size: 16px;
  cursor: pointer;
}
</style>
