<script setup>

import VerticalNavbar from '@/components/VerticalNavbar.vue';
import { router } from '@inertiajs/vue3';
import DropdownLink from '@/Components/DropdownLink.vue';


// export default {
//   components: {
//     VerticalNavbar,
//   },
//   methods: {
//     handleLogout() {
//       // Logic for logout
//       console.log("Logging out...");
//     },
//   },
// };

defineProps({
    title: String,
});

const logout = () => {
    router.post(route('logout'));
};

</script>

<template>
    <div id="app">
      <VerticalNavbar />
      <div class="content">
        <!-- <div class="logout-button">
          <img src="@/assets/logout.png" alt="Logout" @click="handleLogout" />
        </div> -->

        <form @submit.prevent="logout">
            <DropdownLink as="button">
                <div class="logout-button">
                    <img src="@/assets/logout.png" alt="Logout" @click="handleLogout" />
                </div>
            </DropdownLink>
        </form>
        <slot></slot>
      </div>
    </div>
  </template>



  <style scoped>
#app {
  display: flex;
  position: relative;
}

.content {
  flex: 1;
  padding: 20px;
  background-color: #f5f5f5;
  position: relative;
}

.logout-button {
  position: absolute;
  top: 20px;
  right: 20px;
  cursor: pointer;
}

.logout-button img {
  width: 40px;
  height: 40px;
}
  </style>
