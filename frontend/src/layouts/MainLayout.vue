<template>
  <div>
    <div
      v-if="isLoading"
      style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      "
    >
      <svg
        class="loading"
        width="64"
        height="64"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          opacity="0.1"
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M12 4C9.87827 4 7.84344 4.84285 6.34315 6.34315C4.84285 7.84344 4 9.87827 4 12C4 14.1217 4.84285 16.1566 6.34315 17.6569C7.84344 19.1571 9.87827 20 12 20C14.1217 20 16.1566 19.1571 17.6569 17.6569C19.1571 16.1566 20 14.1217 20 12C20 9.87827 19.1571 7.84344 17.6569 6.34315C16.1566 4.84285 14.1217 4 12 4ZM2 12C2 6.477 6.477 2 12 2C17.523 2 22 6.477 22 12C22 17.523 17.523 22 12 22C6.477 22 2 17.523 2 12Z"
          fill="black"
        />
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M12 4C9.93741 3.99564 7.95369 4.79228 6.46701 6.222C6.37219 6.31287 6.26041 6.38418 6.13803 6.43186C6.01566 6.47953 5.8851 6.50263 5.75379 6.49985C5.62249 6.49706 5.49302 6.46844 5.37278 6.41562C5.25254 6.3628 5.14388 6.28681 5.05301 6.192C4.96213 6.09719 4.89082 5.9854 4.84315 5.86303C4.79548 5.74065 4.77237 5.61009 4.77516 5.47879C4.77794 5.34748 4.80656 5.21802 4.85938 5.09778C4.91221 4.97753 4.98819 4.86887 5.08301 4.778C6.94208 2.99143 9.42164 1.99559 12 2C12.2652 2 12.5196 2.10536 12.7071 2.29289C12.8947 2.48043 13 2.73478 13 3C13 3.26522 12.8947 3.51957 12.7071 3.70711C12.5196 3.89464 12.2652 4 12 4Z"
          fill="#067D12"
        />
      </svg>
      <h6 style="margin-left: 8px">Loading...</h6>
    </div>
    <q-layout view="hHh lpR fFf" v-else>
      <HeadLeftContent />
      <q-page-container>
        <router-view />
      </q-page-container>
    </q-layout>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { defineComponent, ref } from "vue";
import HeadLeftContent from "../components/HeadLeftContent.vue";
export default defineComponent({
  name: "LayoutMain",
  computed: {
    ...mapState({
      isLoading: (state) => state.auth.isLoading,
    }),
  },
  components: {
    HeadLeftContent,
  },
  async beforeMount() {
    //tokenni tekshiruvdan o'tkazish
    this.$q.loading.show({
      spinnerColor: "green",
      spinnerSize: 140,
      backgroundColor: "blue",
      message: "Iltimos kuting!!!",
      messageColor: "black",
    });
    await this.$store
      .dispatch("check")
      .then((res) => {})
      .catch((error) => {
        this.$router.push({ name: "login" });
      });
    this.$q.loading.hide();
  },
});
</script>
