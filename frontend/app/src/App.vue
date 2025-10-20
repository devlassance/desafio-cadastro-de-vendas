<script setup lang="ts">
</script>

<template>
  <div class="container">
    <header>
      <h2>Sales App</h2>
      <nav>
        <router-link to="/">Home</router-link>
        <router-link to="/sales">Sales</router-link>
        <router-link to="/seller">Seller</router-link>
      </nav>
    </header>
    <main>
      <router-view />
    </main>
  </div>
</template>

<style scoped>
.container { max-width: 960px; margin: 0 auto; padding: 1rem; }
header { display: flex; align-items: center; gap: 1rem; }
nav a { margin-right: .5rem; }
</style>
