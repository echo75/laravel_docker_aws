<script>
import { RouterLink, RouterView } from 'vue-router'
import { useAccountStore } from './stores/account'
import { mapActions, mapState } from 'pinia'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap' // Import Bootstrap JavaScript

export default {
  name: 'App',
  components: {
    RouterLink,
    RouterView
  },
  data() {
    return {
      isLoading: false,
      currentYear: new Date().getFullYear()
    }
  },
  async beforeMount() {
    this.isLoading = true
    await this.fetchUser()
    this.isLoading = false
  },
  methods: {
    ...mapActions(useAccountStore, ['fetchUser', 'logout']),
    async doLogout() {
      await this.logout()
      this.$router.push('/login')
    }
  },
  computed: {
    ...mapState(useAccountStore, ['user'])
  }
}
</script>

<template>
  <div v-if="isLoading" class="loading-container">
    <img src="../src/assets/loading.png" alt="">
  </div>
  <div v-else>
    <header class="p-3 text-bg-dark">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li>
              <RouterLink class="nav-link px-2 text-white" :class="{ active: $route.path === '/' }" to="/">Home</RouterLink>
            </li>
            <li>
              <RouterLink class="nav-link px-2 text-white" :class="{ active: $route.path === '/search' }" to="/search">Search</RouterLink>
            </li>
            <li>
              <RouterLink class="nav-link px-2 text-white" :class="{ active: $route.path === '/watchlist' }" v-show="user" to="/watchlist">My Watch List</RouterLink>
            </li>
            <li>
              <RouterLink class="nav-link px-2 text-white" :class="{ active: $route.path === '/watchedlist' }" v-show="user" to="/watchedlist">My Watched List</RouterLink>
            </li>
            <li>
              <RouterLink class="nav-link px-2 text-white" :class="{ active: $route.path === '/about' }" to="/about">About</RouterLink>
            </li>
          </ul>
          <div class="text-end">
            <button class="btn btn-outline-light me-2" type="button" v-show="!user" @click="$router.push('/login')">Login</button>
            <button class="btn btn-outline-light me-2" type="button" v-show="user" @click="doLogout">Log out</button>
            <button class="btn btn-outline-light" type="button" v-show="!user" @click="$router.push('/signup')">Sign up</button>
          </div>
        </div>
      </div>
    </header>

    <RouterView />

    <footer class="p-3 text-bg-dark fixed-bottom">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center text-white">
            <p class="mt-2 mb-2">&copy; {{ currentYear }} by Johan Hedman. Proudly created with Laravel, MySQL and Vue.js.</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
html {
  height: 101% !important;
}
.nav-link.active {
  font-weight: 700;
  color: #ffca2b !important;
}
.nav-link:hover {
  color: #ffca2b !important;
}
.btn:hover {
  color: #212529;
  background-color: #ffca2b;
  border-color: #ffca2b;
}
.loading-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.8); /* Optional: leicht durchsichtiger Hintergrund */
  z-index: 9999; /* Stellt sicher, dass der Ladeindikator über allem anderen liegt */
}
</style>

