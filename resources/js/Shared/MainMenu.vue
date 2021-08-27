<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('dashboard')">
        <icon name="dashboard" class="w-4 h-4 mr-2" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('tournaments')">
        <icon name="office" class="w-4 h-4 mr-2" :class="isUrl('tournaments') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('tournaments') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Tournaments</div>
      </inertia-link>
    </div>
<!--    <div class="mb-4">-->
<!--      <inertia-link class="flex items-center group py-3" :href="route('players')">-->
<!--        <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('players') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />-->
<!--        <div :class="isUrl('players') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Players</div>-->
<!--      </inertia-link>-->
<!--    </div>-->
<!--    <div class="mb-4">-->
<!--      <inertia-link class="flex items-center group py-3" :href="route('chips')">-->
<!--        <icon name="chip" class="w-4 h-4 mr-2" :class="isUrl('chips') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />-->
<!--        <div :class="isUrl('chips') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Chips</div>-->
<!--      </inertia-link>-->
<!--    </div>-->
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter(url => currentUrl.startsWith(url)).length
    },
  },
}
</script>
