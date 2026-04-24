import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Styles direkt im JavaScript definieren
const addGlobalStyles = () => {
  const style = document.createElement('style')
  style.textContent = `
    /* Grundlegende Styles für alle Seiten */
    body {
      background-color: white;
      background-size: cover;
      background-repeat: no-repeat;
      background-image: none;
      height: 100%;
      margin: 0;
      padding: 0;
    }
    
    /* Spezifische Styles nur für Login/Signup */
    body.auth-page {
      background-image: url('./assets/matterhorn.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      height: 100%;
    }
  `
  document.head.appendChild(style)
}

// Styles hinzufügen
addGlobalStyles()

// Router-Navigation Guard hinzufügen, um die auth-page-Klasse zu setzen
router.beforeEach((to, from, next) => {
  // Überprüfen, ob die aktuelle Route eine Auth-Seite ist
  const isAuthPage = to.path === '/login' || to.path === '/signup'

  // Klasse entsprechend hinzufügen oder entfernen
  if (!isAuthPage) {
    document.body.classList.add('auth-page')
  } else {
    document.body.classList.remove('auth-page')
  }

  next()
})

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
