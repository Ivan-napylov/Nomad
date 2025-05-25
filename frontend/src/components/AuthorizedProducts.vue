<template>
  <div>
    <h2>Продукты (с авторизацией)</h2>
    <div v-if="error" style="color:red">{{ error }}</div>
    <ul v-if="products.length">
      <li v-for="product in products" :key="product.id">
        {{ product.title }} — {{ product.price }}₸
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      error: ''
    };
  },
  async mounted() {
    try {
      const token = localStorage.getItem('token');

      const response = await fetch('http://localhost:8000/api/products', {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });

      if (!response.ok) throw new Error('Ошибка загрузки продуктов');

      this.products = await response.json();

    } catch (err) {
      this.error = err.message;
    }
  }
}
</script>
