<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card
      class="row"
      style="min-width: 70%"
      @keyup.enter="onOKClick"
      @keyup.esc="hide()"
    >
      <q-card-section class="sticky col-12">
        <div class="text-h6">{{ text }}</div>
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          label="Yo'l varaqaa raqami"
          v-model="form.raqami"
          :rules="[(val) => val.length > 0 || 'Kiriting']"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          v-model="form.sana"
          mask="date"
          :rules="['date']"
          label="Sanasi"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="form.sana">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>

      <q-card-section class="q-pt-none margin col-6 col-md-3 col-xl-2">
        <q-select
          dense
          use-input
          @filter="tuman_filter"
          v-model="form.tuman_id"
          :options="tumanlar"
          label="Tuman tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-3 col-xl-2">
        <q-select
          dense
          use-input
          @filter="karxona_filter"
          v-model="form.organization_id"
          :options="organization"
          label="Karxona tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-2">
        <q-select
          dense
          use-input
          @filter="haydovchi_filter"
          v-model="form.haydovchi_id"
          :options="haydovchi"
          label="Haydovchi tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-3 col-xl-2">
        <q-select
          dense
          use-input
          @filter="texnika_filter"
          v-model="form.texnika_id"
          :options="texnikalar"
          label="Texnika tanlang"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          label="Chiqishdagi km"
          type="number"
          v-model="form.start_km"
          :rules="[(val) => val.length > 0 || 'Kiriting']"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          label="Kirishdagi km"
          type="number"
          v-model="form.finish_km"
          :rules="[(val) => val.length > 0 || 'Kiriting']"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          v-model="form.work_time"
          mask="time"
          :rules="['time']"
          label="Boshlang'ich vaqt"
        >
          <template v-slot:append>
            <q-icon name="schedule" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-time format24h v-model="form.work_time">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          v-model="form.free_time"
          mask="time"
          :rules="['time']"
          label="Tugash vaqt"
        >
          <template v-slot:append>
            <q-icon name="schedule" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-time format24h v-model="form.free_time">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          label="Tibbiy ko'rikdan FIO "
          type="text"
          v-model="form.tibbiy"
          :rules="[
            (val) => val.length > 3 || 'Ismda 3tadan ko\'p belgi bo\'ladi',
          ]"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          label="Texnik ko'rikdan FIO"
          type="text"
          v-model="form.texnik"
          :rules="[
            (val) => val.length > 3 || 'Ismda 3tadan ko\'p belgi bo\'ladi',
          ]"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-select
          dense
          use-input
          v-model="form.yoqilgi"
          :options="yoqilgi_type"
          label="Yoqilg'i turi"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-2 col-xl-1">
        <q-input
          dense
          label="Yoqilg'i miqdori"
          type="number"
          v-model="form.miqdori"
          :rules="[(val) => val.length > 0 || 'Kiriting']"
          autofocus
        />
      </q-card-section>

      <q-card-actions align="right" class="text-blue stickybutton col-12">
        <q-btn color="red" label="Bekor qilish" @click="hide()" />
        <q-btn color="blue" label="Saqlash" @click="onOKClick" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  props: {
    Data: {
      type: Array,
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
  },
  data() {
    return {
      form: {
        id: "",
        sana: "",
        raqami: "",
        tuman_id: "",
        organization_id: "",
        haydovchi_id: "",
        texnika_id: "",
        start_km: "",
        finish_km: "",
        yoqilgi: "",
        miqdori: "",
        tibbiy: "",
        texnik: "",
        work_time: "",
        free_time: "",
      },
      tumanlar: [],
      organization: [],
      haydovchi: [],
      texnikalar: [],
      yoqilgi_type: [
        {
          value: 0,
          label: "Gaz",
        },
        {
          value: 1,
          label: "Benzin",
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
      .get("tumanlar")
      .then((response) => {
        let data = response.data.tumanlar;
        this.tumanlar = [];
        for (var i = 0; i < data.length; i++) {
          var json = {
            value: data[i].id,
            label: data[i].name,
          };
          this.tumanlar.push(json);
        }
      })
      .catch((error) => {
        this.$e("Tuman olishda muammo");
      });
    await this.$axios
      .get("haydovchi/get")
      .then((response) => {
        let data = response.data.haydovchilar;
        this.haydovchi = [];
        for (var i = 0; i < data.length; i++) {
          var json = {
            value: data[i].id,
            label: data[i].name + " " + data[i].guvohnoma,
          };
          this.haydovchi.push(json);
        }
      })
      .catch((error) => {
        this.$e("Haydovchilarni olishda muammo");
      });
    await this.$axios
      .get("texnika/get")
      .then((response) => {
        let data = response.data.texnikalar;
        this.texnikalar = [];
        for (var i = 0; i < data.length; i++) {
          var json = {
            value: data[i].id,
            label: data[i].nomer,
          };
          this.texnikalar.push(json);
        }
      })
      .catch((error) => {
        this.$e("Haydovchilarni olishda muammo");
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
    buyurtna_filter(val, update) {
      if (val === "") {
        update(() => {
          this.optionsStore = this.store;

          // here you have access to "ref" which
          // is the Vue reference of the QSelect
        });
        return;
      }

      update(() => {
        var needle = val.toLowerCase();
        this.shartnomalar = this.all_shartnomalar.filter(
          (v) => v.label.toLowerCase().indexOf(needle) > -1
        );
      });
    },

    onOKClick() {
      if (this.form.sana.length < 1) {
        this.$e("Sanani kiriting");
      } else if (this.form.tuman_id.length < 1) {
        this.$e("Tuman tanlang");
      } else if (this.form.haydovchi_id.length < 1) {
        this.$e("Haydovchi tanlang");
      } else if (this.form.texnika_id.length < 1) {
        this.$e("Texnika tanlang");
      } else if (this.form.start_km.length < 0) {
        this.$e("Chiqishdagi Spidametr ko'rsatkichini kiriting");
      } else if (this.form.finish_km.length < 0) {
        this.$e("Kirishdagi Spidametr ko'rsatkichini kiriting");
      } else if (this.form.start_km.yoqilgi < 0) {
        this.$e("Yoqilgi turini tanlang");
      } else if (this.form.miqdori.length < 0) {
        this.$e("Yoqilg'i miqdorini kiriting");
      } else {
        this.$axios
          .post("yolvaraqa/" + this.url, this.form)
          .then((response) => {
            this.$emit("ok");
            this.$s("Muvaffaqqiyatli qo'shildi");
            this.hide();
          })
          .catch((error) => {
            this.$e("Qo'shilmadi");
            this.$checkstatus(error.response.status);
          });
      }
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
.margin {
  margin-bottom: 5px;
}
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
