<template>
  <div>
    <h3>Добавить товар (только админ)</h3>
    <form @submit.prevent="submitProduct">
      <input v-model="title" placeholder="Название" required />
      <input v-model.number="price" placeholder="Цена" type="number" required />
      <input v-model.number="stock" placeholder="Остаток" type="number" required />
      <button type="submit">Создать</button>
    </form>
    <div v-if="message">{{ message }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: '',
      price: 0,
      stock: 0,
      message: ''
    };
  },
  methods: {
    async submitProduct() {
      const token = localStorage.getItem('token');
      const response = await fetch('http://localhost:8000/api/products', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          title: this.title,
          price: this.price,
          stock: this.stock
        })
      });

      if (response.ok) {
        this.message = 'Товар успешно создан!';
        this.title = '';
        this.price = 0;
        this.stock = 0;
      } else {
        this.message = 'Ошибка при создании товара';
      }
    }
  }
}
</script>
