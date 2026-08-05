<script setup>
import { ref, onMounted, onUnmounted } from "vue";
const isVisible = ref(false);

const handleScroll = () => {
  if (window.scrollY > 300) {
    isVisible.value = true;
  } else {
    isVisible.value = false;
  }
};

const scrollUp = () => {
    window.scrollTo(0, top);
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>

<template>
    <footer class="re-admin-footer">
        <div class="footer-content">
            <div class="main-container py-8">

                <div class="flex items-center justify-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#1C4532] border border-[#C8A028]/30 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-[#C8A028]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a7.5 7.5 0 0 0-7.5 7.5c0 5.2 6.2 10.6 7.5 10.6s7.5-5.4 7.5-10.6A7.5 7.5 0 0 0 12 1.5Z"/></svg>
                    </div>
                    <div class="footer-bottom-text">© {{ new Date().getFullYear() }} Rencontre Éthique — Administration</div>
                </div>

                <div v-if="isVisible" @click="scrollUp" id="scrollUp" class="od-scrollTo-top" style="display: block; cursor: pointer;">
                    <i class="fa fa-angle-up"></i>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.re-admin-footer {
    border-top: 1px solid rgba(28, 69, 50, .08);
    background: #0D2218;
    margin-top: 2rem;
}
.re-admin-footer .footer-bottom-text {
    color: rgba(251, 247, 240, .65);
    font-size: .82rem;
}
</style>
