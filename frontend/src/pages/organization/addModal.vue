<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card style="min-width: 50%" @keyup.enter="onOKClick" @keyup.esc="hide()">
      <q-card-section class="sticky">
        <div class="text-h6">{{ text }}</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <div class="text-h7">Xizmat ko'rsatuvchi korxona</div>
        <q-input 
          dense 
          v-model="form.name" 
          autofocus 
          :rules="rules.nameRules"
        />
      </q-card-section>

      <q-card-section class="q-pt-none">
        <div class="text-h7">Tuman/Shahar</div>
        <q-select 
          filled
          v-model="form.regions" 
          multiple
          :options="regions"
          label='Tuman/Shahar'
          emit-value
          map-options
        />
      </q-card-section>

      <q-card-actions align="right" class="text-blue stickybutton">
        <q-btn color="red"  label="Bekor qilish" @click="hide()"/>
        <q-btn color="blue"  label="Saqlash" @click="onOKClick"/>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
 
 <script>
  export default {
    props: {
    Data:{
        type: Array,
        required: true
    },
    text:{
      type: String,
      required: true
    }
    },
    data() {
      return {
        isPwd:true,
        form: {
            id: "",
            name: "",
            regions: [],
        },
        regions:[],
        rules: {
          nameRules: [
            val => val.length >= 3 || 'Korxona nomi 3 ta harfdan ko\'p bo\'lishi kerak'
          ]
        }
      }
    },

    emits: [
      'ok' //, 'hide'
    ],

    mounted() {
      if (this.Data.length != 0) {
        this.form = this.Data
      }
      this.getRegions();
    },

    methods: {
      show () {
        this.$refs.dialog.show()
      },
      hide () {
        this.$refs.dialog.hide()
      },

      onOKClick () {
        if (this.form.name.length < 3) {
          this.$e("Korxona nomi to'liq emas")
        } else {
          //console.log(this.form.regions)
          this.$axios.post('organization/add', this.form).then(response => {
            this.$s("Muvofaqqiyatli qo'shildi")
            this.form = {
              id: "",
              name: "",
              regions: [],
            }
            this.$emit('ok', this.form)
            this.hide();
          }).catch(error => {
            this.$e(error.response.data.error);
          });
        }
      },

      getRegions () {
        this.$axios.post('region/get').then(response => {
          // console.log(response.data.datas);
          let data = response.data.data;
          for (var i = 0; i < data.length; i++) {
            var json = {
                "value": data[i].id,
                "label": data[i].name,
            }
            this.regions.push(json)
          }
        }).catch(error => {
          this.$e("Tumanlar olishda xatolik")  
        });
      }
 
   //   onDialogHide () {
   //     // required to be emitted
   //     // when QDialog emits "hide" event
   //     this.$emit('hide')
   //   },
 
   //   onCancelClick () {
   //     // we just need to hide the dialog
   //     this.hide()
   //   }
   }
 }
 </script>
 <style scoped>
 .sticky{
   position: sticky; 
   top:0;
   z-index: 100;
   background: inherit;
 }
 .stickybutton{
   position: sticky; 
   bottom: 0;
   z-index: 100;
   background: inherit;
 }
 </style>