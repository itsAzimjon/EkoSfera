<template>
  <q-layout view="hHh lpR fFf">
    <HeadLeftContent />
    <q-page-container>
        <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import HeadLeftContent from '../components/HeadLeftContent.vue'
export default defineComponent({
    name: 'LayoutMain',
    components:{
      HeadLeftContent
    },
    async beforeMount() {
        //tokenni tekshiruvdan o'tkazish
        this.$q.loading.show({
          spinnerColor: 'green',
          spinnerSize: 140,
          backgroundColor: 'blue',
          message: 'Iltimos kuting!!!',
          messageColor: 'black'
        })
        await this.$store.dispatch('check').then(res=>{
        }).catch(error=>{
          this.$router.push({name:"login"})
        })
        this.$q.loading.hide()


  },
    
    
})
</script>
