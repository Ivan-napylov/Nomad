<template>
  <div>
    <h2>Список продуктов</h2>
    <ul>
      <li v-for="product in products" :key="product.id">
        {{ product.title }} — {{ product.price }}₽ (остаток: {{ product.stock }})
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'UserProducts',
  data() {
    return {
      products: []
    };
  },
  mounted() {
    fetch('http://localhost:8000/api/products', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
      .then(res => res.json())
      .then(data => {
        console.log('Продукты:', data); // Проверка, что пришёл массив
        this.products = data;
      })
      .catch(err => {
        console.error('Ошибка загрузки продуктов:', err);
      });
  }
}
</script>
