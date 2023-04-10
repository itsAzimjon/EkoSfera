<template>
  <router-view/>
</template>

<script>

export default {
  name: 'App',
  data: () => ({
    //
  }),
  mounted(){
    this.$store.dispatch('check').then(res=>{
      if(this.$router.currentRoute._value.name=="login"){
        this.$router.push({name:"dashboard"})
      }
      console.log('success check');
    }).catch(error=>{
        this.$router.push({name:"login"})
      })
  }
}
</script>
