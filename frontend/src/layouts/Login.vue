
<template>
  <q-layout>
    <q-page-container>
      <q-page
        class="bg-white window-height window-width row justify-center items-center"
      >
        <div class="column">
          <div class="row">
            <h5 class="text-h5 text-primary q-my-md">
              <q-icon name="login" size="24px" />
              Login
            </h5>
          </div>
          <div class="row">
            <div class="q-gutter-md">
              <q-card square bordered class="q-pa-lg shadow-8">
                <q-card-section>
                  <q-input
                    square
                    outlined
                    clearable
                    v-model="login.email"
                    lazy-rules
                    :rules="[
                      (val) =>
                        (val && val.length > 0) || 'Please enter an email',
                    ]"
                    type="email"
                    label="Email"
                  />
                  <q-input
                    square
                    outlined
                    clearable
                    v-model="login.password"
                    lazy-rules
                    type="password"
                    label="Password"
                  />
                </q-card-section>
                <q-card-actions class="q-px-md">
                  <q-btn
                    unelevated
                    color="primary"
                    size="lg"
                    class="full-width"
                    label="Login"
                    @click="signin" 
                  />
                </q-card-actions>
              </q-card>
            </div>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
<script>
import {mapState} from "vuex"
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'HomeView',
  components:{
  },
  data() {
    return {
      login:{
        email:"",
        password:""
      }
    }
  },
  computed: {
    ...mapState({
      isLoading:state=>state.auth.isLoading,
    })
  },
  methods: {
    async signin(){
      this.$q.loading.show({
          spinnerColor: 'green',
          spinnerSize: 140,
          backgroundColor: 'blue',
          message: 'Iltimos kuting!!!',
          messageColor: 'black'
        })
      await this.$store.dispatch('Login',this.login).then(ren=>{
        this.$s("Muofaqqiyatli Kiridingiz")
        this.$router.push({name:"dashboard"})
        this.$q.loading.hide()
      }).catch(error=>{
        this.$e("Parol yoki email xato")
        this.$q.loading.hide()
      })

    }
  },
  mounted(){

    this.$store.dispatch('check').then(res=>{
      this.$router.push({name:"dashboard"})
      }).catch(error=>{
        
      })
  }
  
});
</script>
