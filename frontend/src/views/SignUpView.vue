<script>
import { mapActions } from 'pinia'
import { useUserStore } from '../stores/user'
import '@/assets/fontawesome.min.js'

export default {
  name: 'SignUpView',
  data() {
    return {
      firstName: '',
      surName: '',
      email: '',
      password: '',
      signupError: '',
      submitting: false
    }
  },
  methods: {
    ...mapActions(useUserStore, ['signup']),

    async doSignUp() {
      this.signupError = ''
      this.submitting = true

      try {
        await this.signup(this.firstName, this.surName, this.email, this.password)
        this.$router.push('/login')
      } catch (error) {
        this.signupError = error?.response?.data?.message || 'Sign up fehlgeschlagen. Bitte pruefe deine Eingaben.'
      } finally {
        this.submitting = false
      }
    }
  }
}
import '../assets/login.css' // Import login.css only for this component
</script>

<template>
  <div class="logincontainer">
    <div class="d-flex justify-content-center h-100">
      <div class="card" style="height: 428px !important;">
        <div class="card-header">
          <h3>Sign up</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="doSignUp">
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-user"></i>
              </span>
              <input id="firstName" v-model="firstName" class="form-control" type="text" placeholder="firstname" required autofocus>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-user"></i>
              </span>
              <input id="surName" v-model="surName" class="form-control" type="text" placeholder="surname" required>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-envelope"></i>
              </span>
              <input id="email" v-model="email" class="form-control" type="email" placeholder="email" required>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-key"></i>
              </span>
              <input id="password" v-model="password" class="form-control" type="password" placeholder="password" required minlength="6">
            </div>

            <div v-if="signupError" class="text-danger mb-2">{{ signupError }}</div>

            <div class="mb-3">
              <input class="btn float-end login_btn" type="submit" value="Sign up" :disabled="submitting">
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-center links" style="padding-bottom: 12px;">
            <span>Already have an account?</span>
            <RouterLink to="/login">Login</RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
@layer scoped {
  .card {
    height: 422px !important;
  }

  @media (min-width: 1400px) {
    .container-xxl,
    .container-xl,
    .container-lg,
    .container-md,
    .container-sm,
    .container {
      max-width: 1320px !important;
    }
  }
  .container {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    width: 100%;
    padding-right: calc(var(--bs-gutter-x) * 0.5);
    padding-left: calc(var(--bs-gutter-x) * 0.5);
    margin-right: auto;
    margin-left: auto;
  }
}
</style>
