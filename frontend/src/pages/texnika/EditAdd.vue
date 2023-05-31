<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card style="min-width: 50%" @keyup.enter="onOKClick" @keyup.esc="hide()">
      <q-card-section class="sticky">
        <div class="text-h6">{{ text }}</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-input
          dense
          :rules="[(val) => val.length > 3 || 'Kiritish shart']"
          v-model="form.nomer"
          autofocus
          label="Davlat raqami"
        />
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-select
          dense
          v-model="form.type"
          :options="types"
          label="Texnika turini tanlang"
          emit-value
          map-options
          hint="Texnika turi"
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin">
        <q-select
          dense
          v-model="form.organization_id"
          :options="organization"
          label="Korxonani tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-actions align="right" class="text-blue stickybutton">
        <q-btn
          style="font-size: 0.7rem"
          size="sm"
          color="red"
          label="Bekor qilish"
          @click="hide()"
        />
        <q-btn
          style="font-size: 0.7rem"
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
      disabled: false,
      organization: [],
      form: {
        id: "",
        nomer: "",
        organization_id: "",
        type: "",
      },
      types: [
        {
          value: 0,
          label: "Musorovoz - 7m3",
        },
        {
          value: 1,
          label: "Musurovoz - 12 m3",
        },
        {
          value: 2,
          label: "Krantaz",
        },
        {
          value: 3,
          label: "Samosval",
        },
        {
          value: 4,
          label: "Аssenizator",
        },
        {
          value: 5,
          label: "Yengil avtomashina",
        },
      ],
    };
  },
  emits: [
    "ok", //, 'hide'
  ],
  async mounted() {
    this.form = this.Data;
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
      if (this.form.nomer.length < 3) {
        this.$e("Texnika davlat raqamini");
      } else if (this.form.type.length < 1) {
        this.$e("Turini tanlang");
      } else {
        await this.$axios
          .post("texnika/" + this.url, this.form)
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
