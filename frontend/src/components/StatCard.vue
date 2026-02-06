<template>
  <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
    <div :class="`absolute left-0 top-0 bottom-0 w-1 ${colorClass}`"></div>
    <div class="flex items-start justify-between mb-4">
      <div :class="`p-3 rounded-xl ${iconBgClass} ${iconTextClass}`">
        <slot name="icon"></slot>
      </div>
    </div>
    <div>
      <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ title }}</h3>
      <div class="flex items-end gap-2">
        <span class="text-3xl font-bold text-gray-900">{{ value }}</span>
        <span v-if="subtitle" :class="subtitleColor ? subtitleColor : 'text-gray-400'" class="text-xs font-medium mb-1.5">{{ subtitle }}</span>
      </div>
      <p v-if="description" class="text-xs text-gray-400 mt-2">{{ description }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  subtitle: String,
  description: String,
  colorClass: String,
  iconBgClass: String,
  iconTextClass: String,
  subtitleColor: String,
});
</script>
