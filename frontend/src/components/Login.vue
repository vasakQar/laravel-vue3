<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title text-center">Login</h1>
            <form @submit.prevent="handleLogin" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" v-model="email" id="email" class="form-control" :class="{ 'is-invalid': emailError }" required />
                <div class="invalid-feedback" v-if="emailError">Please enter a valid email.</div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" v-model="password" id="password" class="form-control" :class="{ 'is-invalid': passwordError }" required />
                <div class="invalid-feedback" v-if="passwordError">Password is required.</div>
              </div>
              <div v-if="loginError" class="alert alert-danger">{{ loginError }}</div>
              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
              <a @click="goToRegister" class="text-decoration-none">Register</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../axios';

export default {
  name: 'Login',
  setup() {
    const email = ref('');
    const password = ref('');
    const emailError = ref(false);
    const passwordError = ref(false);
    const loginError = ref('');
    const router = useRouter();

    const handleLogin = async () => {
      emailError.value = !email.value;
      passwordError.value = !password.value;
      loginError.value = '';

      if (!emailError.value && !passwordError.value) {
        try {
          const response = await api.post('/login', {
            email: email.value,
            password: password.value,
          });

          localStorage.setItem("auth_token", response.data.token);
          localStorage.setItem("user_id", response.data.user.id);
          await router.push('/home');
        } catch (error) {
          if (error.response && error.response.data && error.response.data.message) {
            loginError.value = error.response.data.message;
          } else {
            loginError.value = 'An error occurred while logging in. Please try again.';
          }
        }
      }
    };

    onMounted(() => {
      const token = localStorage.getItem("auth_token");
      if (token) {
        router.push('/home');
      }
    });

    return {
      email,
      password,
      emailError,
      passwordError,
      loginError,
      handleLogin,
    };
  },
  methods: {
    goToRegister() {
      this.$router.push('/register');
    }
  }
};
</script>

<style scoped>
.container {
  max-width: 600px;
}

.card {
  padding: 20px;
}
</style>
