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
    mounted() {
        //tokenni tekshiruvdan o'tkazish
        this.$store.dispatch('check').then(res=>{
          //kerakli malumotlar yuklash bo'limi 
        }).catch(error=>{
          this.$router.push({name:"login"})
        })


  },
    
    
})
</script>
