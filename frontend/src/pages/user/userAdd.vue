<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card style="min-width: 50%" @keyup.enter="onOKClick" @keyup.esc="hide()">
      <q-card-section class="sticky">
        <div class="text-h6">{{ text }}</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-input dense v-model="form.name" autofocus label="FIO" />
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-input
          dense
          type="email"
          v-model="form.email"
          autofocus
          label="Email"
        />
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-select
          dense
          v-model="form.role_id"
          :options="roles"
          label="Foydalanuvchi vazifasi"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin" v-if="form.role_id == 3">
        <q-select
          dense
          v-model="form.tuman_id"
          :options="tuman"
          label="Tuman tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin" v-if="form.role_id == 2">
        <q-select
          dense
          v-model="form.organization_id"
          :options="organization"
          label="Korxonani tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-input
          label="Parol"
          v-model="form.password"
          dense
          :type="isPwd ? 'password' : 'text'"
          hint="Parol 8ta belgidan iborat bo'lsin"
        >
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility_off' : 'visibility'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
        </q-input>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-input
          label="Qayta parol"
          v-model="form.return_password"
          dense
          :type="isPwd ? 'password' : 'text'"
          hint="Qayta kiriting"
        >
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility_off' : 'visibility'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
        </q-input>
      </q-card-section>

      <q-card-actions align="right" class="text-blue stickybutton">
        <q-btn size="sm" color="red" label="Bekor qilish" @click="hide()" />
        <q-btn
          size="sm"
          color="blue"
          label="Saqlash"
          @click="onOKClick"
          :disabled="disabled"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  props: {
    Data: {
      type: Object,
      required: true,
    },
    text: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      required: true,
    },
    success: {
      type: String,
      required: true,
    },
    error: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      isPwd: true,
      form: {
        id: "",
        name: "",
        email: "",
        role_id: "",
        tuman_id: "",
        organization_id: "",
        password: "",
        return_password: "",
      },
      roles: [
        {
          value: 1,
          label: "Asosiy Admin",
        },
        {
          value: 2,
          label: "Xizmat ko'rsatish korxona admini",
        },
        {
          value: 3,
          label: "Tuman admin",
        },
      ],
      tuman: [],
      organization: [],
      disabled: false,
    };
  },
  emits: [
    "ok", //, 'hide'
  ],
  async mounted() {
    this.form = this.Data;
    await this.$axios
      .get("tumanlar")
      .then((response) => {
        let data = response.data.tumanlar;
        this.tuman = [];
        for (var i = 0; i < data.length; i++) {
          var json = {
            value: data[i].id,
            label: data[i].name,
          };
          this.tuman.push(json);
        }
      })
      .catch((error) => {
        this.$e("Tuman olishda muammo");
      });
    await this.$axios
      .get("organization")
      .then((response) => {
        let data = response.data.organization;
        this.organization = [];
        for (var i = 0; i < data.length; i++) {
          var json = {
            value: data[i].id,
            label: data[i].name,
          };
          this.organization.push(json);
        }
      })
      .catch((error) => {
        this.$e("Korxanalarni olishda muammo");
      });
  },
  methods: {
    show() {
      this.$refs.dialog.show();
    },
    hide() {
      this.$refs.dialog.hide();
    },

    async onOKClick() {
      this.disabled = true;
      this.$q.loading.show();
      if (this.form.name.length < 3) {
        this.$e("FIO to'liq emas");
      } else if (
        !this.form.email.match(
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        )
      ) {
        this.$e("Email kiriting");
      } else if (this.form.role_id.length < 1) {
        this.$e("Foydalanuvchi vazifasini tanlang");
      } else if (
        this.form.role_id == 2 &&
        this.form.organization_id.length < 1
      ) {
        this.$e("Korxona  tanlang");
      } else if (this.form.role_id == 3 && this.form.tuman_id.length < 1) {
        this.$e("Tuman tanlang");
      } else if (this.form.password.length < 8) {
        this.$e("Parol 8ta belgidan ko'p bo'lishi kerak");
      } else if (this.form.password != this.form.return_password) {
        this.$e("Ikki parol bir biriga most emas");
      } else {
        await this.$axios
          .post("user/" + this.url, this.form)
          .then((response) => {
            this.$s(this.success);
            this.$emit("ok");
            this.hide();
          })
          .catch((error) => {
            this.$e(this.error);
            this.$checkstatus(error.response.status);
          });
      }
      this.$q.loading.hide();
      this.disabled = false;
    },

    //   onDialogHide () {
    //     // required to be emitted
    //     // when QDialog emits "hide" event
    //     this.$emit('hide')
    //   },

    //   onCancelClick () {
    //     // we just need to hide the dialog
    //     this.hide()
    //   }
  },
};
</script>
<style scoped>
.sticky {
  position: sticky;
  top: 0;
  z-index: 100;
  background: inherit;
}
.stickybutton {
  position: sticky;
  bottom: 0;
  z-index: 100;
  background: inherit;
}
</style>
