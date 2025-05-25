<template>
  <div>
    <div v-if="isLoggedIn">
      <button @click="logout">Выйти</button>
      <AuthorizedProducts />
      <AdminProductForm v-if="isAdmin" />
    </div>

    <UserLogin v-else @login-success="handleLoginSuccess" />
  </div>
</template>

<script>
import UserLogin from './components/UserLogin.vue';
import AuthorizedProducts from './components/AuthorizedProducts.vue';
import AdminProductForm from './components/AdminProductForm.vue';

export default {
  components: {
    UserLogin,
    AuthorizedProducts,
    AdminProductForm
  },
  data() {
    return {
      isLoggedIn: !!localStorage.getItem('token'),
      isAdmin: false
    };
  },
  methods: {
    async logout() {
      const token = localStorage.getItem('token');
      await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json'
        }
      });

      localStorage.removeItem('token');
      this.isLoggedIn = false;
      this.isAdmin = false;
    },
    async handleLoginSuccess() {
      this.isLoggedIn = true;

      // Проверим роль пользователя (например, /api/user)
      const token = localStorage.getItem('token');
      const response = await fetch('http://localhost:8000/api/user', {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json'
        }
      });

      if (response.ok) {
        const user = await response.json();
        this.isAdmin = user.role === 'admin';
      }
    }
  }
}
</script>
