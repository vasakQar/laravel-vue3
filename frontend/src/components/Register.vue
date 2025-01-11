<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title text-center">Register</h1>
            <form @submit.prevent="handleRegister" novalidate>
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" v-model="name" id="name" class="form-control" :class="{ 'is-invalid': nameError }" required />
                <div class="invalid-feedback" v-if="nameError">Name is required.</div>
              </div>
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
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" v-model="confirmPassword" id="confirmPassword" class="form-control" :class="{ 'is-invalid': confirmPasswordError }" required />
                <div class="invalid-feedback" v-if="confirmPasswordError">Passwords do not match.</div>
              </div>
              <div v-if="registerError" class="alert alert-danger">{{ registerError }}</div>
              <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
              <a @click="goToLogin" class="text-decoration-none">Login</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../axios';

export default {
  name: 'Register',
  setup() {
    const name = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const nameError = ref(false);
    const emailError = ref(false);
    const passwordError = ref(false);
    const confirmPasswordError = ref(false);
    const registerError = ref('');
    const router = useRouter();

    const handleRegister = async () => {
      nameError.value = !name.value;
      emailError.value = !email.value;
      passwordError.value = !password.value;
      confirmPasswordError.value = password.value !== confirmPassword.value;
      registerError.value = '';

      if (!nameError.value && !emailError.value && !passwordError.value && !confirmPasswordError.value) {
        try {
          await api.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: confirmPassword.value,
          });
          await router.push('/login');
        } catch (error) {
          if (error.response && error.response.data && error.response.data.message) {
            registerError.value = error.response.data.message;
          } else {
            registerError.value = 'An error occurred while registering. Please try again.';
          }
        }
      }
    };

    return {
      name,
      email,
      password,
      confirmPassword,
      nameError,
      emailError,
      passwordError,
      confirmPasswordError,
      registerError,
      handleRegister,
    };
  },
  methods: {
    goToLogin() {
      this.$router.push('/login');
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
