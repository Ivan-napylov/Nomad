import { createApp } from 'vue';
import UserLogin from './components/UserLogin.vue';
import ProductList from './components/ProductList.vue';
import App from './App.vue'

const app = createApp({
  data() {
    return {
      isLoggedIn: !!localStorage.getItem('auth_token'),
    };
  },
  template: `
    <div>
      <Login v-if="!isLoggedIn" />
      <Products v-else />
    </div>
  `,
});

app.component('UserLogin', UserLogin);
app.component('ProductList', ProductList);
createApp(App).mount('#app')
