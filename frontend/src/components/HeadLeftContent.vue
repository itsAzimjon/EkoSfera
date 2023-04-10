<template>
<div>
    <!-- <q-header elevated class="" style="background-color:#081040;color:#97A2D6"> -->
    <q-header elevated class="">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
          aria-label="Menu"
          icon="menu"
        />

        <q-toolbar-title>
          Quasar App
        </q-toolbar-title>

        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
    </q-header>
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
    <!-- style="background-color:#081040;color:#97A2D6;" -->

      <q-list>
        <q-item-label header>Menu</q-item-label>
        <q-item  tag="a"  :to='{name:"dashboard"}' clickable>
          <q-item-section avatar>
            <q-icon name="dashboard" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Dashboard</q-item-label>
          </q-item-section>
        </q-item>
        <q-item  tag="a"  :to='{name:"users"}' clickable>
          <q-item-section avatar>
            <q-icon name="people" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Foydalanuvchilar</q-item-label>
          </q-item-section>
        </q-item>
        <q-item  tag="a"  :to='{name:"roles"}' clickable>
          <q-item-section avatar>
            <q-icon name="settings" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Rollar</q-item-label>
          </q-item-section>
        </q-item>
        <q-item @click="logout" tag="a"  clickable>
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Chiqish</q-item-label>
            </q-item-section>
                  
        </q-item>
        
      </q-list>
    </q-drawer>
</div>
    
</template>
<script>

export default {
    data() {
        return {
        leftDrawerOpen: false
        }
    },
    methods: {
      logout(){
          this.$store.dispatch('Logout',this.login).then(ren=>{
            this.$router.push({name:"login"})
          }).catch(error=>{
            console.log("Login bo'lmadi")
          })
      }
    },
}
</script>