<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Menu</h1>
    <div v-for="foodItem in foodItems" :key="foodItem.id" class="mb-4">
      <h2 class="text-xl font-semibold">{{ foodItem.name }}</h2>
      <p>{{ foodItem.description }}</p>
      <p class="text-green-600">${{ foodItem.price }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      foodItems: [],
    };
  },
  async created() {
    const restaurantId = this.$route.params.id;
    const response = await axios.get(`/api/restaurants/${restaurantId}/food-items`);
    this.foodItems = response.data;
  },
};
</script>
