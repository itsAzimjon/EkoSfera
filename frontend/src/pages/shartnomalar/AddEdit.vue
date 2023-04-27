<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card
      style="min-width: 60%"
      @keyup.enter="onOKClick"
      @keyup.esc="hide()"
      class="row"
    >
      <q-card-section class="sticky col-12">
        <div class="text-h6">{{ text }}</div>
      </q-card-section>

      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          v-model="form.name"
          label="Nomi"
          hint="Shartnoma qilayotgan tashkilot(shaxs)"
          autofocus
          ref="name"
          :rules="[(val) => val.length > 3 || 'Nomini kiriting']"
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          label="Stir"
          hint="Stir (INN)"
          type="number"
          v-model="form.stir"
          ref="stir"
          :rules="[(val) => val.length > 3 || 'Stir kiriting']"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-select
          dense
          v-model="form.type"
          :options="types"
          label="Shartnoma turini tanlang"
          emit-value
          map-options
          hint="Shartnoma turi"
        />
      </q-card-section>

      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          v-model="form.start"
          mask="date"
          :rules="['date']"
          ref="start"
          label="Shartnoma sanasi"
          hint="Boshlang'ich sana"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="form.start">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          hint="Tugash sana"
          dense
          v-model="form.finish"
          mask="date"
          :rules="['date']"
          ref="finish"
          label="Shartnoma muddati"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="form.finish">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          label="Shartnoma raqami"
          hint="Shartnoma raqami"
          dense
          v-model="form.raqami"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          label="Shartnoma summasi "
          hint="So'mda to'liq"
          dense
          v-model="form.summa"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-select
          label="Tuman tanlang"
          dense
          v-model="form.tuman_id"
          autofocus
          :options="tuman"
          emit-value
          map-options
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          label="Manzil (tuman nomi yozilmasin)"
          v-model="form.manzil"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          label="Rahbar"
          hint="Rahbar FIO"
          v-model="form.rahbar"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          label="Bugalter"
          hint="Bugalter FIO"
          v-model="form.bugalter"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          type="number"
          :rules="[(val) => val.length == 9 || 'Telefon raqam kiriting']"
          hint="Telefon"
          v-model="form.tel"
          autofocus
          style="font-size: 1.3rem"
        >
          <template v-slot:prepend>
            <span style="font-size: 1.3rem">+998</span>
          </template>
        </q-input>
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input dense label="MFO" v-model="form.mfo" autofocus />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          label="Hison raqam"
          v-model="form.hison_raqam"
          autofocus
        />
      </q-card-section>
      <q-card-section class="q-pt-none margin col-6 col-md-4 col-xl-3">
        <q-input
          dense
          label="QQS to'lovchining ro'yxatdan o'tish ko'di"
          v-model="form.qqs"
          autofocus
        />
      </q-card-section>
      <q-card-section
        class="q-pt-none margin col-6 col-md-4 col-xl-3"
        v-if="form.type == 2"
      >
        <q-input dense label="G'azna STIR" v-model="form.gazna" autofocus />
      </q-card-section>
      <q-card-section
        class="q-pt-none margin col-6 col-md-4 col-xl-3"
        v-if="form.type == 2"
      >
        <q-input
          dense
          label="G'azna nomi"
          v-model="form.gazna_name"
          autofocus
        />
      </q-card-section>
      <q-card-actions align="right" class="text-blue stickybutton col-12">
        <q-btn color="red" label="Bekor qilish" @click="hide()" />
        <q-btn
          color="blue"
          label="Saqlash"
          @click="onOKClick"
          :disabled="dis"
        />
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
    success: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      form: {
        id: "",
        name: "",
        stir: "",
        type: "",
        start: "",
        finish: "",
        raqami: "",
        tuman_id: "",
        manzil: "",
        summa: "",
        rahbar: "",
        bugalter: "",
        tel: "",
        mfo: "",
        hison_raqam: "",
        qqs: "",
        gazna: "",
        name: "",
      },
      types: [
        {
          value: 1,
          label: "Yuridik",
        },
        {
          value: 2,
          label: "Budjet",
        },
      ],
      tuman: [],
      dis: false,
    };
  },
  emits: [
    "ok", //, 'hide'
  ],
  mounted() {
    this.form = this.Data;
    this.$axios
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
  },
  methods: {
    show() {
      this.$refs.dialog.show();
    },
    hide() {
      this.$refs.dialog.hide();
    },

    async onOKClick() {
      this.dis = true;
      if (this.form.name.length < 3) {
        this.$refs.name.isDirty = false;
        this.$e("Nomi kiritilmagan");
      } else if (this.form.stir.length < 3) {
        this.$refs.stir.isDirty = false;
        this.$e("Stir kiriting");
      } else if (this.form.type.length < 1) {
        this.$refs.type.isDirty = false;
        this.$e("Turini tanlang");
      } else if (this.form.start.length < 1) {
        this.$e("Sharnoma sanasini kiriting");
      } else if (this.form.finish.length < 1) {
        this.$e("Sharnoma muddatini kiriting");
      } else if (this.form.raqami.length < 1) {
        this.$e("Sharnoma raqamini kiriting");
      } else if (this.form.summa.length < 1) {
        this.$e("Sharnoma summasini kiriting");
      } else if (this.form.tuman_id.length < 1) {
        this.$e("Tuman tanlang");
      } else if (this.form.manzil.length < 3) {
        this.$e("Manzil kiriting");
      } else if (this.form.rahbar.length < 3) {
        this.$e("Rahbar kiriting");
      } else if (this.form.bugalter.length < 3) {
        this.$e("Bugalter kiriting");
      } else if (this.form.tel.length != 9) {
        this.$e("Telefon raqamni kiriting");
      } else if (this.form.mfo.length < 3) {
        this.$e("MFO kiriting");
      } else if (this.form.hison_raqam.length < 3) {
        this.$e("Hison raqamini kiriting");
      } else if (this.form.qqs.length < 1) {
        this.$e("QQS to'lovchining ro'yxatdan o'tish ko'dini kiriting");
      } else if (this.form.gazna.length < 1 && this.form.type == 2) {
        this.$e("Gazna STIRini kiriting");
      } else if (this.form.gazna_name.length < 3 && this.form.type == 2) {
        this.$e("Gazna STIRini kiriting");
      } else {
        await this.$axios
          .post("shartnomalar/" + this.url, this.form)
          .then((response) => {
            this.$s(this.success);
            this.$emit("ok", this.form);
            this.hide();
          })
          .catch((error) => {
            this.$e("Xato");
            this.$checkstatus(error.response.status);
          });
      }

      this.dis = false;
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
