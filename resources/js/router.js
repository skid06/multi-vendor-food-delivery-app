import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import RestaurantList from './components/RestaurantList.vue';
import FoodItemList from './components/FoodItemList.vue';

const routes = [
    { path: '/', component: Home },
    { path: '/restaurants', component: RestaurantList },
    { path: '/restaurants/:id/food-items', component: FoodItemList },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
