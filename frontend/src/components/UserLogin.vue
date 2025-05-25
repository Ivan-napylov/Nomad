<template>
  <div>
    <h2>Вход</h2>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" required>
      <input v-model="password" type="password" placeholder="Пароль" required>
      <button type="submit">Войти</button>
    </form>
    <p v-if="error" style="color: red;">{{ error }}</p>
  </div>
</template>

<script>
export default {
  name: 'UserLogin',
  data() {
    return {
      email: '',
      password: '',
      error: ''
    }
  },
  methods: {
    async login() {
      try {
        const response = await fetch('http://localhost:8000/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            email: this.email,
            password: this.password
          })
        });

        if (!response.ok) {
          throw new Error('Ошибка входа');
        }

        const data = await response.json();
        localStorage.setItem('token', data.token); // сохраняем токен
        this.$emit('login-success');
      } catch (err) {
        this.error = err.message;
      }
    }
  }
}
</script>
