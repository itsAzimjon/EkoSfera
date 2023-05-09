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

        <q-toolbar-title> Ekosfera </q-toolbar-title>

        <div>Ekosfera.uz</div>
      </q-toolbar>
    </q-header>
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      style="padding: 10px 0 0 5px"
    >
      <!-- style="background-color:#081040;color:#97A2D6;" -->

      <q-list>
        <template v-for="item in permissions" v-bind:key="item">
          <q-item
            :to="{ name: item.url }"
            clickable
            dense
            v-if="!item.label && item.read"
          >
            <q-item-section avatar>
              <q-icon :name="item.icon" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ item.caption }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-expansion-item
            :content-inset-level="0.5"
            expand-separator
            :icon="item.icon"
            :label="item.label"
            :caption="item.caption"
            v-if="item.label && item['read']"
          >
            <template v-for="item2 in item.children" :key="item2">
              <q-item
                :to="{ name: item2.url }"
                clickable
                dense
                :key="item2"
                v-if="item2.read"
              >
                <q-item-section avatar>
                  <q-icon :name="item2.icon" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>{{ item2.caption }}</q-item-label>
                </q-item-section>
              </q-item>
            </template>
          </q-expansion-item>
        </template>
        <q-item @click="logout" clickable dense>
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
import { mapState } from "vuex";

export default {
  data() {
    return {
      leftDrawerOpen: false,
    };
  },
  computed: {
    permissions() {
      return this.$store.state.auth.permission;
    },
  },
  methods: {
    logout() {
      this.$store
        .dispatch("Logout", this.login)
        .then((ren) => {
          this.$router.push({ name: "login" });
        })
        .catch((error) => {
          console.log("Login bo'lmadi");
        });
    },
  },
};
</script>
<style scoped>
.q-router-link--active {
  background: rgba(148, 184, 218, 0.76);
  border-radius: 30px;
}
</style>
