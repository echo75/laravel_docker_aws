<script>
import axios from 'axios'
import { mapActions } from 'pinia'
import { useAccountStore } from '../stores/account'
import { mapState } from 'pinia'
import '@/assets/fontawesome.min.js'

export default {
  name: 'LoginView',
  data() {
    return {
      email: '',
      password: '',
      loginError: ''  // Fehlermeldevariable hinzugefügt
    }
  },
  computed: {
    ...mapState(useAccountStore, ['user'])
  },
  methods: {
    ...mapActions(useAccountStore, ['login']),

    async doLogin() {
      try {
        // Nur einmal login aufrufen
        await this.login({ email: this.email, password: this.password })
        
        // Nach erfolgreichem Login zur Suchseite weiterleiten
        this.$router.push('/search')
      } catch (error) {
        // Fehlerbehandlung
        this.loginError = 'Login fehlgeschlagen. Bitte überprüfe deine Anmeldedaten.'
        alert(this.loginError)
      }
    }
  }
}
import '../assets/login.css' // Import login.css only for this component
</script>

<template>
  <div class="logincontainer">
    <div class="d-flex justify-content-center h-100">
      <div class="card">
        <div class="card-header">
          <h3>Log in</h3>
          <p v-if="user">You are logged in as {{ user?.firstName }} {{ user?.surName }}</p>
        </div>
        <div class="card-body">
          <form v-if="!user" @submit.prevent="doLogin">
            <div class="input-group form-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              <input id="email" v-model="email" class="form-control" type="email" placeholder="email" required autofocus>
            </div>
            <div class="input-group form-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fas fa-key"></i>
                </span>
              </div>
              <input id="password" v-model="password" class="form-control" type="password" placeholder="password" required>
            </div>
            <div class="form-group">
              <input class="btn float-right login_btn" type="submit" value="Login">
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-center links">
            <span>Don&apos;t have an account?</span>
            <RouterLink to="/signup">Sign Up</RouterLink>
          </div>
          <div class="d-flex justify-content-center">
            <a href="#">Forgot your password?</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.card {
  height: 370px !important;
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
</style>
