<template>
  <transition
    @enter="enter"
    @leave="leave"
  >
    <div v-show="show">
      <slot />
    </div>
  </transition>
</template>

<script setup>
defineProps({
  show: {
    type: Boolean,
    default: true,
  },
})

const enter = (el) => {
  el.style.overflow = 'hidden'
  el.style.height = '0'
  el.offsetHeight // Trigger reflow
  el.style.height = el.scrollHeight + 'px'
  el.style.transition = 'height 0.3s ease-out'

  setTimeout(() => {
    el.style.overflow = 'visible'
  }, 300)
}

const leave = (el) => {
  el.style.overflow = 'hidden'
  el.style.height = el.scrollHeight + 'px'
  el.offsetHeight // Trigger reflow
  el.style.height = '0'
  el.style.transition = 'height 0.3s ease-out'
}
</script>
