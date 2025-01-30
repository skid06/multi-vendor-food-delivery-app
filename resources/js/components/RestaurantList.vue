<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Restaurants</h1>
    <div v-for="restaurant in restaurants" :key="restaurant.id" class="mb-4">
      <h2 class="text-xl font-semibold">{{ restaurant.name }}</h2>
      <p>{{ restaurant.description }}</p>
      <router-link :to="`/restaurants/${restaurant.id}/food-items`" class="text-blue-500">View Menu</router-link>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      restaurants: [],
    };
  },
  async created() {
    const response = await axios.get('/api/restaurants');
    this.restaurants = response.data;
  },
};
</script>
