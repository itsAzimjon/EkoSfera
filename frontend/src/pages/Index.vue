<template>
  <h4>Xush kelibsiz Dashboardga</h4>
</template>

<script>
export default {
  mounted() {
        //tokenni tekshiruvdan o'tkazish
        this.$store.dispatch('check').then(res=>{
          //kerakli malumotlar yuklash bo'limi 
        }).catch(error=>{
          this.$router.push({name:"login"})
        })

      },
}
</script>
<style scoped>
h4{
  color:red;
}
</style>