<template>
  <div class="body">
    <div class="login-box">
      <h2>Ekosfera</h2>
      <form @keyup.enter="signin">
        <div class="user-box">
          <label>Login(Email)</label>
          <input type="text" v-model="login.email" required="" />
        </div>
        <div class="user-box">
          <label>Password</label>
          <input type="password" v-model="login.password" required="" />
        </div>
        <a type="button" @click="signin"> Kirish </a>
      </form>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import { defineComponent } from "vue";

export default defineComponent({
  name: "HomeView",
  components: {},
  data() {
    return {
      login: {
        email: "",
        password: "",
      },
    };
  },
  computed: {
    ...mapState({
      isLoading: (state) => state.auth.isLoading,
    }),
  },
  methods: {
    async signin() {
      this.$q.loading.show({
        spinnerColor: "green",
        spinnerSize: 140,
        backgroundColor: "blue",
        message: "Iltimos kuting!!!",
        messageColor: "black",
      });
      console.log(this.login);
      await this.$store
        .dispatch("Login", this.login)
        .then((ren) => {
          this.$s("Muofaqqiyatli Kiridingiz");
          this.$router.push({ name: "dashboard" });
          this.$q.loading.hide();
        })
        .catch((error) => {
          this.$e("Parol yoki email xato");
          this.$q.loading.hide();
        });
    },
  },
  mounted() {
    this.$store
      .dispatch("check")
      .then((res) => {
        this.$router.push({ name: "dashboard" });
      })
      .catch((error) => {});
    this.login = {
      email: "",
      password: "",
    };
  },
});
</script>
<style scoped>
html {
  height: 100%;
}
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  transition: background-color 50000s ease-in-out 0s,
    color 50000s ease-in-out 0s;
}
.body {
  width: 100%;
  height: 100vh;
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  background: linear-gradient(#141e30, #243b55) !important;
}

.login-box {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 400px;
  padding: 40px;
  transform: translate(-50%, -50%);
  background: rgba(0, 0, 0, 0.5);
  box-sizing: border-box;
  box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
  border-radius: 10px;
}

.login-box h2 {
  font-size: 2rem;
  margin: 0 0 30px;
  padding: 0;
  color: #fff;
  text-align: center;
}

.login-box .user-box {
  position: relative;
}

.login-box .user-box input {
  width: 100%;
  padding: 10px 0;
  font-size: 16px;
  color: #fff;
  margin-bottom: 30px;
  border: none;
  border-bottom: 1px solid #fff;
  outline: none;
  background: transparent;
}
.login-box .user-box label {
  color: #03e9f4;
}

input:focus ~ label,
.login-box .user-box input:valid ~ label {
  top: -20px;
  left: 0;
  color: #03e9f4;
  font-size: 12px;
}

.login-box form a {
  position: relative;
  display: inline-block;
  padding: 10px 20px;
  color: #03e9f4;
  border-radius: 8px;
  border: 1px solid #03e9f4;
  font-size: 16px;
  text-decoration: none;
  text-transform: uppercase;
  overflow: hidden;
  transition: 0.5s;
  margin-top: 40px;
  letter-spacing: 4px;
}

.login-box a:hover {
  /* background: #03e9f4; */
  /* color: #fff; */
  /* border-radius: 5px; */
  box-shadow: 0 2px 25px #03e9f4;
}

.login-box a span {
  position: absolute;
  display: block;
}

.login-box a span:nth-child(1) {
  top: 0;
  left: -100%;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #03e9f4);
  animation: btn-anim1 1s linear infinite;
}

@keyframes btn-anim1 {
  0% {
    left: -100%;
  }
  50%,
  100% {
    left: 100%;
  }
}

.login-box a span:nth-child(2) {
  top: -100%;
  right: 0;
  width: 2px;
  height: 100%;
  background: linear-gradient(180deg, transparent, #03e9f4);
  animation: btn-anim2 1s linear infinite;
  animation-delay: 0.25s;
}

@keyframes btn-anim2 {
  0% {
    top: -100%;
  }
  50%,
  100% {
    top: 100%;
  }
}

.login-box a span:nth-child(3) {
  bottom: 0;
  right: -100%;
  width: 100%;
  height: 2px;
  background: linear-gradient(270deg, transparent, #03e9f4);
  animation: btn-anim3 1s linear infinite;
  animation-delay: 0.5s;
}

@keyframes btn-anim3 {
  0% {
    right: -100%;
  }
  50%,
  100% {
    right: 100%;
  }
}

.login-box a span:nth-child(4) {
  bottom: -100%;
  left: 0;
  width: 2px;
  height: 100%;
  background: linear-gradient(360deg, transparent, #03e9f4);
  animation: btn-anim4 1s linear infinite;
  animation-delay: 0.75s;
}

@keyframes btn-anim4 {
  0% {
    bottom: -100%;
  }
  50%,
  100% {
    bottom: 100%;
  }
}
</style>
