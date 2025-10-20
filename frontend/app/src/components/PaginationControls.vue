<template>
  <div class="pagination">
    <button :disabled="loading || !hasPrev" @click="$emit('prev')">Prev</button>
    <span>Page {{ page }}</span>
    <button :disabled="loading || !hasNext" @click="$emit('next')">Next</button>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  page: number
  loading?: boolean
  hasPrev?: boolean
  hasNext?: boolean
}>()
</script>

<style scoped>
.pagination { display:flex; align-items:center; gap:.5rem; margin:.5rem 0; }
</style>
